<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}
?>
<?php 
include 'condb.php';
$sql = "SELECT id, orderID, cus_id, cus_name, provinces, tracking_number,order_status,name_delivery FROM history ORDER BY reg_date DESC";
$result = mysqli_query($conn, $sql);

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
    <title>Express</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js" ></script>
    
</head>
<body>
<?php include 'expressmenu.php'; ?>
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
                        <td><?= $row['provinces'] ?></td>
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
                        <td><?= isset($row['name_delivery']) ? $row['name_delivery'] : '-' ?></td>
                        <td>
                            <a href='express_detail.php?orderID=<?= $row["orderID"] ?>&user_name=<?= $_SESSION["user_name"] ?>' class='btn btn-info'>รายละเอียด</a>
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
