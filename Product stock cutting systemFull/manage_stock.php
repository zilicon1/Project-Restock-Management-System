<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการสต็อกวัตถุดิบ</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styling */
        body { 
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative; /* Allow positioning of child elements */
            margin: 0; /* Remove default margin */
            font-family: Arial, sans-serif;
            background-image: url('image02.jpg'); /* ใส่ URL ของภาพพื้นหลัง */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
        }
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3); /* พื้นหลังสีขาวจางๆ */
            z-index: 0;
        }
        /* Back button styles */
        .back-button {
            position: absolute; /* Absolute positioning */
            top: 10px; /* Distance from the top */
            left: 10px; /* Distance from the left */
            z-index: 1000; /* Ensure it appears above other elements */
        }

        .content {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); /* เพิ่มพื้นหลังโปร่งแสงให้กับฟอร์ม */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 900px;
            z-index: 2; /* ให้อยู่เหนือพื้นหลังโปร่งแสง */
            position: relative;
            margin: 20px 0 20px 0;
        }

        h1, h2 {
            margin: 20px 0;
        }

        table {
            margin: 20px 0;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        button {
            margin-top: 20px; /* Add margin above the button */
            padding: 10px 20px; /* Add padding for a better button appearance */
        }
    </style>
</head>
<body>
<div class="background-overlay"></div>
<!-- ปุ่มกลับไปหน้า Index ที่มุมบนซ้าย -->
<div class="back-button">
    <a href="index.php">
        <button type="button">กลับไปหน้า Index</button>
    </a>
</div>

<div class="content">
    <h1>การจัดการสต็อกเเละวัตถุดิบ</h1>

    <!-- ฟอร์มเติมสต็อกวัตถุดิบที่มีอยู่ -->
    <h2>เติมสต็อกวัตถุดิบที่มีอยู่</h2>
    <form action="restock_existing.php" method="POST">
        <label for="product">เลือกวัตถุดิบ:</label>
        <select name="product_id" required>
            <option value="" disabled selected>-- กรุณาเลือกวัตถุดิบ --</option>
            <?php
            // ดึงรายการวัตถุดิบจากฐานข้อมูล
            $sql = "SELECT id, name FROM Products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                }
            } else {
                echo "<option value='' disabled>ไม่มีวัตถุดิบ</option>";
            }
            ?>
        </select>

        <label for="quantity">จำนวนที่ต้องการเติม:</label>
        <input type="number" name="quantity" min="1" required>

        <button type="submit">เติมสต็อก</button>
    </form>

    <!-- ฟอร์มเพิ่มวัตถุดิบใหม่ -->
    <h2>เพิ่มวัตถุดิบใหม่</h2>
    <form action="add_stock.php" method="POST">
        <label for="name">ชื่อวัตถุดิบ:</label>
        <input type="text" name="name" required>
        <label for="stock_quantity">ปริมาณ:</label>
        <input type="number" name="stock_quantity" min="1" required>
        <label for="unit">หน่วย:</label>
        <input type="text" name="unit" required>
        <button type="submit">เพิ่มวัตถุดิบใหม่</button>
    </form>
    
     <!-- ฟอร์มเพิ่มวัตถุดิบสำหรับเมนูต่อจาน -->
     <h2>คำนวณและเพิ่มวัตถุดิบสำหรับการทำเมนู</h2>
    <form action="manage_stock.php" method="POST">
        <label for="menu">เลือกเมนู:</label>
        <select name="menu_id" required>
            <option value="all">-- เมนูทั้งหมด --</option> <!-- ตัวเลือกสำหรับเมนูทั้งหมด -->
            <?php
            // ดึงรายการเมนูจากฐานข้อมูล
            $sql_menus = "SELECT id, name FROM Menus";
            $result_menus = $conn->query($sql_menus);
            if ($result_menus->num_rows > 0) {
                while ($row_menus = $result_menus->fetch_assoc()) {
                    echo "<option value='" . $row_menus["id"] . "'>" . $row_menus["name"] . "</option>";
                }
            } else {
                echo "<option value='' disabled>ไม่มีเมนู</option>";
            }
            ?>
        </select>

        <label for="servings">จำนวนจานที่ต้องการเตรียมวัตถุดิบ:</label>
        <input type="number" name="servings" min="1" required>
        <button type="submit">เพิ่มวัตถุดิบ</button>
    </form>
    <!-- ตารางแสดงรายการวัตถุดิบ -->
    <h2>รายการวัตถุดิบ</h2>
    <table>
        <tr>
            <th>ชื่อวัตถุดิบ</th>
            <th>ปริมาณที่เหลือ</th>
            <th>หน่วย</th>
            <th>การจัดการ</th>
        </tr>
        <?php
        // ดึงรายการวัตถุดิบทั้งหมดจากฐานข้อมูล
        $sql = "SELECT * FROM Products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["stock_quantity"] . "</td>
                        <td>" . $row["unit"] . "</td>
                        <td>
                            <a href='edit_stock.php?id=" . $row["id"] . "'>แก้ไข</a> |
                            <a href='delete_stock.php?id=" . $row["id"] . "' onclick='return confirm(\"คุณต้องการลบวัตถุดิบนี้ใช่หรือไม่?\")'>ลบ</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>ไม่มีข้อมูลวัตถุดิบ</td></tr>";
        }
        ?>
    </table>

    <!-- ปุ่มรีเซ็ตสต็อกทั้งหมด -->
    <h2>รีเซ็ตสต็อก</h2>
    <form action="reset_product_stock.php" method="POST" >
        <button type="submit">รีเซ็ตสต็อกทั้งหมด</button>
    </form>


    <?php
    // ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
    if (isset($_POST['servings']) && isset($_POST['menu_id'])) {
        $servings = $_POST['servings'];  // จำนวนจานที่ผู้ใช้กำหนด
        $menu_id = $_POST['menu_id'];    // เมนูที่เลือก

        if ($menu_id == "all") {
            // ถ้าเลือกเมนูทั้งหมด ให้คำนวณวัตถุดิบสำหรับทุกเมนู
            $sql = "
                SELECT 
                    r.product_id, 
                    SUM(r.quantity_per_unit * ?) AS total_needed, 
                    p.name AS product_name, 
                    p.unit 
                FROM 
                    Recipes r 
                JOIN 
                    Products p ON r.product_id = p.id
                GROUP BY 
                    r.product_id";
            
            // เตรียมคำสั่ง SQL
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $servings);  // แทนที่ค่าจำนวนจานที่กำหนด
        } else {
            // ถ้าเลือกเมนูเฉพาะ ให้คำนวณวัตถุดิบสำหรับเมนูนั้น
            $sql = "
                SELECT 
                    r.product_id, 
                    (r.quantity_per_unit * ?) AS total_needed, 
                    p.name AS product_name, 
                    p.unit 
                FROM 
                    Recipes r 
                JOIN 
                    Products p ON r.product_id = p.id
                WHERE 
                    r.menu_id = ?
                GROUP BY 
                    r.product_id";
            
            // เตรียมคำสั่ง SQL
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $servings, $menu_id);  // แทนที่ค่าจำนวนจานและเมนูที่กำหนด
        }

        // รันคำสั่ง SQL
        $stmt->execute();
        $result = $stmt->get_result();

        // วนลูปเพิ่มวัตถุดิบในสต็อกตามจำนวนที่คำนวณได้
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $total_needed = $row['total_needed'];
            $product_name = $row['product_name'];
            $unit = $row['unit'];

            // อัปเดตสต็อกวัตถุดิบในตาราง Products
            $sql_update = "UPDATE Products SET stock_quantity = stock_quantity + ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("di", $total_needed, $product_id);
            $stmt_update->execute();

            echo "<p>เพิ่มวัตถุดิบ: $product_name จำนวน: $total_needed $unit ในสต็อก</p>";
        }

        echo "<p>อัปเดตสต็อกวัตถุดิบเสร็จสิ้น</p>";
    }
    ?>
</div>
</body>
</html>