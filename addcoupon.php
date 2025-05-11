<?php
    include 'condb.php';
    session_start();
    if (!isset($_SESSION['admin_name'])) {
        header('location:login_form.php');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add_coupon'])) {
            // รับข้อมูลจากฟอร์ม
            $coupon_code = $_POST['coupon_code'];
            $discount = $_POST['discount'];
            $status = $_POST['status']; // เพิ่มการรับค่าสถานะของคูปอง

            // แปลงค่า status เป็น "Active" หรือ "Inactive" ตามที่เลือกจากฟอร์ม
            if ($status == "1") {
                $status_value = 1;

                // คำนวณคูปองเฉพาะเมื่อสถานะเป็น Active
                // ใส่โค้ดคำนวณคูปองที่นี่
                // เช่น $discounted_price = $price - $discount;
                // แล้วบันทึกลงในฐานข้อมูลด้วยค่า $discounted_price
            } else {
                echo "<script>alert('ไม่ทำการคำนวณคูปองเมื่อสถานะเป็น Inactive');</script>";
            }

            // เพิ่มรายการคูปองในฐานข้อมูล
            if ($status_value == 1) {
                $sql = "INSERT INTO coupons (coupon_code, discount, status) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'sii', $coupon_code, $discount, $status_value); // ใช้ค่า status_value ที่แปลงแล้ว

                // ทำการ execute คำสั่ง SQL
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('เพิ่มคูปองเรียบร้อยแล้ว');</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มคูปอง');</script>";
                }

                // ปิด statement
                mysqli_stmt_close($stmt);
            }
        } elseif (isset($_POST['delete_coupon'])) {
            // รับค่า coupon_id ที่ต้องการลบ
            $coupon_id = $_POST['coupon_id'];

            // สร้างคำสั่ง SQL สำหรับลบคูปอง
            $sql = "DELETE FROM coupons WHERE coupon_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $coupon_id);

            // ทำการ execute คำสั่ง SQL
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('ลบคูปองเรียบร้อยแล้ว');</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการลบคูปอง');</script>";
            }

            // ปิด statement
            mysqli_stmt_close($stmt);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'adminmenu.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Existing Coupons</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Coupon ID</th>
                        <th>Coupon Code</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM coupons";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['coupon_id'] . "</td>";
                                echo "<td>" . $row['coupon_code'] . "</td>";
                                echo "<td>" . $row['discount'] . "</td>";
                                echo "<td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>"; // แสดงสถานะของคูปอง
                                echo "<td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='coupon_id' value='" . $row['coupon_id'] . "'>
                                        <button type='submit' class='btn btn-danger' name='delete_coupon'>Delete</button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No coupons found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Add Coupon</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="coupon_code">Coupon Code:</label>
                    <input type="text" class="form-control" id="coupon_code" name="coupon_code">
                </div>
                <div class="form-group">
                    <label for="discount">Discount:</label>
                    <input type="text" class="form-control" id="discount" name="discount">
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div><br>
                <button type="submit" class="btn btn-primary" name="add_coupon">Add Coupon</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
