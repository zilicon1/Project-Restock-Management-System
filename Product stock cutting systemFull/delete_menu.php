<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับค่า ID ของเมนูที่ต้องการลบ
$menu_id = $_GET['id'];

try {
    // เริ่มการ transaction
    $conn->begin_transaction();

    // ลบข้อมูลวัตถุดิบที่เชื่อมโยงกับเมนูจากตาราง Recipes ก่อน
    $sql_delete_recipes = "DELETE FROM Recipes WHERE menu_id = ?";
    $stmt_delete_recipes = $conn->prepare($sql_delete_recipes);
    $stmt_delete_recipes->bind_param("i", $menu_id);
    $stmt_delete_recipes->execute();

    // ลบข้อมูลเมนูจากตาราง Menus
    $sql_delete_menu = "DELETE FROM Menus WHERE id = ?";
    $stmt_delete_menu = $conn->prepare($sql_delete_menu);
    $stmt_delete_menu->bind_param("i", $menu_id);
    $stmt_delete_menu->execute();

    // ถ้าลบทั้งสองสำเร็จให้ commit การ transaction
    $conn->commit();

    echo "ลบเมนูและวัตถุดิบที่เกี่ยวข้องสำเร็จ!";
} catch (mysqli_sql_exception $exception) {
    // ถ้าเกิดข้อผิดพลาด ให้ rollback การ transaction
    $conn->rollback();
    echo "เกิดข้อผิดพลาดในการลบเมนู: " . $exception->getMessage();
}
?>

<a href="recipes.php">กลับไปยังหน้าสูตรอาหาร</a>

