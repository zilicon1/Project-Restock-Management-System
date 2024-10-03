<?php
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// อ่านวันที่ปัจจุบันจากฐานข้อมูลหรือไฟล์ (หรือ session ถ้าใช้)
$date_today = date("Y-m-d");  // วันที่ปัจจุบัน
$last_reset_date = file_get_contents('last_reset_date.txt');  // อ่านไฟล์วันที่รีเซ็ตล่าสุด

// ถ้าวันที่ปัจจุบันไม่ตรงกับวันที่รีเซ็ตล่าสุด
if ($date_today != $last_reset_date) {
    // รีเซ็ตข้อมูลใน DailyIngredients
    $sql = "UPDATE DailyIngredients SET quantity_used = 0";
    if ($conn->query($sql) === TRUE) {
        echo "รีเซ็ตข้อมูลสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาดในการรีเซ็ตข้อมูล: " . $conn->error;
    }

    // อัปเดตวันที่รีเซ็ตล่าสุด
    file_put_contents('last_reset_date.txt', $date_today);
} else {
    echo "ข้อมูลถูกรีเซ็ตแล้วสำหรับวันนี้.";
}
?>
