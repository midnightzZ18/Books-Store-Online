<?php
    @include 'condb.php';
    session_start();
    if(!isset($_SESSION['admin_name'])){
    header('location:login_form.php');
    }
?>


<?php include 'condb.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>data_user</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css"  rel="stylesheet" >
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js" ></script>
    <style>
        body {
            background-image:url('1.png');/* Set your desired background image */
        }
    </style>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><br>
        <div class="alert alert-success" role="alert" style="text-align:center;">
            <h1>ข้อมูล User</h1>
    </div>
        <?php
    

        // SQL query to fetch user data
        $sql = "SELECT uf.name, uf.email, uf.sex, uf.address, uf.phone_number, uf.id, 
        p.name_th AS province_name, a.name_th AS amphur_name, d.name_th AS district_name
        FROM user_form uf
        JOIN provinces p ON uf.Ref_prov_id = p.id
        JOIN amphures a ON uf.Ref_dist_id = a.id
        JOIN districts d ON uf.Ref_subdist_id = d.id";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<tr><th>รหัสลูกค้า</th><th>ชื่อ-นามสกุล</th><th>เพศ</th><th>E-mail</th><th>เบอร์โทรศัพท์</th><th>ที่อยู่</th><th>ตำบล</th><th>อำเภอ</th><th>จังหวัด</th></tr>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["sex"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["phone_number"]."</td>";
                echo "<td>".$row["address"]."</td>";
                echo "<td>".$row["district_name"]."</td>";
                echo "<td>".$row["amphur_name"]."</td>";
                echo "<td>".$row["province_name"]."</td>";
                echo "</tr>";

            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
</body>
</html>
