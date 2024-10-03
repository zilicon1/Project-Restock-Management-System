<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากฟอร์ม
$menu_name = $_POST['menu_name'];
$product_ids = $_POST['product_id'];
$quantities = $_POST['quantity_per_unit'];

// เพิ่มเมนูใหม่ในตาราง Menus
$sql_menu = "INSERT INTO Menus (name) VALUES (?)";
$stmt_menu = $conn->prepare($sql_menu);
$stmt_menu->bind_param("s", $menu_name);
$stmt_menu->execute();

// ดึงค่า menu_id ของเมนูที่เพิ่งถูกเพิ่ม
$menu_id = $conn->insert_id;

// เพิ่มวัตถุดิบที่ใช้ในตาราง Recipes
for ($i = 0; $i < count($product_ids); $i++) {
    $product_id = $product_ids[$i];
    $quantity = $quantities[$i];

    $sql_recipe = "INSERT INTO Recipes (menu_id, product_id, quantity_per_unit) VALUES (?, ?, ?)";
    $stmt_recipe = $conn->prepare($sql_recipe);
    $stmt_recipe->bind_param("iid", $menu_id, $product_id, $quantity);
    $stmt_recipe->execute();
}

echo "เพิ่มเมนูใหม่สำเร็จ!";
?>

<a href="recipes.php">กลับไปยังหน้าสูตรอาหาร</a>

