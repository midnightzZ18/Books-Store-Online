<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'condb.php';
session_start();

// ตรวจสอบ Session
if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
    header('location:login_form.php'); 
    exit;
}

$id = $_GET['id'];

// --- ส่วนที่ 1: บันทึกเมื่อกดปุ่ม Submit ---
if (isset($_POST['submit_edit'])) {
    $bookName    = mysqli_real_escape_string($conn, $_POST['bookName']);
    $authorName  = mysqli_real_escape_string($conn, $_POST['authorName']);
    $price       = $_POST['price'];
    $amount      = $_POST['amount'];
    $typebookid  = $_POST['typebookid'];
    $detail      = mysqli_real_escape_string($conn, $_POST['detail']);
    $old_image   = $_POST['old_image'];

    // จัดการรูปภาพใหม่
    $folder = "img/";
    if (!is_dir($folder)) { mkdir($folder, 0777, true); }
    
    if (!empty($_FILES['new_image']['name']) && !empty($_FILES['new_image']['tmp_name'])) {
        $ext = pathinfo($_FILES['new_image']['name'], PATHINFO_EXTENSION);
        $filename = "edit_" . time() . "." . $ext;
        
        if (move_uploaded_file($_FILES['new_image']['tmp_name'], $folder . $filename)) {
            // อัปโหลดสำเร็จ ใช้รูปใหม่
        } else {
            // อัปโหลดไม่สำเร็จ ใช้รูปเก่า
            $filename = $old_image;
        }
    } else {
        $filename = $old_image;
    }

    // แก้ไข: ลำดับคอลัมน์ตามโครงสร้างฐานข้อมูล
    $sql_update = "UPDATE stocks SET 
                    book_name = '$bookName', 
                    amount = '$amount',
                    typebook_id = '$typebookid', 
                    price = '$price', 
                    author_name = '$authorName', 
                    image = '$filename',
                    detail = '$detail'
                   WHERE book_id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ'); window.location='admin.php';</script>";
        exit;
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "');</script>";
    }
}

// --- ส่วนที่ 2: ดึงข้อมูลเดิมมาโชว์ ---
$sql = "SELECT * FROM stocks WHERE book_id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if (!$row) { die("ไม่พบข้อมูลหนังสือรหัส: " . $id); }

// ดึงรายการประเภทหนังสือสำหรับ dropdown
$typeResult = mysqli_query($conn, "SELECT * FROM type ORDER BY type_name");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไข: <?= htmlspecialchars($row['book_name']) ?></title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'adminmenu.php'; ?>
    <div class="container mt-5 mb-5">
        <div class="card p-4 shadow-sm mx-auto" style="max-width: 700px;">
            <h3 class="text-primary mb-4">แก้ไขหนังสือ</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="old_image" value="<?= htmlspecialchars($row['image']) ?>">
                
                <div class="mb-3">
                    <label>ชื่อหนังสือ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="bookName" value="<?= htmlspecialchars($row['book_name']) ?>" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>ชื่อผู้แต่ง</label>
                        <input type="text" class="form-control" name="authorName" value="<?= htmlspecialchars($row['author_name']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label>ราคา <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="price" value="<?= $row['price'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>จำนวน <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="amount" value="<?= $row['amount'] ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label>ประเภท <span class="text-danger">*</span></label>
                    <select class="form-select" name="typebookid" required>
                        <option value="">-- เลือกประเภท --</option>
                        <?php while ($t = mysqli_fetch_assoc($typeResult)): ?>
                            <option value="<?= $t['typebook_id'] ?>" <?= ($t['typebook_id'] == $row['typebook_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['type_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label>รายละเอียด</label>
                    <textarea class="form-control" name="detail" rows="4"><?= htmlspecialchars($row['detail']) ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label>รูปภาพเดิม</label><br>
                    <?php if (!empty($row['image'])): ?>
                        <img src="img/<?= htmlspecialchars($row['image']) ?>" width="150" class="mb-2 border"><br>
                    <?php else: ?>
                        <span class="text-muted">ไม่มีรูปภาพ</span><br>
                    <?php endif; ?>
                    <label class="mt-2">อัปโหลดรูปใหม่ (ถ้าต้องการเปลี่ยน)</label>
                    <input type="file" class="form-control" name="new_image" accept="image/*">
                </div>
                
                <button type="submit" name="submit_edit" class="btn btn-primary px-5">บันทึกแก้ไข</button>
                <a href="admin.php" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</body>
</html>