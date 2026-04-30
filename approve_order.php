<?php
include 'condb.php';
session_start();

if(isset($_GET['id'])){
    $orderID = intval($_GET['id']);

    // 1. ดึงข้อมูลจาก tb_order พร้อม JOIN เอาชื่อที่อยู่ภาษาไทย
    $sql_order = "SELECT t.*, p.name_th AS p_name, a.name_th AS a_name, d.name_th AS d_name
                  FROM tb_order t
                  LEFT JOIN provinces p ON t.provinces = p.id
                  LEFT JOIN amphures a ON t.amphures = a.id
                  LEFT JOIN districts d ON t.districts = d.id
                  WHERE t.orderID = '$orderID'";
    
    $res_order = mysqli_query($conn, $sql_order);
    $order = mysqli_fetch_array($res_order);

    if($order) {
        $sql_detail = "SELECT od.*, s.book_name FROM order_detail od 
                       JOIN stocks s ON od.book_id = s.book_id 
                       WHERE od.orderID = '$orderID'";
        $res_detail = mysqli_query($conn, $sql_detail);

        $success = true;
        while($item = mysqli_fetch_array($res_detail)) {
            $line_total = $item['orderPrice'] * $item['orderQty'];
            
            // เตรียมข้อมูลชื่อที่อยู่ภาษาไทย
            $p_val = mysqli_real_escape_string($conn, $order['p_name']);
            $a_val = mysqli_real_escape_string($conn, $order['a_name']);
            $d_val = mysqli_real_escape_string($conn, $order['d_name']);
            $cus_name = mysqli_real_escape_string($conn, $order['cus_name']);
            $book_name = mysqli_real_escape_string($conn, $item['book_name']);
            $address = mysqli_real_escape_string($conn, $order['address']);

            // 2. บันทึกลง history (ใช้ชื่อภาษาไทย)
            $sql_hist = "INSERT INTO history (orderID, cus_id, cus_name, sex, provinces, amphures, districts, address, telephone, book_id, book_name, orderPrice, orderQty, total_price, order_status, reg_date) 
                        VALUES ('$orderID', '{$order['cus_id']}', '$cus_name', '{$order['sex']}', '$p_val', '$a_val', '$d_val', '$address', '{$order['telephone']}', '{$item['book_id']}', '$book_name', '{$item['orderPrice']}', '{$item['orderQty']}', '$line_total', '3', NOW())";
            
            if(!mysqli_query($conn, $sql_hist)) $success = false;
        }

        if($success) {
            mysqli_query($conn, "UPDATE tb_order SET order_status = '3' WHERE orderID = '$orderID'");
            echo "<script>alert('อนุมัติเรียบร้อย'); window.location='confirm_order.php';</script>";
        }
    }
}
?>