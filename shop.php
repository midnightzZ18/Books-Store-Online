<?php
    include 'condb.php';
    session_start();

    // ตรวจสอบการ Login
    if (!isset($_SESSION['user_name'])) {
        header('location:login_form.php');
        exit;
    }

    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
        header('location:admin.php');
        exit;
    }

    $limit = 16; // 4 แถว × 4 คอลัมน์
    $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    // รองรับ keyword ผ่าน GET (เพื่อให้ pagination เก็บ keyword ไว้ได้)
    $kw = "";
    if (isset($_GET['keyword']) && $_GET['keyword'] !== "") {
        $kw = mysqli_real_escape_string($conn, $_GET['keyword']);
    } elseif (isset($_POST['keyword']) && $_POST['keyword'] !== "") {
        $kw = mysqli_real_escape_string($conn, $_POST['keyword']);
    }

    // นับจำนวนรวม
    if ($kw !== "") {
        $res_count  = mysqli_query($conn, "SELECT COUNT(*) as total FROM stocks WHERE book_name LIKE '%$kw%' OR author_name LIKE '%$kw%'");
    } else {
        $res_count  = mysqli_query($conn, "SELECT COUNT(*) as total FROM stocks");
    }
    $total_rows  = mysqli_fetch_assoc($res_count)['total'];
    $total_pages = max(1, ceil($total_rows / $limit));
    if ($page > $total_pages) $page = $total_pages;
    $offset = ($page - 1) * $limit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        body { background-color: #fffafa; font-family: 'Kanit', sans-serif; }

        .slideshow-container {
            max-width: 60%;
            margin: 20px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        .slideshow img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .promotion-banner {
            background: linear-gradient(45deg, #FFCCCC, #FFB6C1);
            border-radius: 15px;
            padding: 20px;
            margin: 20px auto;
            max-width: 80%;
            color: #ad1457;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .book-card {
            border: 1px solid #eee !important;
            border-radius: 15px;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(255, 182, 193, 0.2) !important;
        }

        .book-img-container {
            width: 100%;
            height: 350px;
            overflow: hidden;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .book-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: 0.3s;
        }
        .book-card:hover .book-img { transform: scale(1.05); }

        .btn-outline-detail {
            color: #5bc0de;
            border: 1.5px solid #5bc0de;
            border-radius: 8px;
        }
        .btn-outline-detail:hover { background-color: #5bc0de; color: white !important; }

        .btn-outline-cart {
            color: #FF80AB;
            border: 1.5px solid #FF80AB;
            border-radius: 8px;
        }
        .btn-outline-cart:hover { background-color: #FF80AB; color: white !important; }

        .price-text { color: #d81b60; font-size: 1.4rem; font-weight: bold; }

        /* --- Pagination --- */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            color: #d81b60;
            border-color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #FF80AB;
            border-color: #FF80AB;
            color: white;
        }
        .pagination .page-link:hover {
            background-color: #ffe4ec;
            color: #ad1457;
        }
    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="container pb-5">
        
        <div class="slideshow-container">
            <div class="slideshow">
                <?php 
                    $sql_promo = "SELECT * FROM promotions";
                    $res_promo = mysqli_query($conn, $sql_promo);
                    while ($row_p = mysqli_fetch_array($res_promo)) {
                        echo '<a href="detailpromotion.php?id=' . $row_p['id'] . '"><img src="img/' . $row_p['image'] . '"></a>';
                    }
                ?>
            </div>
        </div>

        <div class="promotion-banner text-center shadow-sm">
            <h2 class="fw-bold"><i class="fas fa-bullhorn"></i> โปรโมชั่นพิเศษประจำเดือน!</h2>
            <p class="mb-0">ซื้อครบ 500 บาท รับส่วนลดทันที 50 บาท หรือใช้โค้ด <span class="badge bg-white text-danger">NEWBOOK</span></p>
        </div>

        <h4 class="mb-4 text-secondary border-start border-4 border-danger ps-3">
            <?php if ($kw !== ""): ?>
                ผลการค้นหา: <span class="text-danger">"<?= htmlspecialchars($kw) ?>"</span>
                <small class="text-muted fs-6">(<?= $total_rows ?> รายการ)</small>
            <?php else: ?>
                หนังสือทั้งหมด <small class="text-muted fs-6">(<?= $total_rows ?> รายการ)</small>
            <?php endif; ?>
        </h4>
        
        <div class="row">
            <?php
            if ($kw !== "") {
                $sql = "SELECT * FROM stocks WHERE book_name LIKE '%$kw%' OR author_name LIKE '%$kw%' ORDER BY book_id DESC LIMIT $limit OFFSET $offset";
            } else {
                $sql = "SELECT * FROM stocks ORDER BY book_id DESC LIMIT $limit OFFSET $offset";
            }

            $result = mysqli_query($conn, $sql);
            $count  = mysqli_num_rows($result);

            if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 book-card p-3 border-0 shadow-sm text-center">
                    <div class="book-img-container mb-3">
                        <img src="img/<?=$row['image']?>" class="book-img">
                    </div>
                    
                    <div class="card-body p-0 d-flex flex-column">
                        <h6 class="fw-bold text-dark text-truncate mb-1"><?=$row['book_name']?></h6>
                        <p class="small text-muted mb-3">ผู้แต่ง: <?=$row['author_name']?></p>
                        
                        <div class="mt-auto">
                            <div class="mb-3">
                                <span class="price-text">฿<?=number_format($row['price'])?></span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a class="btn btn-outline-detail w-100 py-2 fw-bold small" href="detail.php?id=<?=$row['book_id']?>">ข้อมูล</a>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-outline-cart w-100 py-2 fw-bold small" href="order.php?id=<?=$row['book_id']?>">
                                        <i class="fas fa-cart-plus"></i> ซื้อ
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
                echo "<h3 class='text-muted'>❌ ไม่พบหนังสือที่ตรงกับคำค้นหา</h3>";
                echo "<a href='shop.php' class='btn btn-secondary mt-3'>แสดงหนังสือทั้งหมด</a>";
                echo "</div>";
            }
            mysqli_close($conn);
            ?>
        </div>

        <!-- Pagination -->
        <?php renderPagination($page, $total_pages, $kw); ?>

    </div>

    <script>
        $(document).ready(function(){
            $('.slideshow').slick({
                autoplay: true,
                autoplaySpeed: 3000,
                infinite: true,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: false,
                nextArrow: false
            });
        });
    </script>

</body>
</html>

<?php
function renderPagination($page, $total_pages, $kw = "") {
    if ($total_pages <= 1) return;

    $kw_param = $kw !== "" ? "&keyword=" . urlencode($kw) : "";

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

    // เลขหน้า (แสดงแบบ window ±3)
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