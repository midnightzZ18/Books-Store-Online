<?php
@include 'condb.php';

if(isset($_POST['submit'])){
    // Prepare the SQL statement using placeholders
    $insert = "INSERT INTO delivery_form (name, email, password, user_type, user_name, phone_number, sex, address, Ref_prov_id, Ref_dist_id, Ref_subdist_id, zip_code) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Initialize prepared statement
    $stmt = mysqli_prepare($conn, $insert);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssssssssssss", $name, $email, $pass, $user_type, $user_name, $phone_number, $sex, $address, $Ref_prov_id, $Ref_dist_id, $Ref_subdist_id, $zip_code);

    // Set parameter values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']); // It's recommended to use a more secure hashing algorithm
    $user_type = "delivery";
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $Ref_prov_id = mysqli_real_escape_string($conn, $_POST['Ref_prov_id']); // Assuming you have added this field in your form
    $Ref_dist_id = mysqli_real_escape_string($conn, $_POST['Ref_dist_id']); // Assuming you have added this field in your form
    $Ref_subdist_id = mysqli_real_escape_string($conn, $_POST['Ref_subdist_id']); // Assuming you have added this field in your form
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']); // Assuming you have added this field in your form
    $address = mysqli_real_escape_string($conn, $_POST['address']); 
      // Check for existing user
      $select = "SELECT * FROM delivery_form WHERE email = '$email' ";
      $select = "SELECT * FROM delivery_form WHERE user_name = '$user_name ' ";
      $result = mysqli_query($conn, $select);
  
      // Check for errors
      if(empty($name) || empty($email) || empty($pass) || empty($user_name) || empty($phone_number) || empty($sex)|| empty( $Ref_prov_id )|| empty( $Ref_dist_id )|| empty($Ref_subdist_id )|| empty($address)) {
          $error[] = 'กรุณากรอกข้อมูลให้ครบ!';
      } elseif(!preg_match('/^0\d{9}$/', $phone_number)) {
          $error[] = 'รูปแบบเบอร์โทรไม่ถูกต้อง!';
      } elseif(mysqli_num_rows($result) > 0){
          $error[] = 'Username หรือ Email มีผู้ใช้แล้ว!';
      } else {
          // Execute the prepared statement
          mysqli_stmt_execute($stmt);
  
          // Check if the query was executed successfully
          if(mysqli_stmt_affected_rows($stmt) > 0) {
              // Redirect to login page after successful registration
              header('location: express_login.php');
          } else {
              // Handle the case where the registration failed
              // This could be due to various reasons such as database error
              echo "Registration failed. Please try again.";
          }
      }
  
      // Close the prepared statement
      mysqli_stmt_close($stmt);
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Express</title>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php
    $sql_provinces = "SELECT * FROM provinces";
    $query = mysqli_query($condb, $sql_provinces);
?>


<div class="form-container1">

   <form action="" method="post">
      <h3>สมัครสมาชิคส่งของ</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
        
      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">ชื่อ - นามสกุล</span>
         <input type="text" name="name" required placeholder="กรุณากรอกชื่อ - นามสกุล">
      </div>

      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">เพศ</span>
         <input type="radio" name="sex" value="male" id="male" style="margin-right: -40px;"> Male 
         <input type="radio" name="sex" value="female" id="female" style="margin-right: -40px;"> Female
      </div>

      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">กรอก Email</span>
         <input type="email" name="email" required placeholder="กรุณากรอก Email"> 
      </div>

      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">เบอร์โทรศัพท์</span>
         <input type="tel" name="phone_number" required placeholder="xxx-xxx-xxxx"> 
      </div>
      
      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">ที่อยู่ในการจัดส่ง</span>
         <input type="text" name="address" required placeholder="บ้านเลขที่,ซอย,ตรอก">
      </div>
      
      
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
      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">ชื่อผู้ใช้ของคุณ</span>
         <input type="text" name="user_name" required placeholder="กรอก Username"> 
      </div>

      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">รหัสของคุณ</span>
         <input type="password" name="password" required placeholder="กรอก Password"> 
      </div>

      <div style="display: flex; align-items: center;">
         <span style="min-width: 150px;">ยืนยันรหัสของคุณ</span>
         <input type="password" name="cpassword" required placeholder="ยืนยัน Password"> 
      </div>
         
      <input type="submit" name="submit" value="ยืนยันการสมัครสมาชิก" class="form-btn">
      <p>เป็นสมาชิกอยู่แล้ว <a href="express_login.php">เข้าสู่ระบบ</a></p>
   </form>

</div>

</body>
</html>
<?php include('script.php');?>