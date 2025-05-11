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
include 'condb.php'; // Connect to the database

// SQL query to select orders with order_status = 1 from the history table

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            background-image: url('.png'); /* Set your desired background image */
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
</div>

<?php include 'expressmenu.php'; 

?>
<div class="container mt-3">
    <div class="alert alert-warning" role="alert" style="text-align:center;">
        <h1>EXPRESS Details</h1>
        <div class="form-group mb-3"> 
    </div>
</div> 
<style>
    .alert.alert-warning {
        background-color: #d4edda; /* เปลี่ยนสีเป็นสีเขียว */
        color: #155724; /* เปลี่ยนสีข้อความ */
        font-size: 22px; /* กำหนดขนาดฟ้อน */
        text-align: center; /* จัดข้อความให้อยู่ตรงกลาง */
    }
</style>

<div class="container mt-3">
    <div class="alert alert-warning" role="alert">      
        <?php
        // Retrieve user_name from session
        if (isset($_SESSION['user_name'])) {
            $user_name = $_SESSION['user_name'];

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("SELECT name, id FROM delivery_form WHERE user_name = ?");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if query was successful
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "ID ผู้ส่ง: " . htmlspecialchars($row['id']) . "<br>";
                echo "ชื่อ ผู้ส่ง: " . htmlspecialchars($row['name']) . "<br>";
            } else {
                echo "ไม่พบข้อมูลผู้ใช้";
            }
            $stmt->close();
        } else {
            echo "Session variable 'user_name' not set.";
        }

        ?>
    </div>
</div>

    <div class="container mt-3">
    <div style="text-align: center;">
        <?php
        // Check if orderID is provided via URL
        if (isset($_GET['orderID'])) {
            $orderID = $_GET['orderID'];

            // Use prepared statements
            $stmt = $conn->prepare("SELECT * FROM history WHERE orderID = ?");
            $stmt->bind_param("i", $orderID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if data is found
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Display order details
                echo "<h1>เลข ออเดอร์: " . htmlspecialchars($row["orderID"]) . "</h1>";
                echo "<h2>เลขพัสดุ: " . htmlspecialchars($row["tracking_number"]) . "</h2>";
                echo "<h4>ชื่อผู้รับ: " . htmlspecialchars($row["cus_name"]) . "</h4>";
                echo "<h4>ที่อยู่: " . htmlspecialchars($row["address"]) . " ตำบล: " . htmlspecialchars($row["districts"]) . "</h4>";
                echo "<h4>อำเภอ: " . htmlspecialchars($row["amphures"]) . " จังหวัด: " . htmlspecialchars($row["provinces"]) . "</h4>";
                echo "<h4>วันที่สั่งซื้อ: " . htmlspecialchars($row["reg_date"]) . "</h4>";
                echo "<h4>สถานะ: ";
                if ($row["order_status"] == 3) {
                    echo '<span style="color:#FF9900;">รอการจัดส่ง</span>';
                } else if ($row["order_status"] == 4) {
                    echo '<span style="color:#0033FF;">กำลังจัดส่ง</span>';
                } else if ($row["order_status"] == 5) {
                    echo '<span style="color:#00FF33;">จัดส่งสำเร็จ</span>';
                } else if ($row["order_status"] == 6) {
                    echo '<span style="color:#FF4500;">เกิดปัญหาในการจัดส่ง</span>';
                } else {
                    echo htmlspecialchars($row["order_status"]);
                }
                
                // Buttons for order actions
                echo "<form id='orderActions' method='post'>";
                echo "<input type='hidden' name='orderID' value='".$row["orderID"]."'>";
                echo "<br>"; 
                echo "<input type='hidden' name='user_name' value='".$_SESSION["user_name"]."'>"; // Using $_SESSION['user_name']
                echo "<button type='button' onclick='confirmdelivery(".$row["orderID"].")' class='btn btn-warning'>จัดส่งพัสดุ</button>";
                echo "&nbsp;"; echo "&nbsp;";
                echo "<button type='button' onclick='confirmwarning(".$row["orderID"].")' class='btn btn-danger'>เกิดปัญหาในการจัดส่ง</button>";
                echo "&nbsp;"; echo "&nbsp;";
                echo "<button type='button' onclick='confirmorderexpress(".$row["orderID"].")' class='btn btn-success'>จัดส่งสินค้าแล้ว</button>";
                echo "</form>";
                
            } else {
                echo "No results found.";
            }
        } else {
            echo "No orderID provided.";
        }
        ?>

        <br><a href="express_save.php?orderID=<?php echo $orderID; ?>" class="btn btn-primary">รับพัสดุ</a>
    </div>
</div>

</div>

<<script>
    function confirmwarning(orderID) {
        var confirmwarning = confirm("คุณแน่ใจหรือไม่ที่ต้องการแจ้งปัญหาในการจัดส่ง?");
        if (confirmwarning) {
            document.getElementById('confirmwarning').submit();
        }
    }
    function confirmdelivery(orderID) {
        var confirmdelivery = confirm("คุณแน่ใจหรือไม่ที่ต้องการยืนยันการจัดส่งออเดอร์นี้?");
        if (confirmdelivery) {
            document.getElementById('confirmdelivery').submit();
        }
    }
    function confirmorderexpress(orderID) {
        var confirmorderexpress = confirm("คุณแน่ใจหรือไม่ที่ต้องการจัดส่งสินค้าแล้ว?");
        if (confirmorderexpress) {
            document.getElementById('confirmorderexpress').submit();
        }
    }
    fun
</script>

</body>
</html>
<!-- Bootstrap JS -->
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
