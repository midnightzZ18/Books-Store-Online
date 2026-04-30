<?php
include 'condb.php';
session_start();

$orderID = $_GET['orderID']; // รับ ID มาจากหน้า insert_cart
$sql = "SELECT * FROM tb_order WHERE orderID = '$orderID'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จรับเงิน - Order #<?= $orderID ?></title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } } /* ซ่อนปุ่มเวลาปริ้น */
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4">
            <h2 class="text-center">ใบเสร็จรับเงิน</h2>
            <hr>
            <p><strong>เลขที่ออเดอร์:</strong> <?= $orderID ?></p>
            <p><strong>ชื่อลูกค้า:</strong> <?= htmlspecialchars($order['cus_name']) ?></p>
            <p><strong>ที่อยู่:</strong> <?= htmlspecialchars($order['address']) ?></p>
            
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>รายการ</th>
                        <th class="text-center">ราคา</th>
                        <th class="text-center">จำนวน</th>
                        <th class="text-end">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $detail = mysqli_query($conn, "SELECT od.*, s.book_Name FROM order_detail od JOIN stocks s ON od.book_id = s.book_id WHERE od.orderID = '$orderID'");
                    $subtotal = 0;
                    while($row = mysqli_fetch_array($detail)){
                        $total = $row['orderPrice'] * $row['orderQty'];
                        $subtotal += $total;
                    ?>
                    <tr>
                        <td><?= $row['book_Name'] ?></td>
                        <td class="text-center">฿<?= number_format($row['orderPrice'], 2) ?></td>
                        <td class="text-center"><?= $row['orderQty'] ?></td>
                        <td class="text-end">฿<?= number_format($total, 2) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">รวมราคาสินค้า</td>
                        <td class="text-end">฿<?= number_format($subtotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end text-danger">ส่วนลดคูปอง</td>
                        <td class="text-end text-danger">-฿<?= number_format($order['coupon_price'], 2) ?></td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="3" class="text-end"><strong>ยอดรวมสุทธิ</strong></td>
                        <td class="text-end"><strong>฿<?= number_format($order['total_price'], 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary">พิมพ์ใบเสร็จ</button>
                <a href="shop.php" class="btn btn-secondary">กลับหน้าหลัก</a>
            </div>
        </div>
    </div>
</body>
</html>