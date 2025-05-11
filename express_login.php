<?php
@include 'condb.php';

session_start();

if (isset($_POST['submit'])) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $pass = md5($_POST['password']); // Consider using stronger hashing algorithm
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';

    $select = "SELECT * FROM delivery_form WHERE user_name = '$user_name' && password = '$pass' ";

    $result = mysqli_query($conn, $select);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);

        if (isset($row['user_type'])) {
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                header('Location: admin.php');
                exit; // Stop further execution
            } elseif ($row['user_type'] == 'delivery') {
                $_SESSION['user_name'] = $row['user_name'];
                header('Location: express.php');
                exit; // Stop further execution
            }
        } else {
            $error[] = 'User type information not available!';
        }

    } else {
        $error[] = 'Incorrect username or password!'; // Provide a specific error message
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
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
   <style>
      *{
         font-family: 'Poppins', sans-serif;
         margin:0; padding:0;
         box-sizing: border-box;
         outline: none; border:none;
         text-decoration: none;
         text-align: center; 
      }

      .container{
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         padding:20px;
         padding-bottom: 60px;
      }

      .container .content{
         text-align: center;
      }

      .container .content h3{
         font-size: 30px;
         color:#333;
      }

      .container .content h3 span{
         background: crimson;
         color:#fff;
         border-radius: 5px;
         padding:0 15px;
      }

      .container .content h1{
         font-size: 50px;
         color:#333;
      }

      .container .content h1 span{
         color:crimson;
      }

      .container .content p{
         font-size: 25px;
         margin-bottom: 20px;
      }

      .container .content .btn{
         display: inline-block;
         padding:10px 30px;
         font-size: 20px;
         background: #333;
         color:#fff;
         margin:0 5px;
         text-transform: capitalize;
      }

      .container .content .btn:hover{
         background: crimson;
      }

      .form-container{
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         padding:20px;
         padding-bottom: 60px;
         background-image:url('express.jpg');
      }

      .form-container form h3{
         font-size: 46px;
         text-transform: uppercase;
         margin-bottom: 10px;
         color:#ffffff;
         text-align: center; 
      }

      .form-container form input,
      .form-container form select{
         width: 50%; /* Adjust the width percentage as needed */
         padding: 10px 15px;
         font-size: 17px;
         margin: 8px 0;
         background: #eee;
         border: 2px solid black; /* Add a black border */
         border-radius: 500px;
         text-align: center;
      }

      .form-container form select option{
         background: #fff;
      }

      .form-container form .form-btn{
         background: #CCFFFF;
         color:black;
         text-transform: capitalize;
         font-size: 25px;
         cursor: pointer;
      }

      .form-container form .form-btn:hover{ 
         background: #99FF66;
         color:black; 
      }

      .form-container form p{
         margin-top: 10px;
         font-size: 20px;
         color:#fff;
         text-align: center; 
      }

      .form-container form p a{
         color:crimson;
      }

      .form-container form .error-msg{
         margin:10px 0;
         display: block;
         background: crimson;
         color:#fff;
         border-radius: 5px;
         font-size: 20px;
         padding:10px;
      }
   </style>
</head>
<body>
   <div class="form-container">
      <form action="" method="post">
         <h3>ระบบขนส่ง</h3>
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
         <p>ยังไม่ได้สมัครสมาชิก? <a href="express_register.php">สมัคร</a></p>
      </form>
   </div>
</body>
</html>
