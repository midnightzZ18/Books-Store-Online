<?php include 'condb.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('1.png'); /* Set your desired background image */
        }
        .rounded-img {
            border-radius: 10px; /* Rounded corners */
        }
    </style>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br>
        <div class="alert alert-success" role="alert" style="text-align:center;">
            <h1>คลังสินค้า</h1>
        </div>
        <?php
        $sql = "SELECT s.book_id, s.image, s.book_Name, t.type_name, s.author_Name, s.price, s.amount 
                FROM stocks s
                INNER JOIN type t ON s.typebook_id = t.typebook_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<tr><th>ลำดับ</th><th></th><th>ชื่อหนังสือ</th><th>ประเภทหนังสือ</th><th>ชื่อผู้แต่ง</th><th>ราคา</th><th>คงเหลือ</th><th>แก้ไขข้อมูล</th><th>ลบข้อมูล</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["book_id"]."</td>";
                // แสดงรูปภาพ
                echo "<td><img src='img/".$row['image']."' class='rounded-img img-fluid' style='width: 80px; height: 100px;'></td>";
                echo "<td>".$row["book_Name"]."</td>";
                echo "<td>".$row["type_name"]."</td>";
                echo "<td>".$row["author_Name"]."</td>";
                echo "<td>".$row["price"]."</td>";
                echo "<td>".$row["amount"]."</td>";
                // Add edit button with link to edit.php
                echo "<td><a class='btn btn-primary' href='edit.php?id=".$row["book_id"]."'>แก้ไข</a></td>";
                // Add delete button with link to delete.php
                echo "<td><a class='btn btn-danger' href='delete.php?id=".$row["book_id"]."'>ลบ</a></td>";
                echo "</tr>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
