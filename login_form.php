<?php

@include 'condb.php';

session_start();

if (isset($_POST['submit'])) {

    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $pass = md5($_POST['password']);
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : ''; // Add this line to prevent the warning

    $select = "SELECT * FROM user_form WHERE user_name = '$user_name' && password = '$pass' ";


    $result = mysqli_query($conn, $select);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_array($result);

        // Add a check for the 'user_type' key to prevent the warning
        if (isset($row['user_type'])) {
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                header('location:admin.php');
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['user_name'];
                header('location:shop.php');
            } elseif ($row['user_type'] == 'ceo') {
                $_SESSION['admin_name'] = $row['name'];
                header('location:dashboard.php');
            } elseif ($row['user_type'] == 'delivery') {
                $_SESSION['admin_name'] = $row['user_name'];
                header('location:express.php');
            }
        } else {
            // Handle the case when 'user_type' key is not set in the array
            $error[] = 'User type information not available!';
        }

    } else {
        $error[] = 'user_name or password!';
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login Form</title>
   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css?v=9999">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-GLhlTQ8iEWAyH7t8u+J/+6UwL5KOYbAhkbrGIPIf34Mz9gHx2C4D/hRTt8+pR6L4" crossorigin="anonymous">

</head>
<body>
<div class="form-container">
   <form action="" method="post">
      
      <h3>เข้าสู่ระบบ</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $error) {
            echo '<span class="error-msg">' . $error . '</span>';
         };
      };
      ?>
        <div style="position: relative;">
            <input type="text" name="user_name" required placeholder="Username" style="padding-left: 40px;"> 
            <img src="img/account.png" width="40px" height="40" style="position: absolute; top: 100%; transform: translateY(-297%); left: 140px;">
            <input type="password" name="password" required placeholder="Password" style="padding-left: 40px;"> 
            <img src="img/padlock.png" width="40px" height="40" style="position: absolute; top: 100%; transform: translateY(-132%); left: 140px;">
        </div>
        <input type="submit" name="submit" value="ยืนยัน" class="form-btn">
        <p>ยังไม่ได้สมัครสมาชิก? <a href="register_form.php">สมัคร</a></p>
   </form>
</div>

</body>
</html>
