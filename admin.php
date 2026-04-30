<?php
    @include 'condb.php';
    session_start();

    // ตรวจสอบสิทธิ์ Admin
    if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
        header('location:login_form.php');
        exit;
    }

    $limit = 16; // 4 แถว × 4 คอลัมน์
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    // นับจำนวนรวมสำหรับ Pagination (ใช้กับ keyword ด้วย)
    $kw = "";
    if (isset($_POST['keyword']) && !empty($_POST['keyword'])) {
        $kw = mysqli_real_escape_string($conn, $_POST['keyword']);
        $res_count = $conn->query("SELECT COUNT(*) as total FROM stocks WHERE book_name LIKE '%$kw%' OR author_name LIKE '%$kw%'");
    } else {
        $res_count = $conn->query("SELECT COUNT(*) as total FROM stocks");
    }
    $total_rows  = $res_count->fetch_assoc()['total'];
    $total_pages = max(1, ceil($total_rows / $limit));
    if ($page > $total_pages) $page = $total_pages;
    $offset = ($page - 1) * $limit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store - Admin</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        body { background-color: #f8f9fa; }

        /* --- ปุ่มแก้ไข (Dark Blue) --- */
        .btn-outline-dark-blue {
            color: white !important;
            background-color: #4db6ac;
            border: 1px solid #4db6ac;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-outline-dark-blue:hover { 
            background-color: transparent !important; 
            color: #4db6ac !important; 
        }

        /* --- ปุ่มรายละเอียด (Info Blue) --- */
        .btn-outline-info-blue {
            color: white !important;
            background-color: #00BFFF;
            border: 1px solid #00BFFF;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-outline-info-blue:hover { 
            background-color: transparent !important; 
            color: #00BFFF !important; 
        }

        /* --- ปุ่มลบ (Danger Red) --- */
        .btn-outline-danger-red {
            color: white !important;
            background-color: #e84e40;
            border: 1px solid #e84e40;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-outline-danger-red:hover { 
            background-color: transparent !important; 
            color: #e84e40 !important; 
        }

        .card { 
            border: 1px solid #eee !important; 
            border-radius: 12px; 
            transition: transform 0.2s, box-shadow 0.2s; 
        }
        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; 
        }
        .admin-actions .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
        }

        /* --- Pagination --- */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            color: #4db6ac;
            border-color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #4db6ac;
            border-color: #4db6ac;
            color: white;
        }
        .pagination .page-link:hover {
            background-color: #e0f2f1;
            color: #00897b;
        }
    </style>
