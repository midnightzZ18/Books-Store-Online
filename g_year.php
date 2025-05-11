<?php
// เชื่อมต่อกับฐานข้อมูล MySQL
$con = mysqli_connect("localhost", "root", "", "data_db");

// ตรวจสอบการเชื่อมต่อ
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit(); // ออกจากโปรแกรมถ้าเชื่อมต่อไม่ได้
}

// ดำเนินการดึงข้อมูลจากฐานข้อมูล
$query = "SELECT DATE_FORMAT(reg_date, '%Y') AS order_year, SUM(total_price) AS yearly_revenue 
          FROM tb_order 
          GROUP BY DATE_FORMAT(reg_date, '%Y') 
          ORDER BY DATE_FORMAT(reg_date, '%Y')";

$result = mysqli_query($con, $query);

// ตรวจสอบว่าคิวรีสำเร็จหรือไม่
if (!$result) {
    echo "Error in query: " . mysqli_error($con);
    exit(); // ออกจากโปรแกรมถ้าเกิดข้อผิดพลาดในการคิวรี
}

// สร้างตัวแปรเก็บข้อมูลเพื่อใช้ในกราฟ
$years = array();
$revenues = array();

// ดึงข้อมูลจากผลลัพธ์ของคิวรี
while ($row = mysqli_fetch_assoc($result)) {
    $years[] = $row['order_year'];
    $revenues[] = $row['yearly_revenue'];
}

// ปิดการเชื่อมต่อ MySQL
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yearly Revenue Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>สถิติร้านค้ารายปี</h2>
    <canvas id="yearlyRevenueChart" width="800" height="400"></canvas>

    <script>
        var ctx = document.getElementById('yearlyRevenueChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($years); ?>,
                datasets: [{
                    label: 'รายได้ตามปี',
                    data: <?php echo json_encode($revenues); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
