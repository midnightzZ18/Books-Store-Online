<?php
// เชื่อมต่อกับฐานข้อมูล MySQL
$con = mysqli_connect("localhost", "root", "", "data_db");

// ตรวจสอบการเชื่อมต่อ
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit(); // ออกจากโปรแกรมถ้าเชื่อมต่อไม่ได้
}

// ดำเนินการดึงข้อมูลจากฐานข้อมูล
$query = "SELECT typebook_id, SUM(amount) AS total_amount 
          FROM stocks 
          GROUP BY typebook_id";

$result = mysqli_query($con, $query);

// ตรวจสอบว่าคิวรีสำเร็จหรือไม่
if (!$result) {
    echo "Error in query: " . mysqli_error($con);
    exit(); // ออกจากโปรแกรมถ้าเกิดข้อผิดพลาดในการคิวรี
}

// สร้างตัวแปรเก็บข้อมูลเพื่อใช้ในกราฟ
$typebook_names = array();
$typebook_amounts = array();

// ดึงข้อมูลจากผลลัพธ์ของคิวรี
while ($row = mysqli_fetch_assoc($result)) {
    $typebook_names[] = $row['typebook_id']; // เก็บชื่อประเภทหนังสือ
    $typebook_amounts[] = $row['total_amount']; // เก็บจำนวนหนังสือในแต่ละประเภท
}

// ปิดการเชื่อมต่อ MySQL
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Sales by Type</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>ยอดขายหนังสือตามประเภท</h2>
    <canvas id="typebookSalesChart" width="400" height="400"></canvas>

    <script>
        var ctx = document.getElementById('typebookSalesChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($typebook_names); ?>,
                datasets: [{
                    label: 'ยอดขายตามประเภท',
                    data: <?php echo json_encode($typebook_amounts); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
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
