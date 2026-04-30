<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
        header('location:login_form.php');
        exit;
}
?>
<?php 
include 'condb.php';
$sql = "SELECT h.id, h.orderID, h.cus_name, h.provinces, h.amphures, 
               h.districts, h.tracking_number, h.order_status, h.name_delivery 
        FROM history h 
        ORDER BY h.reg_date DESC";$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการสั่งซื้อ</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include 'adminmenu.php'; ?>
    <div class="container">
        <div class="alert alert-primary" style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h4> หน้ารวมหมายเลขพัสดุ </h4>

        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>หมายเลข Order</th>
                    <th>ชื่อผู้รับ</th>
                    <th>จังหวัด</th>
                    <th>เลขพัสดุ</th>
                    <th>สถานะ</th>
                    <th>ชื่อผู้ส่ง</th>
                    <th>ดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $previous_order_id = null; // Track previous order ID
                while($row = mysqli_fetch_array($result)): 
                    if ($row['orderID'] !== $previous_order_id): // Check if order ID changed
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['orderID'] ?></td>
                        <td><?= $row['cus_name'] ?></td>
                        <td><?= htmlspecialchars($row['provinces']) ?></td>   <!-- จังหวัด -->
                        <td><?= htmlspecialchars($row['amphures']) ?></td>    <!-- อำเภอ -->
                        <td><?= htmlspecialchars($row['districts']) ?></td>   <!-- ตำบล -->
                        <td><?= $row['tracking_number'] ?></td>
                        <td>
                            <?php
                            if ($row["order_status"] == 3) {
                                echo '<span style="color:#FF9900;">รอการจัดส่ง</span>';
                            } else if ($row["order_status"] == 4) {
                                echo '<span style="color:#0033FF;">กำลังจัดส่ง</span>';
                            } else if ($row["order_status"] == 5) {
                                echo '<span style="color:#00FF33;">จัดส่งสำเร็จ</span>';
                            } else if ($row["order_status"] == 6) {
                                echo '<span style="color:#FF4500;">เกิดปัญหาในการจัดส่ง</span>';
                            } else {
                                echo $row["order_status"];
                            }
                            ?>
                        </td>
                        <td><?=  $row['name_delivery'] ?></td>
                        <td>
                            <a href='admin_express.php?orderID=<?= $row["orderID"] ?>&user_name=<?= $_SESSION["user_name"] ?>' class='btn btn-info'>รายละเอียด</a>
                        </td>
                    </tr>
                <?php 
                    $previous_order_id = $row['orderID']; // Update previous order ID
                    endif;
                endwhile; 
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php 
// Close connection
mysqli_close($conn);
?>
