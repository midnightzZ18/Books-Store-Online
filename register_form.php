<?php
@include 'condb.php';
session_start();

$error = []; 

if (isset($_POST['submit'])) {
    
    // รับค่าจากฟอร์มและทำความสะอาดข้อมูล
    $name           = trim($_POST['name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $cpassword      = $_POST['cpassword'] ?? '';
    $user_type      = 'user';
    $user_name      = trim($_POST['user_name'] ?? '');
    $phone_number   = trim($_POST['phone_number'] ?? '');
    $sex            = trim($_POST['sex'] ?? '');
    $Ref_prov_id    = trim($_POST['Ref_prov_id'] ?? '');
    $Ref_dist_id    = trim($_POST['Ref_dist_id'] ?? '');
    $Ref_subdist_id = trim($_POST['Ref_subdist_id'] ?? '');
    $zip_code       = trim($_POST['zip_code'] ?? '');
    $address        = trim($_POST['address'] ?? '');

    // ตรวจสอบข้อมูลว่าง
    if ($name == '' || $email == '' || $password == '' || $cpassword == '' || 
        $user_name == '' || $phone_number == '' || $sex == '' || 
        $Ref_prov_id == '' || $Ref_dist_id == '' || $Ref_subdist_id == '' || $address == '') {
        
        $error[] = 'กรุณากรอกข้อมูลให้ครบทุกช่อง!';
    } 
    elseif ($password !== $cpassword) {
        $error[] = 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน';
    } 
    elseif (strlen($password) < 6) {
        $error[] = 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
    }
    elseif (!preg_match('/^0\d{9}$/', preg_replace('/[^0-9]/', '', $phone_number))) {
        $error[] = 'รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง (ต้องมี 10 หลัก)';
    } 
    else {
        // ตรวจสอบ Email หรือ Username ซ้ำ
        $check = mysqli_prepare($conn, "SELECT id FROM user_form WHERE email = ? OR user_name = ? LIMIT 1");
        mysqli_stmt_bind_param($check, 'ss', $email, $user_name);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error[] = 'อีเมลหรือชื่อผู้ใช้นี้มีผู้ใช้งานแล้ว!';
        }
        mysqli_stmt_close($check);

        // บันทึกข้อมูลถ้าไม่มีข้อผิดพลาด
        if (empty($error)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = "INSERT INTO user_form 
                (name, email, password, user_type, user_name, phone_number, sex, address, 
                 Ref_prov_id, Ref_dist_id, Ref_subdist_id, zip_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $insert);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssssssssssss', 
                    $name, $email, $pass_hash, $user_type, $user_name, 
                    $phone_number, $sex, $address, $Ref_prov_id, 
                    $Ref_dist_id, $Ref_subdist_id, $zip_code);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='login_form.php';</script>";
                    exit;
                } else {
                    $error[] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>สมัครสมาชิก - Online Book Store</title>
   <link rel="icon" type="image/png" href="img/logo-web.png">
   <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      body { background-color: #fffafa; font-family: 'Kanit', sans-serif; padding: 40px 0; }
      .register-card {
         background: white;
         padding: 40px;
         border-radius: 20px;
         box-shadow: 0 10px 30px rgba(0,0,0,0.05);
         max-width: 800px;
         margin: auto;
      }
      .section-title {
         color: #ad1457;
         font-weight: bold;
         border-bottom: 2px solid #ffcccc;
         padding-bottom: 10px;
         margin-bottom: 25px;
         font-size: 1.1rem;
      }
      .form-control, .form-select {
         border-radius: 8px;
         border: 1px solid #eee;
         padding: 10px;
      }
      .btn-register {
         background: #ff80ab;
         color: white;
         border-radius: 10px;
         padding: 12px 40px;
         font-weight: bold;
         border: none;
         transition: 0.3s;
      }
      .btn-register:hover { background: #f50057; transform: scale(1.02); }
      .error-msg {
         color: #d81b60;
         background: #fdf2f5;
         padding: 10px;
         border-radius: 8px;
         margin-bottom: 10px;
         display: block;
         font-size: 0.9rem;
         border-left: 4px solid #d81b60;
      }
   </style>
</head>
<body>

<div class="container">
   <div class="register-card">
      <div class="text-center mb-5">
         <h2 class="fw-bold" style="color: #ad1457;">สร้างบัญชีใหม่</h2>
         <p class="text-muted">ร่วมเป็นสมาชิกกับร้านหนังสือของเรา</p>
      </div>

      <?php if (!empty($error)): ?>
         <div class="mb-4">
            <?php foreach ($error as $err): ?>
               <span class="error-msg"><i class="fas fa-exclamation-circle me-2"></i><?php echo $err; ?></span>
            <?php endforeach; ?>
         </div>
      <?php endif; ?>

      <form action="" method="post">
         <div class="row">
            <div class="col-md-6 mb-4">
               <h5 class="section-title"><i class="fas fa-user-circle me-2"></i>ข้อมูลส่วนตัว</h5>
               <div class="mb-3">
                  <label class="small mb-1">ชื่อ-นามสกุล</label>
                  <input type="text" name="name" class="form-control" placeholder="ชื่อจริง นามสกุล" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
               </div>
               <div class="mb-3">
                  <label class="small mb-1">อีเมล</label>
                  <input type="email" name="email" class="form-control" placeholder="example@mail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
               </div>
               <div class="mb-3">
                  <label class="small mb-1">เบอร์โทรศัพท์</label>
                  <input type="text" name="phone_number" class="form-control" placeholder="08x-xxx-xxxx" value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" required>
               </div>
               <div class="mb-3">
                  <label class="small mb-1">เพศ</label>
                  <select name="sex" class="form-select" required>
                     <option value="">- เลือกเพศ -</option>
                     <option value="male" <?php echo (isset($_POST['sex']) && $_POST['sex'] == 'ชาย') ? 'selected' : ''; ?>>ชาย</option>
                     <option value="female" <?php echo (isset($_POST['sex']) && $_POST['sex'] == 'หญิง') ? 'selected' : ''; ?>>หญิง</option>
                  </select>
               </div>
            </div>

            <div class="col-md-6 mb-4">
               <h5 class="section-title"><i class="fas fa-map-marker-alt me-2"></i>ที่อยู่จัดส่ง</h5>
               <div class="mb-3">
                  <label class="small mb-1">บ้านเลขที่/ซอย/ถนน</label>
                  <input type="text" name="address" class="form-control" placeholder="ระบุรายละเอียดที่อยู่" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
               </div>
               <div class="row g-2">
                  <div class="col-6 mb-2">
                     <label class="small mb-1">จังหวัด</label>
                     <select class="form-select" name="Ref_prov_id" id="provinces" required>
                        <option value="" selected disabled>- เลือกจังหวัด -</option>
                        <?php 
                        $query = mysqli_query($conn, "SELECT * FROM provinces");
                        while($value = mysqli_fetch_assoc($query)){ 
                        ?>
                           <option value="<?=$value['id']?>"><?=$value['name_th']?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-6 mb-2">
                     <label class="small mb-1">อำเภอ</label>
                     <select class="form-select" name="Ref_dist_id" id="amphures" required>
                        <option value="">- เลือกอำเภอ -</option>
                     </select>
                  </div>
                  <div class="col-6 mb-2">
                     <label class="small mb-1">ตำบล</label>
                     <select class="form-select" name="Ref_subdist_id" id="districts" required>
                        <option value="">- เลือกตำบล -</option>
                     </select>
                  </div>
                  <div class="col-6 mb-2">
                     <label class="small mb-1">รหัสไปรษณีย์</label>
                     <input type="text" name="zip_code" id="zip_code" class="form-control" readonly placeholder="xxxxx">
                  </div>
               </div>
            </div>

            <div class="col-12 mt-3">
               <h5 class="section-title"><i class="fas fa-key me-2"></i>ข้อมูลบัญชีผู้ใช้</h5>
               <div class="row">
                  <div class="col-md-4 mb-3">
                     <label class="small mb-1">ชื่อผู้ใช้ (Username)</label>
                     <input type="text" name="user_name" class="form-control" placeholder="ตั้งชื่อผู้ใช้" value="<?php echo isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : ''; ?>" required>
                  </div>
                  <div class="col-md-4 mb-3">
                     <label class="small mb-1">รหัสผ่าน</label>
                     <input type="password" name="password" class="form-control" placeholder="อย่างน้อย 6 ตัวอักษร" required>
                  </div>
                  <div class="col-md-4 mb-3">
                     <label class="small mb-1">ยืนยันรหัสผ่านอีกครั้ง</label>
                     <input type="password" name="cpassword" class="form-control" placeholder="กรอกรหัสผ่านเดิม" required>
                  </div>
               </div>
            </div>
         </div>

         <div class="text-center mt-4">
            <button type="submit" name="submit" class="btn btn-register shadow-sm">ลงทะเบียนสมาชิก</button>
            <p class="mt-3 small">เป็นสมาชิกอยู่แล้ว? <a href="login_form.php" class="text-decoration-none" style="color: #ff80ab;">เข้าสู่ระบบที่นี่</a></p>
         </div>
      </form>
   </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php include('script.php'); ?>

</body>
</html>