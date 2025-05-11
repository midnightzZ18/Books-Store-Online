<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head> 
<body>
    <?php
    include 'menu.php'; // Include menu conditionally based on login status
    ?>
    
    <div class="container">
        <br> 
        <div class="alert alert-success" role="alert" style="text-align:center;">
            <h3>ข้อมูลของฉัน</h3>
        </div>

        <?php 
        $user_name = $_SESSION['user_name'];
        $sql = "SELECT uf.name, uf.email, uf.sex, uf.address, uf.phone_number, uf.id, 
                       p.name_th AS province_name, a.name_th AS amphur_name, d.name_th AS district_name
                FROM user_form uf
                JOIN provinces p ON uf.Ref_prov_id = p.id
                JOIN amphures a ON uf.Ref_dist_id = a.id
                JOIN districts d ON uf.Ref_subdist_id = d.id
                WHERE uf.user_name = '$user_name'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            ?> 
            <div class="col-sm-4"> 
                <div style="border: 1px solid black; padding: 10px;">
                    <b> ชื่อ - นามสกุล : </b> &emsp; <?= htmlspecialchars($row['name']) ?>                 
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> E - Mail : </b> &emsp; <?= htmlspecialchars($row['email']) ?> 
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> เบอร์โทร : </b> &emsp; <?= htmlspecialchars($row['phone_number']) ?> 
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> เพศ : </b> &emsp; <?= htmlspecialchars($row['sex']) ?>
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> ที่อยู่ : </b> &emsp; <?= htmlspecialchars($row['address'])  ?>
                    <br> ตำบล : </b> &emsp; <?= htmlspecialchars($row['district_name']) ?>
                    <br> อำเภอ: </b> &emsp; <?= htmlspecialchars($row['amphur_name']) ?>
                    <br> จังหวัด : </b> &emsp; <?= htmlspecialchars($row['province_name']) ?>
                </div>
            </div>
            <div class="col-sm-7" style="margin-top: -330px; margin-left: auto;">
    <div style="border: 3px solid blue; padding: 20px;">
        <h1> ประวัติการสั่งซื้อ  </h1> &emsp; 
            <?php 
$user_id = $row['id'];
$order_sql = "SELECT tb_order.orderID, tb_order.order_status,tb_order.coupon_price, order_detail.book_id, order_detail.orderPrice, order_detail.orderQty, (order_detail.orderPrice * order_detail.orderQty) AS Total, stocks.book_Name, history.tracking_number
              FROM tb_order 
              INNER JOIN order_detail ON tb_order.orderID = order_detail.orderID
              INNER JOIN stocks ON order_detail.book_id = stocks.book_id
              INNER JOIN history ON tb_order.orderID = history.orderID
              WHERE tb_order.cus_id = '$user_id'";

$order_result = mysqli_query($conn, $order_sql);
$current_order_id = null;
$total_amount = 0;
// Check if there are any orders
/// Initialize arrays to store quantities for each book name
$bookQuantities = array();

// Loop through each order
while ($order_row = mysqli_fetch_assoc($order_result)) {
    // If the current order ID is different from the previous one
    if ($order_row['orderID'] != $current_order_id || $order_row['tracking_number'] != $current_tracking_number || $order_row['order_status'] != $current_order_status) {
        // If it's not the first order or tracking number or order status, close the previous container and display the total amount
        if ($current_order_id !== null && $current_tracking_number !== null && $current_order_status !== null) {
            // Display book names with summed quantities
            foreach ($bookQuantities as $bookName => $quantity) {
                echo '<b>สินค้าที่สั่ง:</b> ' . htmlspecialchars($bookName) . ' - <b>จำนวน:</b> ' . $quantity . '<br>';
            }
            echo '</div>'; // Close the container for the previous order
            $total_amount = 0; // Reset total amount for the new order
            // Clear the book quantities array for the new order
            $bookQuantities = array();
        }
        // Start a new container for the current order, tracking number, and order status
        echo '<div style="border: 1px solid black; padding: 5px; margin-bottom: 5px;">';
        echo '<b>Order ID:</b> ' . htmlspecialchars($order_row['orderID']) . '<br>';
        echo '<b>Tracking Number:</b> ' . htmlspecialchars($order_row['tracking_number']) . '<br>';
        
        echo '<b>Order Status:</b> ';
        if ($order_row["order_status"] == 1) {
            echo '<span style="color:#FF9900;">คำสั่งซื้อ</span>';
        } else if ($order_row["order_status"] == 2) {
            echo '<span style="color:#FF4500;">รอตรวจสอบ</span>';
        } else if ($order_row["order_status"] == 3) {
            echo '<span style="color:#FF1493;">กำลังเตรียมการจัดส่ง</span>';
        } else if ($order_row["order_status"] == 4) {
            echo '<span style="color:#0033FF;">กำลังจัดส่ง</span>';
        } else if ($order_row["order_status"] == 5) {
            echo '<span style="color:#00FF33;">จัดส่งสำเร็จ</span>';
        } else if ($order_row["order_status"] == 6) {
            echo '<span style="color:#FF4500;">เกิดปัญหาในการจัดส่ง</span>';
        } else {
            echo htmlspecialchars($order_row["order_status"]);
        }
        echo '<br>';
        echo '<b>ยอดรวม:</b> ' . htmlspecialchars($order_row['coupon_price']) . '<br>';

        $current_order_id = $order_row['orderID']; // Update current order ID
        $current_tracking_number = $order_row['tracking_number']; // Update current tracking number
        $current_order_status = $order_row['order_status']; // Update current order status
    }
    
    // Update book quantities
    $bookName = $order_row['book_Name'];
    $bookQty = $order_row['orderQty'];
    if (isset($bookQuantities[$bookName])) {
        $bookQuantities[$bookName] += $bookQty;
    } else {
        $bookQuantities[$bookName] = $bookQty;
    }
    
    // Sum up the total amount for the order
    $total_amount += $order_row['Total'];
}

// Display book names with summed quantities for the last order group
foreach ($bookQuantities as $bookName => $quantity) {
    echo '<b>สินค้าที่สั่ง:</b> ' . htmlspecialchars($bookName) . ' - <b>จำนวน:</b> ' . $quantity . '<br>';
    
}
?>








        <?php
        }
        mysqli_close($conn);            
        ?>
    </div>
</body>
</html>
