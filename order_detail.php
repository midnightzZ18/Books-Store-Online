
<?php
include 'condb.php'; // เชื่อมต่อฐานข้อมูล

// คำสั่ง SQL เพื่อดึงรายการสั่งซื้อที่มี order_status = 1 เท่านั้น
$sql = "SELECT * FROM tb_order WHERE order_status = 1";
$result = $conn->query($sql);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('1.png');
        }

        .container {
            margin-top: 50px;
        }

        .order-details {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .order-details h3 {
            margin-top: 0;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th,
        .order-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .order-details th {
            background-color: #f2f2f2;
        }

        .order-details td img {
            max-width: 80px;
            max-height: 100px;
        }
        #buttonForm button {
    display: inline-block;
    margin-right: 5px; /* Adjust as needed */
}
    </style>
</head>
<body>
    <div class="container">

<?php
// ตรวจสอบว่ามีการส่งค่า orderID ผ่าน URL หรือไม่
if(isset($_GET['orderID'])) {
    // รับค่า orderID จาก URL
    $orderID = $_GET['orderID'];

    // คำสั่ง SQL เพื่อดึงข้อมูลของรายการสั่งซื้อ
    $sql = "SELECT * FROM tb_order WHERE orderID = $orderID";

    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        // ดึงข้อมูลของรายการสั่งซื้อ
        $row = $result->fetch_assoc();
        
        echo "<td>";
        echo "<form id='confirmex' method='post' action='con_ex.php'>";
echo "<input type='hidden' name='orderID' value='".$row["orderID"]."'>";
echo "<button type='button' onclick='confirmex(".$row["orderID"].")' class='btn btn-success'>ยืนยันการจัดส่ง</button>";
echo "</form>";

echo "<form id='confirmorder' method='post' action='con_order.php'>";
echo "<input type='hidden' name='orderID' value='".$row["orderID"]."'>";
echo "<button type='button' onclick='confirmorder(".$row["orderID"].")' class='btn btn-warning'>รอตรวจสอบ</button>";
echo "</form>";

echo "<form id='rejectForm' method='post' action='reject_order.php'>";
echo "<input type='hidden' name='orderID' value='".$row["orderID"]."'>";
echo "<button type='button' onclick='confirmReject(".$row["orderID"].")' class='btn btn-danger'>ปฏิเสธ</button>";
echo "</form>";



echo "</td>";
echo "</tr>";

// Displaying order details
echo "<div class='order-details'>";
echo "<h1>รายละเอียดรายการสั่งซื้อ เลขที่: ".$row["orderID"]."</h1>";
echo "<p>ชื่อลูกค้า: ".$row["cus_name"]."</p>";
echo "<p>ที่อยู่: ".$row["address"]."</p>";
echo "<p>ตำบล: ".$row["districts"]."</p>";
echo "<p>อำเภอ: ".$row["amphures"]."</p>";
echo "<p>จังหวัด: ".$row["provinces"]."</p>";
echo "<p>เบอร์โทร: ".$row["telephone"]."</p>";
echo "<p>วันที่สั่งซื้อ: ".$row["reg_date"]."</p>";
echo "<p>วันที่สั่งซื้อ: ".$row["reg_date"]."</p>";
echo "<p>ราคารวม: ".$row["coupon_price"]."</p>";
echo "<td><img src='slip/".$row['image']."' class='rounded-img img-fluid' style='width: 300px; height: 300px;'></td>";

echo "</div>";
       


echo "</td>";
echo "</tr>";
        // SQL query ที่รวมข้อมูลจากตาราง order_detail และ stocks
$sql = "SELECT od.book_id, s.book_Name, od.orderPrice, od.orderQty, (od.orderPrice * od.orderQty) AS Total
FROM order_detail od
INNER JOIN stocks s ON od.book_id = s.book_ID
WHERE od.orderID = $orderID";

$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
// Display product details table
echo "<div class='order-details'>";
echo "<h3>รายละเอียดสินค้า</h3>";
echo "<table>";
echo "<tr><th>Book ID</th><th>ชื่อหนังสือ</th><th>จำนวน</th><th>ราคาต่อเล่ม</th></tr>";

// Iterate over the results
while ($row = $result->fetch_assoc()) {
// Display row for each item
echo "<tr>";
echo "<td>".$row["book_id"]."</td>";
echo "<td>".$row["book_Name"]."</td>";

echo "<td>".$row["orderQty"]."</td>";
echo "<td>".$row["orderPrice"]."</td>";
echo "</tr>";

}

echo "</table>";

} else {
// No results found
echo "No order details found.";
}
    } else {
        // ถ้าไม่พบรายการสั่งซื้อ
        echo "ไม่พบรายการสั่งซื้อ";
    }
} else {
    // ถ้าไม่มีการระบุ orderID
    echo "ไม่พบรายการสั่งซื้อ";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
<script>
    function confirmReject(orderID) {
        var confirmReject = confirm("คุณแน่ใจหรือไม่ที่ต้องการปฏิเสธการสั่งซื้อนี้?");
        if (confirmReject) {
            document.getElementById('rejectForm').submit();
        }
    }
    function confirmorder(orderID) {
        var confirmorder = confirm("คุณแน่ใจหรือไม่ที่ต้องการรอตรวจสอบการสั่งซื้อนี้?");
        if (confirmorder) {
            document.getElementById('confirmorder').submit();
        }
    }
    function confirmex(orderID) {
        var confirmex = confirm("คุณแน่ใจหรือไม่ที่ต้องการยืนยันการสั่งซื้อนี้?");
        if (confirmex) {
            document.getElementById('confirmex').submit();
        }
    }
    fun
</script>