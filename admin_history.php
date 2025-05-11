<?php
include 'condb.php'; // Connect to the database
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            background-image: url('path/to/your/image.png'); /* Set your desired background image */
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'adminmenu.php'; ?>

<div class="container mt-5">
    <div class="alert alert-warning" role="alert" style="text-align:center;">
        <h1>ประวัติการขาย</h1>
    </div>

    <div class="order-details">
        <?php
        $sql = "SELECT id, orderID, cus_id, cus_name, sex, book_Name, book_id, orderPrice, orderQty, total_price, address, districts, amphures, provinces, reg_date, order_status, tracking_number FROM history";
        $result = mysqli_query($conn, $sql);

        // Check if data is found
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ลำดับ</th>';
            echo '<th>เลข ออเดอร์</th>';
            echo '<th>ชื่อผู้รับ</th>';
            echo '<th>ชื่อหนังสือ</th>';
            echo '<th>จำนวน</th>';
            echo '<th>ยอดรวม</th>';
            echo '<th>ที่อยู่</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Initialize a variable to keep track of the current orderID
            $currentOrderID = null;

            while ($row = $result->fetch_assoc()) {
                // Check if the orderID has changed
                if ($row['orderID'] !== $currentOrderID) {
                    // If yes, close the previous row (if it exists) and start a new row
                    if ($currentOrderID !== null) {
                        echo '</td></tr>';
                    }

                    // Start a new table row for the current orderID
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['orderID'] . '</td>';
                    echo '<td>' . $row["cus_name"] . '</td>';
                    echo '<td>' . $row["book_Name"] . '</td>';
                    echo '<td>' . $row["orderQty"] . '</td>';
                    echo '<td>' . $row["orderPrice"] . '</td>';
                    echo '<td>' . $row["provinces"] . '</td>';
                    
                    // Update the currentOrderID variable
                    $currentOrderID = $row['orderID'];
                } else {
                    echo '<tr>';
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td></td>';
                    // If the orderID is the same, continue adding columns to the current row
                    echo '<td>' . $row["book_Name"] . '</td>';
                    echo '<td>' . $row["orderQty"] . '</td>';
                    echo '<td>' . $row["orderPrice"] . '</td>';
                    echo '<td>' . $row["provinces"] . '</td>';
                }
            }

            // Close the last row (if it exists)
            if ($currentOrderID !== null) {
                echo '</td></tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo "No data found in the history table.";
        }
        ?>
    </div>

    <!-- ... (remaining code) ... -->

    <!-- Bootstrap JS -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
