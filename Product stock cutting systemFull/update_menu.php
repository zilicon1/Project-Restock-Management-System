<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากฟอร์มแก้ไขเมนู
$menu_id = $_POST['menu_id'];
$menu_name = $_POST['menu_name'];
$product_ids = $_POST['product_id'];
$quantities = $_POST['quantity_per_unit'];

// อัปเดตชื่อเมนูในตาราง Menus
$sql_menu = "UPDATE Menus SET name = ? WHERE id = ?";
$stmt_menu = $conn->prepare($sql_menu);
$stmt_menu->bind_param("si", $menu_name, $menu_id);
$stmt_menu->execute();

// ลบข้อมูลวัตถุดิบเก่าที่ใช้ในเมนูจากตาราง Recipes
$sql_delete = "DELETE FROM Recipes WHERE menu_id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $menu_id);
$stmt_delete->execute();

// เพิ่มข้อมูลวัตถุดิบใหม่ที่ใช้ในเมนูในตาราง Recipes
for ($i = 0; $i < count($product_ids); $i++) {
    $product_id = $product_ids[$i];
    $quantity = $quantities[$i];

    $sql_recipe = "INSERT INTO Recipes (menu_id, product_id, quantity_per_unit) VALUES (?, ?, ?)";
    $stmt_recipe = $conn->prepare($sql_recipe);
    $stmt_recipe->bind_param("iid", $menu_id, $product_id, $quantity);
    $stmt_recipe->execute();
}

echo "บันทึกการแก้ไขเมนูสำเร็จ!";
?>

<a href="recipes.php">กลับไปยังหน้าสูตรอาหาร</a>
