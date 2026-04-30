<?php
session_start();
include 'condb.php'; 

if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }
mysqli_set_charset($conn, "utf8mb4");

function getCount($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    return ($result && $row = mysqli_fetch_array($result)) ? $row[0] : 0;
}

// --- 1. ข้อมูลสรุป (Top Cards) ---
$total_ids = getCount($conn, "SELECT COUNT(id) FROM user_form");
$total_records = getCount($conn, "SELECT COUNT(id) FROM history");
$total_amount = getCount($conn, "SELECT SUM(amount) FROM stocks");
$low_stock_count = getCount($conn, "SELECT COUNT(*) FROM stocks WHERE amount < 10");

// --- 2. เตรียมข้อมูล 6 กราฟ --- (ส่วนนี้คงเดิม)
$sexes = []; 
$counts = [];
$res_gender = mysqli_query($conn, "SELECT sex, COUNT(*) AS count FROM history GROUP BY sex");
while ($row = mysqli_fetch_assoc($res_gender)) { 
    $sex_raw = strtolower(trim($row['sex']));
    if (in_array($sex_raw, ['male', 'ชาย'])) {
        $display = 'ชาย';
    } elseif (in_array($sex_raw, ['female', 'หญิง'])) {
        $display = 'หญิง';
    } else {
        $display = 'อื่นๆ';
    }
    // ป้องกันการซ้ำ (กรณีมีทั้ง male และ ชาย อยู่ในข้อมูล)
    if (!in_array($display, $sexes)) {
        $sexes[] = $display;
        $counts[] = $row['count'];
    } else {
        // ถ้าซ้ำ ให้รวมจำนวน
        $key = array_search($display, $sexes);
        $counts[$key] += $row['count'];
    }
}

$res_m = mysqli_query($conn, "SELECT DATE_FORMAT(reg_date, '%Y-%m') AS m_date, SUM(total_price) AS rev FROM history GROUP BY m_date ORDER BY m_date");
$order_months = []; $monthly_revenues = [];
while ($row = mysqli_fetch_assoc($res_m)) { $order_months[] = $row['m_date']; $monthly_revenues[] = $row['rev']; }

$res_y = mysqli_query($conn, "SELECT YEAR(reg_date) AS y_date, SUM(total_price) AS rev FROM history GROUP BY y_date ORDER BY y_date");
$order_years = []; $yearly_revenues = [];
while ($row = mysqli_fetch_assoc($res_y)) { $order_years[] = $row['y_date']; $yearly_revenues[] = $row['rev']; }

$best_books = []; $sales_books = [];
$sql_best_books = "SELECT book_name, SUM(orderQty) AS total FROM history GROUP BY book_name ORDER BY total DESC LIMIT 10";
$res_books = mysqli_query($conn, $sql_best_books);
if ($res_books) {
    while ($row = mysqli_fetch_assoc($res_books)) { 
        $best_books[] = $row['book_name']; 
        $sales_books[] = (int)$row['total']; 
    }
}
$best_books = array_reverse($best_books);
$sales_books = array_reverse($sales_books);

$res_w = mysqli_query($conn, "SELECT DATE(reg_date) AS d_date, SUM(total_price) AS rev FROM history WHERE reg_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY d_date ORDER BY d_date");
$weekly_dates = []; $weekly_revs = [];
while ($row = mysqli_fetch_assoc($res_w)) { $weekly_dates[] = $row['d_date']; $weekly_revs[] = $row['rev']; }

