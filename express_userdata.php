<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exdata</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head> 
<body>
    <?php
    include 'expressmenu.php'; // Include menu conditionally based on login status
    ?>
    
    <div class="container">
        <br> 
        <div class="alert alert-success" role="alert" style="text-align:center;">
            <h3>ข้อมูลของฉัน</h3>
        </div>
        <?php 
        $user_name = $_SESSION['user_name'];
        $sql = "SELECT uf.name, uf.email, uf.sex, uf.address, uf.phone_number, uf.id, 
                       p.name_th AS province_name, a.name_th AS amphur_name, d.name_th AS district_name
                FROM delivery_form uf
                JOIN provinces p ON uf.Ref_prov_id = p.id
                JOIN amphures a ON uf.Ref_dist_id = a.id
                JOIN districts d ON uf.Ref_subdist_id = d.id
                WHERE uf.user_name = '$user_name'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            ?> 
            <div class="col-sm-4"> 
                <div style="border: 1px solid black; padding: 10px;">
                    <b> ชื่อ - นามสกุล : </b> &emsp; <?= htmlspecialchars($row['name']) ?>                 
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> E - Mail : </b> &emsp; <?= htmlspecialchars($row['email']) ?> 
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> เบอร์โทร : </b> &emsp; <?= htmlspecialchars($row['phone_number']) ?> 
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> เพศ : </b> &emsp; <?= htmlspecialchars($row['sex']) ?>
                </div> <br>
                <div style="border: 1px solid black; padding: 10px;">
                    <b> ที่อยู่ : </b> &emsp; <?= htmlspecialchars($row['address'])  ?>
                    <br> ตำบล : </b> &emsp; <?= htmlspecialchars($row['district_name']) ?>
                    <br> อำเภอ: </b> &emsp; <?= htmlspecialchars($row['amphur_name']) ?>
                    <br> จังหวัด : </b> &emsp; <?= htmlspecialchars($row['province_name']) ?>
                </div>
            </div>



        <?php
        }
        mysqli_close($conn);            
        ?>
    </div>
</body>
</html>
