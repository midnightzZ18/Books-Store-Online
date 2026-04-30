<?php 
include 'condb.php';
session_start();

// --- ส่วนที่ 1: จัดการการบันทึก ---
if (isset($_POST['submit_addbook'])) {
    $bookName    = mysqli_real_escape_string($conn, $_POST['bookName']);
    $authorName  = mysqli_real_escape_string($conn, $_POST['authorName']);
    $price       = $_POST['price'];
    $amount      = $_POST['amount'];
    $type        = $_POST['type']; 
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // จัดการรูปภาพ
    $folder = "img/";
    
    // ตรวจสอบว่ามีโฟลเดอร์ img หรือไม่ ถ้าไม่มีให้สร้าง
    if (!is_dir($folder)) { 
        mkdir($folder, 0777, true); 
    }

    $filename = $_FILES["bookImage"]["name"];
    $tempname = $_FILES["bookImage"]["tmp_name"];

    if ($filename != "" && $tempname != "") {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = "book_" . time() . "." . $ext; // กันชื่อซ้ำ
        move_uploaded_file($tempname, $folder . $new_filename);
    } else {
        $new_filename = "";
    }

    // บันทึกลงฐานข้อมูล - ลำดับคอลัมน์ตาม structure: book_name, amount, typebook_id, price, author_name, image, detail
    $sql = "INSERT INTO stocks (book_name, amount, typebook_id, price, author_name, image, detail) 
            VALUES ('$bookName', '$amount', '$type', '$price', '$authorName', '$new_filename', '$description')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('เพิ่มหนังสือสำเร็จ!'); window.location='admin.php';</script>";
        exit;
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "');</script>";
    }
}

// ดึงประเภทหนังสือมาโชว์ใน Select
$typeResult = mysqli_query($conn, "SELECT * FROM type ORDER BY type_name");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มหนังสือใหม่</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'adminmenu.php'; ?>
<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 800px;">
        <div class="card-body p-4">
            <h2 class="mb-4">เพิ่มหนังสือใหม่</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">ชื่อหนังสือ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="bookName" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ชื่อผู้แต่ง</label>
                        <input type="text" class="form-control" name="authorName">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ราคา <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="price" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">จำนวน <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">ประเภท <span class="text-danger">*</span></label>
                    <select class="form-select" name="type" required>
                        <option value="">-- เลือกประเภท --</option>
                        <?php while ($t = mysqli_fetch_assoc($typeResult)): ?>
                            <option value="<?= $t['typebook_id'] ?>"><?= htmlspecialchars($t['type_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">รายละเอียด</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">รูปภาพหนังสือ</label>
                    <input type="file" class="form-control" name="bookImage" accept="image/*">
                </div>
                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" name="submit_addbook" class="btn btn-primary px-5">เพิ่มหนังสือ</button>
                    <a href="admin.php" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>