$provinces = []; $prov_revs = [];
$res_p = mysqli_query($conn, "SELECT provinces, SUM(total_price) AS total FROM history GROUP BY provinces ORDER BY total DESC LIMIT 5");
while ($row = mysqli_fetch_assoc($res_p)) { $provinces[] = $row['provinces']; $prov_revs[] = $row['total']; }
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container-fluid { padding: 20px; }
        .chart-box { 
            border: 1px solid #e0e0e0; 
            padding: 15px; 
            border-radius: 12px; 
            background: #fff; 
            margin-bottom: 20px; 
            height: 320px;           /* ลดความสูงกราฟ */
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .card-custom { border-radius: 15px; border: none; }
        .row > div { margin-bottom: 20px; }
    </style>
</head>
<body class="sb-nav-fixed bg-light">
    <?php include 'adminmenu.php'; ?>
    
    <div id="layoutSidenav_content">
        <div class="container-fluid px-4 mt-5">
            <h1 class="mt-4">Dashboard Online Books Store</h1>
            <hr>
            
            <!-- Top Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-primary text-white card-custom shadow h-100 py-2">
                        <div class="card-body">สมาชิก: <strong><?php echo $total_ids; ?></strong> คน</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-warning text-dark card-custom shadow h-100 py-2">
                        <div class="card-body">ออเดอร์: <strong><?php echo $total_records; ?></strong> รายการ</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-success text-white card-custom shadow h-100 py-2">
                        <div class="card-body">สต็อกเล่ม: <strong><?php echo $total_amount; ?></strong> เล่ม</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-danger text-white card-custom shadow h-100 py-2">
                        <div class="card-body">ใกล้หมด: <strong><?php echo $low_stock_count; ?></strong> รายการ</div>
                    </div>
                </div>
            </div>

            <!-- Charts - แถวละ 3 คอลัมน์ -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="chart-box">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-box">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-box">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="chart-box">
                        <canvas id="booksChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-box">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-box">
                        <canvas id="provinceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script>
        const commonOptions = { 
            maintainAspectRatio: false, 
            plugins: { 
                legend: { position: 'top' } 
            } 
        };

        // 1. Pie Chart เพศ (แก้ label ทับกัน)
        new Chart(document.getElementById('genderChart'), {
            type: 'pie',
            data: { 
                labels: <?php echo json_encode($sexes, JSON_UNESCAPED_UNICODE); ?>, 
                datasets: [{ 
                    data: <?php echo json_encode($counts); ?>, 
                    backgroundColor: ['#FFCE56','#36A2EB', '#FF6384']  // เพิ่มสีสำรองเผื่อมีเพศอื่น
                }] 
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    title: { 
                        display: true, 
                        text: 'สัดส่วนเพศลูกค้า',
                        font: { size: 16 }
                    },
                    legend: { 
                        position: 'bottom',        // เปลี่ยนเป็นด้านล่าง → ไม่ทับแน่นอน
                        align: 'center',
                        labels: { 
                            boxWidth: 18,
                            padding: 18,           // เพิ่มระยะห่างระหว่างรายการ
                            font: { size: 14 },
                            usePointStyle: true
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 20,
                        left: 10,
                        right: 10
                    }
                }
            }
        });

        // 2. Monthly Bar
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: { 
                labels: <?php echo json_encode($order_months); ?>, 
                datasets: [{ 
                    label: 'ยอดขายรายเดือน (บาท)', 
                    data: <?php echo json_encode($monthly_revenues); ?>, 
                    backgroundColor: '#4BC0C0' 
                }] 
            },
            options: commonOptions
        });

        // 3. Yearly Line
        new Chart(document.getElementById('yearlyChart'), {
            type: 'line',
            data: { 
                labels: <?php echo json_encode($order_years); ?>, 
                datasets: [{ 
                    label: 'ยอดขายรายปี (บาท)', 
                    data: <?php echo json_encode($yearly_revenues); ?>, 
                    borderColor: '#9966FF', 
                    fill: true, 
                    backgroundColor: 'rgba(153, 102, 255, 0.1)' 
                }] 
            },
            options: commonOptions
        });

        // 4. Best Books (Horizontal Bar)
        new Chart(document.getElementById('booksChart'), {
            type: 'bar',
            data: { 
                labels: <?php echo json_encode($best_books, JSON_UNESCAPED_UNICODE); ?>, 
                datasets: [{ 
                    label: 'จำนวนเล่มที่ขายได้', 
                    data: <?php echo json_encode($sales_books); ?>, 
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }] 
            },
            options: { 
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: '10 อันดับหนังสือขายดี' }
                },
                scales: { x: { beginAtZero: true } }
            }
        });

        // 5. Weekly Line
        new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: { 
                labels: <?php echo json_encode($weekly_dates); ?>, 
                datasets: [{ 
                    label: 'รายได้ 7 วันล่าสุด', 
                    data: <?php echo json_encode($weekly_revs); ?>, 
                    borderColor: '#FF6384', 
                    tension: 0.3 
                }] 
            },
            options: commonOptions
        });

        // 6. Province Doughnut
        new Chart(document.getElementById('provinceChart'), {
            type: 'doughnut',
            data: { 
                labels: <?php echo json_encode($provinces, JSON_UNESCAPED_UNICODE); ?>, 
                datasets: [{ 
                    data: <?php echo json_encode($prov_revs); ?>, 
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'] 
                }] 
            },
            options: { 
                plugins: { 
                    title: { display: true, text: '5 จังหวัดที่มียอดซื้อสูงสุด' },
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>