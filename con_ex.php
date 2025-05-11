<?php
include 'condb.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า orderID มาหรือไม่
if(isset($_POST['orderID'])) {
    // รับค่า orderID จาก form
    $orderID = $_POST['orderID'];

    // อัปเดตสถานะการสั่งซื้อในฐานข้อมูล
    $sql = "UPDATE tb_order SET order_status = 3 WHERE orderID = $orderID";
    if ($conn->query($sql) === TRUE) {
        echo "สถานะการสั่งซื้อถูกเปลี่ยนเป็น ปฏิเสธ สำเร็จ";
        // Redirect ไปยังหน้า index.php
        echo "<script>window.location = 'confirm_order.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสถานะการสั่งซื้อ: " . $conn->error;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<?php
include 'condb.php'; // Include database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $orderID = $_POST['orderID'];
    
    // Function to generate tracking number
    function generateTrackingNumber($conn) {
        // Retrieve the maximum tracking number from the database
        $sql = "SELECT MAX(tracking_number) AS max_tracking FROM history";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $max_tracking = $row['max_tracking'];

        // Extract the numeric part of the tracking number
        $numeric_part = substr($max_tracking, 3);
        // Increment the numeric part
        $numeric_part++;
        // Format the numeric part with leading zeros
        $new_numeric_part = str_pad($numeric_part, 4, '0', STR_PAD_LEFT);

        // Concatenate with the prefix
        $new_tracking_number = 'TBE' . $new_numeric_part;
        
        return $new_tracking_number;
    }

    // Generate tracking number
    $trackingNumber = generateTrackingNumber($conn);

    // Retrieve order details from the database
    $sql = "SELECT o.*, od.book_id, s.book_Name, o.coupon_price , od.orderQty, (o.coupon_price * od.orderQty) AS Total, u.sex
    FROM tb_order o
    INNER JOIN order_detail od ON o.orderID = od.orderID
    INNER JOIN stocks s ON od.book_id = s.book_ID
    INNER JOIN user_form u ON o.cus_id = u.id
    WHERE o.orderID = $orderID";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop through each order item
        while ($row = $result->fetch_assoc()) {
            // Insert order details into the history table
            $insertSql = "INSERT INTO history (orderID, cus_id, cus_name,sex,book_Name, book_id, orderPrice, orderQty, total_price, address, districts, amphures, provinces, reg_date, order_status, tracking_number) 
                          VALUES ('".$row["orderID"]."', '".$row["cus_id"]."','".$row["cus_name"]."', '".$row["sex"]."', '".$row["book_Name"]."', '".$row["book_id"]."', '".$row["coupon_price"]."', '".$row["orderQty"]."', '".$row["Total"]."', '".$row["address"]."', '".$row["districts"]."', '".$row["amphures"]."', '".$row["provinces"]."', '".$row["reg_date"]."', '".$row["order_status"]."', '".$trackingNumber."')";

            if ($conn->query($insertSql) !== TRUE) {
                echo "Error inserting order item: " . $conn->error;
            }
        }
        // Output JavaScript to prevent redirection and display a message
        echo "<script>window.location = 'confirm_order.php';</script>";
    } else {
        echo "No order found with the given ID.";
    }
} else {
    echo "Invalid request.";
}

// Close database connection
$conn->close();
?>
