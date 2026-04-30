<?php 
include 'condb.php'; 
include 'pagination_function.php';
// ระบบแบ่งหน้า
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$res_count = $conn->query("SELECT COUNT(*) as total FROM stocks");
$total_pages = ceil($res_count->fetch_assoc()['total'] / $limit);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><div class="alert alert-success text-center"><h1>คลังสินค้า</h1></div>
        <div class="table-responsive">
        <?php
        $sql = "SELECT s.book_id, s.image, s.book_Name, t.type_name, s.author_Name, s.price, s.amount 
                FROM stocks s INNER JOIN type t ON s.typebook_id = t.typebook_id 
                LIMIT $limit OFFSET $offset";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>
            <thead class='table-dark'><tr><th>ID</th><th>รูป</th><th>ชื่อหนังสือ</th><th>ประเภท</th><th>ผู้แต่ง</th><th>ราคา</th><th>คงเหลือ</th><th>แก้ไข</th><th>ลบ</th></tr></thead>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>{$row['book_id']}</td>
                <td><img src='img/{$row['image']}' style='width:50px;'></td>
                <td>{$row['book_Name']}</td>
                <td>{$row['type_name']}</td>
                <td>{$row['author_Name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['amount']}</td>
                <td><a class='btn btn-primary btn-sm' href='admin_edit.php?id={$row['book_id']}'>แก้ไข</a></td>
                <td><a class='btn btn-danger btn-sm' href='delete.php?id={$row['book_id']}'>ลบ</a></td>
                </tr>";
            }
            echo "</table>";
            // ปุ่มเปลี่ยนหน้า (ใช้ฟังก์ชันเดิมเหมือนด้านบน)
            renderPagination($page, $total_pages);
        }
        ?>
        </div>
    </div>
</body>
</html>