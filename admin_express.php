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
include 'condb.php'; // Connect to the database

// SQL query to select orders with order_status = 1 from the history table
$sql = "SELECT * FROM history WHERE order_status = 1";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <style>
        body {
            background-image: url('.png'); /* Set your desired background image */
        }
        .center-content {
            text-align: center;
            margin-top: 50px; /* Adjust margin as needed */
        }
        .order-details {
            display: inline-block;
            text-align: left; /* Reset text alignment for content inside */
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'adminmenu.php'; ?>

<div class="container mt-5">

    <div class="alert alert-warning" role="alert" style="text-align:center;">
        <h1>EXPRESS Order Details</h1>
        
    </div>

    <div class="center-content"> <!-- Center content -->
        <div class="order-details">
            <?php
            // Check if orderID is provided via URL
            if (isset($_GET['orderID'])) {
                $orderID = $_GET['orderID'];

                // SQL query to retrieve order details for the given orderID
                $sql = "SELECT * FROM history WHERE orderID = $orderID";
                $result = $conn->query($sql);

                // Check if data is found
                if ($result->num_rows > 0) {
                    echo "<table>";
                    // Initialize a variable to track if it's the first row
                    $firstRow = true;
                    // Loop through each row to display order details
                    while ($row = $result->fetch_assoc()) {
                        // Display common order details only for the first row
                        if ($firstRow) {
                            echo "<tr>";
                            echo "<th colspan='2'>Order Details</th>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>เลข ออเดอร์:</td><td>".$row["orderID"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>เลขพัสดุ:</td><td>".$row["tracking_number"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>ชื่อผู้รับ:</td><td>".$row["cus_name"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>ที่อยู่:</td><td>".$row["address"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>ตำบล:</td><td>".$row["districts"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>อำเภอ:</td><td>".$row["amphures"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>จังหวัด:</td><td>".$row["provinces"]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>สถานะ:</td><td>";
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
                            echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td>ยอดรวม:</td><td>".$row["orderPrice"]."</td>";
                            echo "</tr>";

                            // Update the variable to false after displaying common order details
                            $firstRow = false;
                        }
                        // Display individual product details for each row
                        echo "<tr>";
                        echo "<td>สินค้า:</td><td>".$row["book_name"]."  จำนวน: ".$row["orderQty"]."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No order found with ID: $orderID</p>";
                }
            } else {
                echo "<p>No order ID provided</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>