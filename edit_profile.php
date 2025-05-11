<?php
include 'condb.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];

    // Update user profile in the database
    $user_name = $_SESSION['user_name'];
    $sql = "UPDATE user_form 
            SET name = '$name', email = '$email', phone_number = '$phone_number', sex = '$sex', address = '$address' 
            WHERE user_name = '$user_name'";
    $result = mysqli_query($conn, $sql);

    // Check if update was successful
    if ($result) {
        echo '<script>alert("อัพเดตข้อมูลสำเร็จ"); window.location.href = "user.php";</script>';
    } else {
        echo '<script>alert("มีข้อผิดพลาดเกิดขึ้น กรุณาลองใหม่อีกครั้ง");</script>';
    }
}

// Retrieve user data for pre-filling the form
$user_name = $_SESSION['user_name'];
$sql = "SELECT name, email, phone_number, sex, address 
        FROM user_form 
        WHERE user_name = '$user_name'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
    $sql_provinces = "SELECT * FROM provinces";
    $query = mysqli_query($condb, $sql_provinces);
?>

    <?php include 'menu.php'; ?>
    
    <div class="container">
        <br> 
        <div class="alert alert-success" role="alert" style="text-align:center;">
            <h3>แก้ไขข้อมูลของฉัน</h3>
        </div>

        <div class="col-sm-6 offset-sm-3">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                    <label for="name">ชื่อ - นามสกุล:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-Mail:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $row['email'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">เบอร์โทร:</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" pattern="[0-9]{10}" value="<?= $row['phone_number'] ?>" required>
                    <small id="phoneHelp" class="form-text text-muted">กรุณากรอกเป็นตัวเลข 10 หลัก</small>
                </div>
                <div class="form-group">
                    <label for="sex">เพศ:</label>
                    <select class="form-control" id="sex" name="sex" required>
                        <option value="male" <?php if($row['sex'] == 'male') echo 'selected'; ?>>male</option>
                        <option value="female" <?php if($row['sex'] == 'female') echo 'selected'; ?>>female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">ที่อยู่:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required><?= $row['address'] ?></textarea>
                </div><br>

                <!-- Dropdowns for provinces, amphures, and districts -->
                <div class="form-group">
    <label for="sel1">จังหวัด:</label>
    <select class="form-control" name="Ref_prov_id" id="provinces">
        <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
        <?php foreach ($query as $value) { ?>
            <option value="<?=$value['id']?>"><?=$value['name_th']?></option>
        <?php } ?>
    </select>

    <label for="sel1">อำเภอ:</label>
    <select class="form-control" name="Ref_dist_id" id="amphures">
    </select>

    <label for="sel1">ตำบล:</label>
    <select class="form-control" name="Ref_subdist_id" id="districts">
    </select>

      <label for="sel1">รหัสไปรษณีย์:</label>
       <input type="text" name="zip_code" id="zip_code" class="form-control">
          <br>
        
    </div>

                <button type="submit" class="btn btn-primary">บันทึก</button>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#province').change(function () {
                var province_id = $(this).val();
                $.ajax({
                    url: 'get_amphures.php',
                    method: 'POST',
                    data: {provinceID: province_id},
                    success: function (data) {
                        $('#amphur').html(data);
                    }
                });
            });

            $('#amphur').change(function () {
                var amphur_id = $(this).val();
                $.ajax({
                    url: 'get_districts.php',
                    method: 'POST',
                    data: {amphurID: amphur_id},
                    success: function (data) {
                        $('#district').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php include('script.php');?>