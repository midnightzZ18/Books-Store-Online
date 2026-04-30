<?php
session_start();
@include 'condb.php';
$error = '';

if (isset($_POST['submit'])) {
    $user_name = trim($_POST['user_name'] ?? '');
    $password  = $_POST['password'] ?? '';

    if (empty($user_name) || empty($password)) {
        $error = 'กรุณากรอก Username และ Password';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM user_form WHERE user_name = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $user_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row) {
            $db_password = $row['password'];
            $login_success = false;
            if (password_verify($password, $db_password) || md5($password) === $db_password) {
                $login_success = true;
            }

            if ($login_success) {
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_type'] = $row['user_type'];
                if ($row['user_type'] == 'admin') {
                    header('location:admin.php');
                } else {
                    header('location:shop.php');
                }
            } else {
                $error = 'Username หรือ Password ไม่ถูกต้อง!';
            }
        } else {
            $error = 'ไม่พบผู้ใช้งานนี้ในระบบ!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Login - Online Book Store</title>
   <link rel="icon" type="image/png" href="img/logo-web.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      body {
         background: linear-gradient(135deg, #FFCCCC 0%, #ffe4e1 100%);
         height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         font-family: 'Kanit', sans-serif;
      }
      .form-container {
         background: #fff;
         padding: 30px;
         border-radius: 20px; /* มนกลม */
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
         width: 100%;
         max-width: 400px;
      }
      .form-container h3 { text-align: center; color: #ad1457; margin-bottom: 20px; }

      .text-muted {
            text-align: center;
            color: #ff80ab;
            margin-bottom: 30px;
      }
      .text-label {
        display: block;
        text-align: left;
        color: #666; /* ปรับจาก #f เพื่อให้อ่านออก */
        font-size: 0.9rem;
        margin-bottom: 5px;
        margin-left: 15px; /* ขยับให้ตรงกับช่องกรอกที่มนกลม */
    }
      /* ปรับช่องกรอกให้มนกลม */
      .form-container input[type="text"],
      .form-container input[type="password"] {
         width: 100%;
         padding: 12px 20px;
         margin: 10px 0;
         border: 1px solid #ddd;
         border-radius: 30px; /* ทำให้มนกลมแบบ Pill shape */
         box-sizing: border-box;
      }

      /* ปรับปุ่มให้มนกลม */
      .form-btn {
         background: #ff80ab;
         color: white;
         border: none;
         padding: 12px;
         width: 100%;
         border-radius: 30px; /* ทำให้มนกลม */
         font-weight: bold;
         cursor: pointer;
         transition: 0.3s;
         margin-top: 10px;
      }
      .form-btn:hover { background: #f50057; }
      .error-msg { color: red; display: block; text-align: center; margin-bottom: 10px; }
   </style>
</head>
<body>

<div class="form-container">
   <form action="" method="post">
    <div class="login-card">
   <div class="text-center mb-4">
      <h3 class="fw-bold" style="color: #ad1457;">🌸 BOOK STORE</h3>
      <p class="text-muted">ยินดีต้อนรับ เข้าสู่ระบบ</p>
   </div>
      <?php if(!empty($error)) echo '<span class="error-msg">'.$error.'</span>'; ?>
      <label class="text-label">Username</label>
      <input type="text" name="user_name" required placeholder="กรอกชื่อผู้ใช้">
      <label class="text-label">Password</label>
      <input type="password" name="password" required placeholder="กรอกรหัสผ่าน">
      
      <input type="submit" name="submit" value="เข้าสู่ระบบ" class="form-btn">
      <p class="text-center mt-3" style="text-align:center;">ยังไม่มีบัญชี? <a href="register_form.php" style="color:#ff80ab;">สมัครสมาชิก</a></p>
   </form>
</div>

</body>
</html>