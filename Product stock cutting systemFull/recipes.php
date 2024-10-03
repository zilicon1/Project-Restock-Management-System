<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สูตรอาหาร</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('image02.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3); /* Semi-transparent background */
            z-index: 0;
        }

        /* Back button styles */
        .back-button {
            position: absolute; /* Absolute positioning */
            top: 10px; /* Distance from the top */
            left: 10px; /* Distance from the left */
            z-index: 1000;
        }

        .content {
            background-color: rgba(255, 255, 255, 0.9); /* Increased transparency */
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            width: 95%; /* เพิ่มความกว้าง */
            max-width: 1200px; /* ขยายขนาดสูงสุดให้ใหญ่ขึ้น */
            z-index: 2;
            text-align: center;
        }

        h1, h2 {
            margin: 20px 0;
        }

        table {
            margin: 20px auto;
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
            font-size: 14px;
        }

        td {
            word-wrap: break-word;
            white-space: normal;
        }

        .ingredients-cell {
            width: 45%;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
        }

        /* Style for 'กลับไปหน้า Index' button positioned at top-left */
        .back-to-index {
            position: absolute;
            top: 10px; /* Adjust the top position */
            left: 10px; /* Adjust the left position */
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            z-index: 1001; /* Ensures it's above other elements */
        }

        .back-to-index:hover {
            background-color: #45a049;
        }
        
        .actions-cell 
        {
            width: 20%; /* ปรับความกว้างให้ช่องการจัดการ */
            text-align: center; /* จัดข้อความให้อยู่ตรงกลาง */
        }

    </style>
</head>
<body>
    <div class="background-overlay"></div>

    <!-- ปุ่มกลับไปหน้า index ที่ย้ายไปบนซ้าย -->
    <a href="index.php">
        <button type="button" class="back-button">กลับไปหน้า Index</button>
    </a>

    <!-- Container for the content -->
    <div class="content">
        <h1>สูตรอาหาร</h1>

        <!-- สร้างตารางเพื่อแสดงข้อมูลสูตรอาหาร -->
        <table>
            <tr>
                <th>เมนู</th>
                <th>วัตถุดิบที่ใช้</th>
                <th>การจัดการ</th>
            </tr>

            <?php
            $sql = "
                SELECT 
                    m.id as menu_id, 
                    m.name as menu_name, 
                    GROUP_CONCAT(CONCAT(p.name, ' (', r.quantity_per_unit, ' ', p.unit, ')') SEPARATOR ', ') AS ingredients 
                FROM Recipes r
                JOIN Products p ON r.product_id = p.id
                JOIN Menus m ON r.menu_id = m.id
                GROUP BY m.name";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['menu_name'] . "</td>";
                    echo "<td>" . $row['ingredients'] . "</td>";
                    echo "<td>
                            <a href='edit_menu.php?id=" . $row['menu_id'] . "'>แก้ไข</a> |
                            <a href='delete_menu.php?id=" . $row['menu_id'] . "' onclick='return confirm(\"คุณต้องการลบเมนูนี้หรือไม่?\")'>ลบ</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>ไม่มีข้อมูลสูตรอาหาร</td></tr>";
            }
            ?>
        </table>

        <!-- ฟอร์มเพิ่มเมนูใหม่ -->
        <h2>เพิ่มเมนูใหม่</h2>
        <form action="add_menu.php" method="POST">
            <label for="menu_name">ชื่อเมนู:</label>
            <input type="text" name="menu_name" required>

            <div id="ingredients">
                <div class="ingredient">
                    <label for="product_id[]">วัตถุดิบ:</label>
                    <select name="product_id[]">
                        <?php
                        $sql_products = "SELECT id, name FROM Products";
                        $result_products = $conn->query($sql_products);
                        if ($result_products->num_rows > 0) {
                            while ($row_products = $result_products->fetch_assoc()) {
                                echo "<option value='" . $row_products['id'] . "'>" . $row_products['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <label for="quantity_per_unit[]">ปริมาณที่ใช้:</label>
                    <input type="number" name="quantity_per_unit[]" step="0.01" required>
                </div>
            </div>

            <!-- ปุ่มเพิ่มวัตถุดิบ -->
            <button type="button" onclick="addIngredient()">เพิ่มวัตถุดิบ</button>
            <button type="submit">เพิ่มเมนู</button>
        </form>
    </div>

    <script>
        function addIngredient() {
            var ingredientDiv = document.createElement('div');
            ingredientDiv.classList.add('ingredient');

            ingredientDiv.innerHTML = `
                <label for="product_id[]">วัตถุดิบ:</label>
                <select name="product_id[]">
                    <?php
                    $result_products = $conn->query($sql_products);
                    if ($result_products->num_rows > 0) {
                        while ($row_products = $result_products->fetch_assoc()) {
                            echo "<option value='" . $row_products['id'] . "'>" . $row_products['name'] . "</option>";
                        }
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
