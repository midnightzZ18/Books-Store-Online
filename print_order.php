<?php
session_start();
include 'condb.php'; 

$sql = "SELECT * FROM tb_order WHERE orderID='" . $_SESSION["order_id"] . "'";
$result = mysqli_query($conn, $sql);
$rs = mysqli_fetch_array($result);
$total_price = $rs['total_price'];
$finalPrice =$rs['coupon_price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการสั่งซื้อ</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-10">
      <div class="alert alert-success text-center h4 mt-4" role="alert">
        การสั่งซื้อเสร็จแล้ว ขอบคุณที่มาอุดหนุนนะครับ
      </div>
      เลขที่การสั่งซื้อ : <?= $rs['orderID']; ?><br>
      ชื่อ - นามสกุล (ลูกค้า): <?= $rs['cus_name']; ?><br>
      ที่อยู่การจัดส่ง: <?= $rs['address']; ?><br>
      เบอร์โทรศัพท์: <?= $rs['telephone']; ?><br>
      <br>
      <div class="card mb-4 mt-4">
        <div class="card-body">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>รหัสสินค้า</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา</th>
            <th>จำนวน</th>
            <th>ราคารวม</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        $sql1 = "SELECT * FROM order_detail,stocks WHERE order_detail.book_id=stocks.book_id and orderID='" . $_SESSION["order_id"] . "'";
        $result1 = mysqli_query($conn, $sql1);
        while ($row = mysqli_fetch_array($result1)) {
        ?>
          <tr>
            <td><?= $row['book_id'] ?></td>
            <td><?= $row['book_name'] ?></td>
            <td><?= $row['orderPrice'] ?></td>
            <td><?= $row['orderQty'] ?></td>
            <td><?= $row['Total'] ?></td>
          </tr>
        <?php
        }
        ?>
        </tbody>
      </table>
      <h6 class="text-end"> รวมเป็นเงิน <?=number_format($finalPrice,2)?>บาท</h6>
    </div>
    </div>
    <div>
     
      

    
    </div><br><br>
    <div class="text-center">
    <button onclick="window.print()" class="btn btn-success">พิมพ์ใบสั่งซื้อ</button>
        <a href="shop.php" class="btn btn-secondary">กลับหน้าหลัก</a>
        </div>
    </div>
    
  </div>
</div>
</body>
</html>
