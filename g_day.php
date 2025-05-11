<?php
$con = mysqli_connect("localhost", "root", "", "data_db");
 
// ตรวจสอบการเชื่อมต่อ
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// ดึงข้อมูลรายการสั่งซื้อและราคารวมสุทธิตามวันที่สั่งซื้อ
$query = "SELECT DATE(reg_date) AS order_date, SUM(total_price) AS daily_revenue 
          FROM tb_order 
          GROUP BY DATE(reg_date) 
          ORDER BY DATE(reg_date)";

$result = mysqli_query($con, $query);

// สร้าง array เก็บข้อมูลวันที่และรายได้รวมตามวันที่
$order_dates = array();
$daily_revenues = array();

while ($row = mysqli_fetch_assoc($result)) {
    $order_dates[] = $row['order_date'];
    $daily_revenues[] = $row['daily_revenue'];
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>สถิติร้านค้า</title>
    <!-- เพิ่ม CDN สำหรับ Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>สถิติร้านค้ารายเดือน</h1>
    <!-- สร้าง Canvas สำหรับแสดงกราฟ -->
    <canvas id="revenueChart"></canvas>

    <script>
        // รับข้อมูลจาก PHP และแปลงเป็น JavaScript เพื่อนำไปใช้ในการสร้างกราฟ
        var orderDates = <?php echo json_encode($order_dates); ?>;
        var dailyRevenues = <?php echo json_encode($daily_revenues); ?>;

        // สร้างกราฟด้วย Chart.js
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: orderDates, // ใช้วันที่เป็นแกน x
                datasets: [{
                    label: 'รายได้รวมตามวันที่', // ชื่อเรื่องของข้อมูล
                    data: dailyRevenues, // ใช้รายได้รวมเป็นแกน y
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // สีพื้นหลังของกราฟ
                    borderColor: 'rgba(54, 162, 235, 1)', // สีเส้นของกราฟ
                    borderWidth: 1 // ความหนาของเส้นกราฟ
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // เริ่มแกน y ที่ค่า 0
                    }
                }
            }
        });
    </script>
</body>
</html>