</head>
<body>

    <?php include 'adminmenu.php'; ?>

    <div class="container my-4">
        <div class="text-center admin-actions mb-4">
            <a href="addbook.php" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> เพิ่มสินค้า
            </a>
            <a href="addcoupon.php" class="btn btn-success ms-2">
                <i class="fas fa-ticket-alt"></i> เพิ่มคูปอง
            </a>
            <a href="addpromotion.php" class="btn btn-warning ms-2 text-dark">
                <i class="fas fa-ad"></i> เพิ่มโปรโมชั่น
            </a>
        </div>

        <hr>

        <?php 
            // --- Query พร้อม LIMIT / OFFSET สำหรับ Pagination ---
            if ($kw != "") {
                $sql = "SELECT * FROM stocks 
                        WHERE book_name LIKE '%$kw%' 
                        OR author_name LIKE '%$kw%' 
                        ORDER BY book_id DESC
                        LIMIT $limit OFFSET $offset";
                echo "<h4 class='mb-4'>ผลการค้นหาสำหรับ: <span class='text-primary'>'$kw'</span>
                      <small class='text-muted fs-6'>($total_rows รายการ)</small></h4>";
            } else {
                $sql = "SELECT * FROM stocks ORDER BY book_id DESC LIMIT $limit OFFSET $offset";
                echo "<h4 class='mb-4 text-secondary'>หนังสือทั้งหมด
                      <small class='text-muted fs-6'>($total_rows รายการ)</small></h4>";
            }
            
            $result = mysqli_query($conn, $sql);
            $count  = mysqli_num_rows($result);
        ?>

        <div class="row">
            <?php 
            if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4"> 
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="text-center p-3">
                            <img src="img/<?=$row['image']?>" class="shadow-sm" 
                                 style="width: 140px; height: 200px; object-fit: cover; border-radius: 5px;">
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold text-dark text-truncate mb-1" title="<?=$row['book_name']?>">
                                <?=$row['book_name']?>
                            </h6>
                            <p class="small text-muted mb-3">ผู้แต่ง: <?=$row['author_name']?></p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-danger mb-0 fw-bold">฿<?=number_format($row['price'])?></h5>
                                    <span class="badge bg-light text-dark border small">ID: <?=$row['book_id']?></span>
                                </div>

                                <div class="row g-2"> 
                                    <div class="col-6">
                                        <a href="admin_edit.php?id=<?=$row['book_id']?>" class="btn btn-outline-dark-blue w-100 py-2 fw-bold small">
                                            แก้ไข
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="admin_detailbook.php?id=<?=$row['book_id']?>" class="btn btn-outline-info-blue w-100 py-2 fw-bold small">
                                            รายละเอียด
                                        </a>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <a href="delete.php?id=<?=$row['book_id']?>" class="btn btn-outline-danger-red w-100 py-2 fw-bold small" 
                                           onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหนังสือเล่มนี้?')">
                                            ลบสินค้า
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<div class='col-12 text-center my-5'>";
                echo "<h3 class='text-muted'>❌ ไม่พบข้อมูลหนังสือที่ตรงกับคำค้นหา</h3>";
                echo "<a href='admin.php' class='btn btn-secondary mt-3'>แสดงหนังสือทั้งหมด</a>";
                echo "</div>";
            }
            mysqli_close($conn);
            ?>
        </div>

        <!-- Pagination -->
        <?php renderPagination($page, $total_pages, $kw); ?>

    </div>

</body>
</html>

<?php
function renderPagination($page, $total_pages, $kw = "") {
    if ($total_pages <= 1) return;

    // เก็บ keyword ไว้ใน URL ถ้ามี (ใช้ GET แทน POST เพื่อให้ pagination ทำงานได้)
    $kw_param = $kw !== "" ? "&kw=" . urlencode($kw) : "";

    echo '<nav class="mt-4 mb-5" aria-label="Page navigation">';
    echo '<ul class="pagination justify-content-center flex-wrap">';

    // ปุ่มก่อนหน้า
    $prev = $page - 1;
    $disabled_prev = $page <= 1 ? 'disabled' : '';
    echo "<li class='page-item $disabled_prev'>
            <a class='page-link' href='?page=$prev$kw_param'>
                <i class='fas fa-chevron-left'></i> ก่อนหน้า
            </a>
          </li>";

    // แสดงเลขหน้า (จำกัดไม่เกิน 7 ปุ่ม)
    $start = max(1, $page - 3);
    $end   = min($total_pages, $page + 3);

    if ($start > 1) {
        echo "<li class='page-item'><a class='page-link' href='?page=1$kw_param'>1</a></li>";
        if ($start > 2) echo "<li class='page-item disabled'><span class='page-link'>…</span></li>";
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = ($page == $i) ? 'active' : '';
        echo "<li class='page-item $active'><a class='page-link' href='?page=$i$kw_param'>$i</a></li>";
    }

    if ($end < $total_pages) {
        if ($end < $total_pages - 1) echo "<li class='page-item disabled'><span class='page-link'>…</span></li>";
        echo "<li class='page-item'><a class='page-link' href='?page=$total_pages$kw_param'>$total_pages</a></li>";
    }

    // ปุ่มถัดไป
    $next = $page + 1;
    $disabled_next = $page >= $total_pages ? 'disabled' : '';
    echo "<li class='page-item $disabled_next'>
            <a class='page-link' href='?page=$next$kw_param'>
                ถัดไป <i class='fas fa-chevron-right'></i>
            </a>
          </li>";

    echo '</ul></nav>';
}
?>