<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// รับค่า ID ของเมนูที่ต้องการแก้ไข
$menu_id = $_GET['id'];

// ดึงข้อมูลเมนูที่ต้องการแก้ไขจากตาราง Menus
$sql_menu = "SELECT name FROM Menus WHERE id = ?";
$stmt_menu = $conn->prepare($sql_menu);
$stmt_menu->bind_param("i", $menu_id);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();
$row_menu = $result_menu->fetch_assoc();

// ดึงข้อมูลวัตถุดิบที่ใช้ในเมนูนี้จากตาราง Recipes
$sql_recipes = "SELECT r.product_id, r.quantity_per_unit, p.name 
                FROM Recipes r 
                JOIN Products p ON r.product_id = p.id 
                WHERE r.menu_id = ?";
$stmt_recipes = $conn->prepare($sql_recipes);
$stmt_recipes->bind_param("i", $menu_id);
$stmt_recipes->execute();
$result_recipes = $stmt_recipes->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขเมนู</title>
</head>
<body>
    <h1>แก้ไขเมนู: <?php echo $row_menu['name']; ?></h1>

    <!-- ฟอร์มแก้ไขเมนู -->
    <form action="update_menu.php" method="POST">
        <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">

        <label for="menu_name">ชื่อเมนู:</label>
        <input type="text" name="menu_name" value="<?php echo $row_menu['name']; ?>" required>

        <!-- วัตถุดิบที่ใช้ -->
        <h2>วัตถุดิบที่ใช้</h2>
        <div id="ingredients">
            <?php while ($row_recipes = $result_recipes->fetch_assoc()) { ?>
            <div class="ingredient">
                <label for="product_id[]">วัตถุดิบ:</label>
                <select name="product_id[]">
                    <?php
                    // ดึงรายการวัตถุดิบทั้งหมดจากตาราง Products
                    $sql_products = "SELECT id, name FROM Products";
                    $result_products = $conn->query($sql_products);
                    while ($row_products = $result_products->fetch_assoc()) {
                        $selected = ($row_products['id'] == $row_recipes['product_id']) ? 'selected' : '';
                        echo "<option value='" . $row_products['id'] . "' " . $selected . ">" . $row_products['name'] . "</option>";
                    }
                    ?>
                </select>

                <label for="quantity_per_unit[]">ปริมาณที่ใช้:</label>
                <input type="number" name="quantity_per_unit[]" step="0.01" value="<?php echo $row_recipes['quantity_per_unit']; ?>" required>
            </div>
            <?php } ?>
        </div>

        <!-- ปุ่มเพิ่มวัตถุดิบ -->
        <button type="button" onclick="addIngredient()">เพิ่มวัตถุดิบ</button>

        <!-- ปุ่มบันทึกการแก้ไข -->
        <button type="submit">บันทึกการแก้ไข</button>
    </form>

    <!-- ปุ่มกลับไปหน้า recipes -->
    <a href="recipes.php">กลับไปยังหน้าสูตรอาหาร</a>

    <script>
        // ฟังก์ชันเพิ่มวัตถุดิบในฟอร์ม
        function addIngredient() {
            var ingredientDiv = document.createElement('div');
            ingredientDiv.classList.add('ingredient');

            ingredientDiv.innerHTML = `
                <label for="product_id[]">วัตถุดิบ:</label>
                <select name="product_id[]">
                    <?php
                    $result_products = $conn->query($sql_products);
                    while ($row_products = $result_products->fetch_assoc()) {
                        echo "<option value='" . $row_products['id'] . "'>" . $row_products['name'] . "</option>";
                    }
                    ?>
                </select>
                <label for="quantity_per_unit[]">ปริมาณที่ใช้:</label>
                <input type="number" name="quantity_per_unit[]" step="0.01" required>
            `;
            document.getElementById('ingredients').appendChild(ingredientDiv);
        }
    </script>
</body>
</html>
