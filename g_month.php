<?php
 $con = mysqli_connect("localhost", "root", "", "data_db");
    
// ตรวจสอบการเชื่อมต่อ
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// ดึงข้อมูลรายการสั่งซื้อและราคารวมสุทธิตามเดือนและปีที่สั่งซื้อ
$query = "SELECT DATE_FORMAT(reg_date, '%Y-%m') AS order_month, SUM(total_price) AS monthly_revenue 
          FROM tb_order 
          GROUP BY DATE_FORMAT(reg_date, '%Y-%m') 
          ORDER BY DATE_FORMAT(reg_date, '%Y-%m')";

$result = mysqli_query($con, $query);

// สร้าง array เก็บข้อมูลเดือนและรายได้รวมตามเดือน
$order_months = array();
$monthly_revenues = array();

while ($row = mysqli_fetch_assoc($result)) {
    $order_months[] = $row['order_month'];
    $monthly_revenues[] = $row['monthly_revenue'];
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>รายงานรายได้รายวัน</title>
    <!-- เพิ่ม CDN สำหรับ Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>สถิติร้านค้ารายวัน</h1>
    <!-- สร้าง Canvas สำหรับแสดงกราฟ -->
    <canvas id="revenueChart"></canvas>

    <script>
        // รับข้อมูลจาก PHP และแปลงเป็น JavaScript เพื่อนำไปใช้ในการสร้างกราฟ
        var orderMonths = <?php echo json_encode($order_months); ?>;
        var monthlyRevenues = <?php echo json_encode($monthly_revenues); ?>;

        // สร้างกราฟด้วย Chart.js
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: orderMonths, // ใช้เดือนเป็นแกน x
                datasets: [{
                    label: 'รายได้รวมตามเดือน', // ชื่อเรื่องของข้อมูล
                    data: monthlyRevenues, // ใช้รายได้รวมเป็นแกน y
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
