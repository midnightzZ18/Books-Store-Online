<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
    exit;
}

include 'condb.php'; // เชื่อมต่อฐานข้อมูล

// คำสั่ง SQL เพื่อดึงรายการสั่งซื้อที่มี order_status = 1 เท่านั้น
$sql = "SELECT * FROM tb_order WHERE order_status IN (1, 2)";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<style>
        body {
            background-image: url('1.png'); /* Set your desired background image */
        }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการสั่งซื้อ</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css"  rel="stylesheet" >
</head>
<body>
<?php include 'adminmenu.php'; ?>
<div class="container mt-5">
<div class="alert alert-success" role="alert" style="text-align:center;">
            <h1>รายการสั่งซื้อ</h1>
        </div>
    <table class="table">
        <thead>
            <tr>
                <th>เลขที่ใบสั่งซื้อ</th>
                <th>ชื่อลูกค้า</th>
                <th>วันที่สั่ง</th>
                <th>สถานะสั่งซื้อ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                // วนลูปแสดงรายการสั่งซื้อ
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["orderID"]."</td>";
                    echo "<td>".$row["cus_name"]."</td>";
                    echo "<td>".$row["reg_date"]."</td>";
                    echo "<td>";
                    if ($row["order_status"] == 1) {
                        echo "คำสั่งซื้อ";
                    } else if ($row["order_status"] == 2) {
                        echo "รอตรวจสอบ";
                    } else {
                        echo $row["order_status"];
                    }

                    echo "<td>";
                    echo "<form id='rejectForm' method='post' action='reject_order.php'>";
                    echo "<input type='hidden' name='orderID' value='".$row["orderID"]."'>";
                    // เพิ่มการเรียกใช้ JavaScript เพื่อเปิดหน้าต่างการยืนยัน
                    echo "<a href='order_detail.php?orderID=".$row["orderID"]."' class='btn btn-info'>รายละเอียด</a>";
                   

                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>ไม่พบรายการสั่งซื้อ</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
