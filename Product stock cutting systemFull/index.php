<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

$error_message = "";  // เก็บข้อความแจ้งเตือนในกรณีวัตถุดิบไม่พอ
$success_message = "";  // เก็บข้อความเมื่อขายสำเร็จ

// รับข้อมูลจากฟอร์มขายเมนู
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_id = $_POST['menu_id'];
    $quantity_sold = $_POST['quantity'];
    $table_number = $_POST['table_number']; // รับเลขโต๊ะจากฟอร์ม
    $can_sell = true;  // ตัวแปรสำหรับตรวจสอบว่าสามารถขายได้หรือไม่

    // ตรวจสอบว่าเมนูและจำนวนถูกต้องหรือไม่
    if (isset($menu_id) && isset($quantity_sold) && isset($table_number)) {
        // ดึงข้อมูลเมนูที่ขาย
        $sql_menu = "SELECT name FROM Menus WHERE id = ?";
        $stmt_menu = $conn->prepare($sql_menu);
        $stmt_menu->bind_param("i", $menu_id);
        $stmt_menu->execute();
        $result_menu = $stmt_menu->get_result();
        if ($result_menu->num_rows > 0) {
            $row_menu = $result_menu->fetch_assoc();
            $menu_name = $row_menu['name']; // ชื่อเมนูที่ขาย
        } else {
            $error_message = "ไม่พบเมนูที่เลือก";
        }

        // ดึงสูตรอาหาร (Recipes) เพื่อดูวัตถุดิบที่ใช้สำหรับเมนูนี้
        $sql_recipes = "SELECT product_id, quantity_per_unit FROM Recipes WHERE menu_id = ?";
        $stmt_recipes = $conn->prepare($sql_recipes);
        $stmt_recipes->bind_param("i", $menu_id);
        $stmt_recipes->execute();
        $result_recipes = $stmt_recipes->get_result();

        if ($result_recipes->num_rows > 0) {
            $required_products = []; // เก็บข้อมูลวัตถุดิบที่ต้องใช้

            // ตรวจสอบว่ามีวัตถุดิบเพียงพอสำหรับการขายหรือไม่
            while ($row_recipes = $result_recipes->fetch_assoc()) {
                $product_id = $row_recipes['product_id'];
                $quantity_per_unit = $row_recipes['quantity_per_unit'];
                $quantity_used = $quantity_per_unit * $quantity_sold; // ปริมาณวัตถุดิบที่ใช้

                // ดึงข้อมูลวัตถุดิบจากตาราง Products เพื่อตรวจสอบสต็อก
                $sql_product = "SELECT name, unit, stock_quantity FROM Products WHERE id = ?";
                $stmt_product = $conn->prepare($sql_product);
                $stmt_product->bind_param("i", $product_id);
                $stmt_product->execute();
                $result_product = $stmt_product->get_result();
                if ($result_product->num_rows > 0) {
                    $row_product = $result_product->fetch_assoc();
                    $product_name = $row_product['name'];
                    $unit = $row_product['unit'];
                    $stock_quantity = $row_product['stock_quantity'];

                    // ตรวจสอบว่าวัตถุดิบเพียงพอหรือไม่
                    if ($stock_quantity < $quantity_used) {
                        $error_message .= "วัตถุดิบ <strong>" . $product_name . "</strong> ไม่เพียงพอ (ต้องการ <strong>$quantity_used $unit</strong>, แต่มีเพียง <strong>$stock_quantity $unit</strong>)<br>";
                        $can_sell = false;  // ตั้งค่าไม่สามารถขายได้
                    } else {
                        // เก็บข้อมูลสำหรับการอัปเดตสต็อกและบันทึกประวัติ
                        $required_products[] = [
                            'product_id' => $product_id,
                            'product_name' => $product_name,
                            'unit' => $unit,
                            'quantity_used' => $quantity_used
                        ];
                    }
                } else {
                    $error_message .= "ไม่พบวัตถุดิบที่มี ID = $product_id<br>";
                    $can_sell = false;
                }
            }

            // หากวัตถุดิบเพียงพอสำหรับการขาย
            if ($can_sell) {
                foreach ($required_products as $product) {
                    $product_id = $product['product_id'];
                    $product_name = $product['product_name'];
                    $unit = $product['unit'];
                    $quantity_used = $product['quantity_used'];

                    // บันทึกข้อมูลการขายลงในตาราง SalesHistory รวมหมายเลขโต๊ะ
                    $sql_history = "INSERT INTO SalesHistory (menu_name, product_name, quantity_used, unit, quantity_sold, table_number) 
                                    VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_history = $conn->prepare($sql_history);
                    $stmt_history->bind_param("ssdiss", $menu_name, $product_name, $quantity_used, $unit, $quantity_sold, $table_number);
                    $stmt_history->execute();

                    // ตัดสต็อกวัตถุดิบใน Products
                    $sql_update_stock = "UPDATE Products SET stock_quantity = stock_quantity - ? WHERE id = ?";
                    $stmt_update_stock = $conn->prepare($sql_update_stock);
                    $stmt_update_stock->bind_param("di", $quantity_used, $product_id);
                    $stmt_update_stock->execute();
                }

                $success_message = "ขายสินค้าและบันทึกประวัติการขายสำเร็จ! (โต๊ะ: $table_number)";
            }
        } else {
            $error_message = "ไม่พบสูตรอาหารสำหรับเมนูนี้";
        }
    } else {
        $error_message = "ข้อมูลไม่ถูกต้อง กรุณาเลือกเมนู จำนวนจาน และระบุเลขโต๊ะ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการสต็อก</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-image: url('image02.jpg'); /* ใส่ URL ของภาพพื้นหลัง */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        /* สร้างพื้นหลังโปร่งแสง */
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3); /* พื้นหลังโปร่งแสง */
            z-index: 0;
        }

        /* กำหนดรูปแบบของฟอร์มและเนื้อหา */
        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); /* เพิ่มพื้นหลังโปร่งแสงให้กับฟอร์ม */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            z-index: 2; /* ให้อยู่เหนือพื้นหลังโปร่งแสง */
            position: relative;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* ปรับให้ช่องต่างๆ เท่ากัน */
        select, input[type="number"], input[type="text"], button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* ปรับแต่งปุ่ม */
        button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        /* ข้อความแจ้งเตือน */
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
           
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

    <!-- ชั้นพื้นหลังโปร่งแสง -->
    <div class="background-overlay"></div>

    <div class="container">
        <h1>ระบบจัดการสต็อก</h1>

        <!-- ฟอร์มขายสินค้า -->
        <form action="" method="POST">
            <label for="menu_id">เลือกเมนู:</label>
            <select name="menu_id" id="menu_id" required>
                <option value="" disabled selected>-- กรุณาเลือกเมนู --</option>
                <?php
                // ดึงรายการเมนูอาหารจากฐานข้อมูล
                $sql = "SELECT id, name FROM Menus";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>ไม่มีเมนู</option>";
                }
                ?>
            </select>

            <label for="quantity">จำนวนจาน:</label>
            <input type="number" name="quantity" id="quantity" min="1" required>

            <!-- เพิ่มฟิลด์สำหรับกรอกเลขโต๊ะ -->
            <label for="table_number">เลขโต๊ะ:</label>
            <input type="text" name="table_number" id="table_number" required>

            <button type="submit">ขาย</button>
        </form>

        <!-- แสดงข้อความแจ้งเตือน -->
        <?php
        if (!empty($error_message)) {
            echo "<div class='message error'>$error_message</div>";
        }

        if (!empty($success_message)) {
            echo "<div class='message success'>$success_message</div>";
        }
        ?>

        <div class="dropdown-container">
            <label for="extra_actions">การทำงานเพิ่มเติม:</label>
            <select id="extra_actions" onchange="navigateToPage(this.value)">
                <option value="" disabled selected>-- กรุณาเลือกการทำงาน --</option>
                <option value="manage_stock.php">จัดการสต็อกวัตถุดิบ</option>
                <option value="sales_history.php">ประวัติการขายและเติมสต็อก</option>
                <option value="notify.php">แจ้งเตือนวัตถุดิบที่ต้องซื้อ</option>
                <option value="recipes.php">สูตรเมนู</option>
            </select>
        </div>
    </div>

    <script>
        // ฟังก์ชันนี้จะถูกเรียกเมื่อมีการเปลี่ยนแปลงค่าใน dropdown
        function navigateToPage(url) {
            if (url) {
                window.location.href = url; // เปลี่ยนไปยังหน้าที่เลือก
            }
        }
    </script>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
