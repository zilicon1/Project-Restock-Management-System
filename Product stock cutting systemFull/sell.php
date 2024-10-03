<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากฟอร์มขายเมนู
$menu_id = $_POST['menu_id'];
$quantity_sold = $_POST['quantity'];
$table_number = $_POST['table_number'];  // รับข้อมูลหมายเลขโต๊ะจากฟอร์ม
$can_sell = true;  // ตัวแปรสำหรับตรวจสอบว่าสามารถขายได้หรือไม่

// ตรวจสอบว่าเมนูและจำนวนถูกต้องหรือไม่
if (isset($menu_id) && isset($quantity_sold) && isset($table_number)) {
    // ดึงข้อมูลเมนูที่ขาย รวมถึงราคา
    $sql_menu = "SELECT name, price FROM Menus WHERE id = ?";
    $stmt_menu = $conn->prepare($sql_menu);
    $stmt_menu->bind_param("i", $menu_id);
    $stmt_menu->execute();
    $result_menu = $stmt_menu->get_result();
    $row_menu = $result_menu->fetch_assoc();
    $menu_name = $row_menu['name']; // ชื่อเมนูที่ขาย
    $menu_price = $row_menu['price']; // ราคาของเมนู

    // คำนวณราคารวมสำหรับจำนวนจานที่ขาย
    $total_price = $menu_price * $quantity_sold;

    // ดึงสูตรอาหาร (Recipes) เพื่อดูวัตถุดิบที่ใช้สำหรับเมนูนี้
    $sql_recipes = "SELECT product_id, quantity_per_unit FROM Recipes WHERE menu_id = ?";
    $stmt_recipes = $conn->prepare($sql_recipes);
    $stmt_recipes->bind_param("i", $menu_id);
    $stmt_recipes->execute();
    $result_recipes = $stmt_recipes->get_result();

    if ($result_recipes->num_rows > 0) {
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
            $row_product = $result_product->fetch_assoc();
            $product_name = $row_product['name'];
            $unit = $row_product['unit'];
            $stock_quantity = $row_product['stock_quantity'];

            // ตรวจสอบว่าวัตถุดิบเพียงพอหรือไม่
            if ($stock_quantity < $quantity_used) {
                echo "วัตถุดิบ " . $product_name . " ไม่เพียงพอ (ต้องการ $quantity_used $unit, แต่มีเพียง $stock_quantity $unit)<br>";
                $can_sell = false;  // ตั้งค่าไม่สามารถขายได้
            }
        }

        // หากวัตถุดิบเพียงพอสำหรับการขาย
        if ($can_sell) {
            // รีเซ็ตผลลัพธ์และวนลูปเพื่อทำการตัดสต็อกและบันทึกประวัติ
            $result_recipes->data_seek(0);  // รีเซ็ต pointer ของผลลัพธ์

            while ($row_recipes = $result_recipes->fetch_assoc()) {
                $product_id = $row_recipes['product_id'];
                $quantity_per_unit = $row_recipes['quantity_per_unit'];
                $quantity_used = $quantity_per_unit * $quantity_sold;

                // บันทึกข้อมูลการขายลงในตาราง SalesHistory รวมราคาทั้งหมดและหมายเลขโต๊ะ
                $sql_history = "INSERT INTO SalesHistory (menu_name, product_name, quantity_used, unit, quantity_sold, total_price, table_number) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_history = $conn->prepare($sql_history);
                $stmt_history->bind_param("ssdssid", $menu_name, $product_name, $quantity_used, $unit, $quantity_sold, $total_price, $table_number);
                $stmt_history->execute();

                // ตัดสต็อกวัตถุดิบใน Products
                $sql_update_stock = "UPDATE Products SET stock_quantity = stock_quantity - ? WHERE id = ?";
                $stmt_update_stock = $conn->prepare($sql_update_stock);
                $stmt_update_stock->bind_param("di", $quantity_used, $product_id);
                $stmt_update_stock->execute();
            }

            echo "ขายสินค้าและบันทึกประวัติการขายสำเร็จ! ราคารวม: " . number_format($total_price, 2) . " บาท (โต๊ะ: " . htmlspecialchars($table_number) . ")";
        } else {
            echo "ไม่สามารถขายได้เนื่องจากวัตถุดิบไม่เพียงพอ";
        }
    } else {
        echo "ไม่พบสูตรอาหารสำหรับเมนูนี้";
    }
} else {
    echo "ข้อมูลไม่ถูกต้อง กรุณาเลือกเมนู จำนวนจาน และหมายเลขโต๊ะ";
}
?>

<a href="index.php">กลับไปยังหน้าหลัก</a>
