<?php
session_start();
include 'condb.php'; // ตรวจสอบว่า condb.php มี mysqli_set_charset("utf8mb4")
header('Content-Type: text/html; charset=utf-8');

// Initialize database connection
$con = mysqli_connect("localhost", "root", "", "data_db");
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8mb4");

// Query for total users
$sql_users = "SELECT COUNT(id) AS total_ids FROM user_form";
$result_users = $con->query($sql_users);
$total_ids = $result_users->num_rows > 0 ? $result_users->fetch_assoc()['total_ids'] : 0;

// Query for total purchase history
$sql_history = "SELECT COUNT(id) AS total_records FROM history";
$result_history = $con->query($sql_history);
$total_records = $result_history->num_rows > 0 ? $result_history->fetch_assoc()['total_records'] : 0;

// Query for total stock
$sql_stocks = "SELECT SUM(amount) AS total_amount FROM stocks";
$result_stocks = $con->query($sql_stocks);
$total_amount = $result_stocks->num_rows > 0 ? $result_stocks->fetch_assoc()['total_amount'] : 0;

// Query for low stock
$sql_low_stock = "SELECT COUNT(*) AS low_stock_count FROM stocks WHERE amount < 10";
$result_low_stock = $con->query($sql_low_stock);
$low_stock_count = $result_low_stock->num_rows > 0 ? $result_low_stock->fetch_assoc()['low_stock_count'] : 0;

// Query for gender distribution
$query_gender = "SELECT sex, COUNT(*) AS count FROM history GROUP BY sex";
$result_gender = mysqli_query($con, $query_gender);
$sexes = [];
$counts = [];
while ($row = mysqli_fetch_assoc($result_gender)) {
    $sexes[] = $row['sex'];
    $counts[] = $row['count'];
}

// Query for monthly revenue
$selected_month = isset($_POST['month']) ? $_POST['month'] : '';
$query_monthly = "SELECT DATE_FORMAT(reg_date, '%Y-%m') AS order_month, SUM(total_price) AS monthly_revenue 
                 FROM history";
if (!empty($selected_month)) {
    $query_monthly .= " WHERE MONTH(reg_date) = $selected_month";
}
$query_monthly .= " GROUP BY DATE_FORMAT(reg_date, '%Y-%m') ORDER BY DATE_FORMAT(reg_date, '%Y-%m')";
$result_monthly = mysqli_query($con, $query_monthly);
$order_months = [];
$monthly_revenues = [];
while ($row = mysqli_fetch_assoc($result_monthly)) {
    $order_months[] = $row['order_month'];
    $monthly_revenues[] = $row['monthly_revenue'];
}

// Query for yearly revenue
$query_yearly = "SELECT YEAR(reg_date) AS order_year, SUM(total_price) AS yearly_revenue 
                FROM history GROUP BY YEAR(reg_date) ORDER BY YEAR(reg_date)";
$result_yearly = mysqli_query($con, $query_yearly);
$order_years = [];
$yearly_revenues = [];
while ($row = mysqli_fetch_assoc($result_yearly)) {
    $order_years[] = $row['order_year'];
    $yearly_revenues[] = $row['yearly_revenue'];
}

// Query for daily revenue
$selected_date = isset($_POST['date']) ? $_POST['date'] : '';
$query_daily = "SELECT DATE(reg_date) AS order_date, SUM(total_price) AS daily_revenue 
               FROM history";
if (!empty($selected_date)) {
    $query_daily .= " WHERE DATE(reg_date) = '$selected_date'";
}
$query_daily .= " GROUP BY DATE(reg_date) ORDER BY DATE(reg_date)";
$result_daily = mysqli_query($con, $query_daily);
$order_dates = [];
$daily_revenues = [];
while ($row = mysqli_fetch_assoc($result_daily)) {
    $order_dates[] = $row['order_date'];
    $daily_revenues[] = $row['daily_revenue'];
}

// Query for weekly revenue
$firstDayOfWeek = date('Y-m-d', strtotime('monday this week'));
$lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));
$query_weekly = "SELECT DATE(reg_date) AS order_date, SUM(total_price) AS weekly_revenue 
                FROM history WHERE DATE(reg_date) BETWEEN '$firstDayOfWeek' AND '$lastDayOfWeek'
                GROUP BY DATE(reg_date) ORDER BY DATE(reg_date)";
$result_weekly = mysqli_query($con, $query_weekly);
$order_dates_weekly = [];
$weekly_revenues = [];
while ($row = mysqli_fetch_assoc($result_weekly)) {
    $order_dates_weekly[] = $row['order_date'];
    $weekly_revenues[] = $row['weekly_revenue'];
}

// Query for best-selling books
$selected_book_date = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : '';
$query_books = "SELECT book_name, SUM(orderQty) AS total_sales 
               FROM history";
if (!empty($selected_book_date)) {
    $query_books .= " WHERE DATE(reg_date) = '$selected_book_date'";
}
$query_books .= " GROUP BY book_name ORDER BY total_sales DESC";
$result_books = mysqli_query($con, $query_books);
$best_selling_books = [];
$total_sales_books = [];
while ($row = mysqli_fetch_assoc($result_books)) {
    $best_selling_books[] = $row['book_name'];
    $total_sales_books[] = $row['total_sales'];
}

// Query for best-selling customers
$query_customers = "SELECT cus_name, SUM(orderQty) AS total_sales 
                   FROM history GROUP BY cus_name ORDER BY total_sales DESC";
$result_customers = mysqli_query($con, $query_customers);
$best_selling_customers = [];
$total_sales_customers = [];
while ($row = mysqli_fetch_assoc($result_customers)) {
    $best_selling_customers[] = $row['cus_name'];
    $total_sales_customers[] = $row['total_sales'];
}

// Query for best-selling provinces
$query_provinces = "SELECT provinces, SUM(orderQty) AS total_sales 
                   FROM history GROUP BY provinces ORDER BY total_sales DESC";
$result_provinces = mysqli_query($con, $query_provinces);
$best_selling_provinces = [];
$total_sales_provinces = [];
while ($row = mysqli_fetch_assoc($result_provinces)) {
    $best_selling_provinces[] = $row['provinces'];
    $total_sales_provinces[] = $row['total_sales'];
}

// Close database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        .container-fluid {
            padding: 20px;
            margin-top: 60px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .col-xl-3 {
            flex: 0 0 calc(25% - 20px);
            max-width: calc(25% - 20px);
            margin: 10px;
        }
        .chart-container, #piechart-container, #linechart-container {
            flex: 0 0 calc(33.33% - 20px);
            max-width: calc(33.33% - 20px);
            margin: 10px;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #f9f9f9;
            height: 350px;
            box-sizing: border-box;
            position: relative;
        }
        #submitButton {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .chart-container canvas, #piechart-container > div {
            width: 100% !important;
            height: 250px !important;
        }
        @media (max-width: 992px) {
            .col-xl-3 {
                flex: 0 0 calc(50% - 20px);
                max-width: calc(50% - 20px);
            }
            .chart-container, #piechart-container, #linechart-container {
                flex: 0 0 calc(50% - 20px);
                max-width: calc(50% - 20px);
            }
        }
        @media (max-width: 576px) {
            .col-xl-3 {
                flex: 0 0 calc(100% - 20px);
                max-width: calc(100% - 20px);
            }
            .chart-container, #piechart-container, #linechart-container {
                flex: 0 0 calc(100% - 20px);
                max-width: calc(100% - 20px);
            }
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'menu1.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1>Dashboard</h1>
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-header"><i class="fas fa-circle-user"></i>  ข้อมูลของผู้ใช้ (User)</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h5 class="card-title" style="font-size: 18px;"><?php echo $total_ids; ?> คน</h5>
                                <p class="card-text">
                                    <a href="data_userdashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-header"><i class="fas fa-receipt"></i>  ประวัติการสั่งซื้อ</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h5 class="card-title" style="font-size: 18px;"><?php echo $total_records; ?> รายการ</h5>
                                <p class="card-text">
                                    <a href="historydashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-header"><i class="fas fa-box-archive"></i>  คลังสินค้า</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h5 class="card-title" style="font-size: 18px;"><?php echo $total_amount; ?> เล่ม</h5>
                                <p class="card-text">
                                    <a href="stockdashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-header"><i class="fa-solid fa-square-xmark"></i>  สินค้าใกล้หมด</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <h5 class="card-title" style="font-size: 18px;"><?php echo $low_stock_count; ?> เล่ม</h5>
                                <p class="card-text">
                                    <a href="lowstockdashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Gender Pie Chart -->
                    <div id="piechart-container">
                        <div id="piechart"></div>
                    </div>
                    <script>
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(function() {
                            var data = google.visualization.arrayToDataTable([
                                ['Sex', 'Count'],
                                <?php for ($i = 0; $i < count($sexes); $i++) {
                                    echo "['" . $sexes[$i] . "', " . $counts[$i] . "],";
                                } ?>
                            ]);
                            var options = {
                                title: 'สถิติการซื้อของเพศชายและหญิง',
                                width: '100%',
                                height: 250,
                                sliceVisibilityThreshold: 0.00001,
                                colors: ['#FF33CC', '#66CCFF']
                            };
                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                            chart.draw(data, options);
                        });
                    </script>

                    <!-- Monthly Revenue Bar Chart -->
                    <div class="chart-container">
                        <canvas id="monthlyRevenueChart"></canvas>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <label for="month">เลือกเดือน:</label>
                            <select id="month" name="month">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <input type="submit" value="แสดง" id="submitButton">
                        </form>
                    </div>
                    <script>
                        var orderMonths = <?php echo json_encode($order_months, JSON_UNESCAPED_UNICODE); ?>;
                        var monthlyRevenues = <?php echo json_encode($monthly_revenues); ?>;
                        var ctxMonthly = document.getElementById('monthlyRevenueChart').getContext('2d');
                        new Chart(ctxMonthly, {
                            type: 'bar',
                            data: {
                                labels: orderMonths,
                                datasets: [{
                                    label: 'ยอดขายรายเดือน',
                                    data: monthlyRevenues,
                                    backgroundColor: 'rgba(255, 99, 132, 1)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>

                    <!-- Yearly Revenue Line Chart -->
                    <div class="chart-container">
                        <canvas id="yearlyRevenueChart"></canvas>
                    </div>
                    <script>
                        var orderYears = <?php echo json_encode($order_years); ?>;
                        var yearlyRevenues = <?php echo json_encode($yearly_revenues); ?>;
                        var ctxYearly = document.getElementById('yearlyRevenueChart').getContext('2d');
                        new Chart(ctxYearly, {
                            type: 'line',
                            data: {
                                labels: orderYears,
                                datasets: [{
                                    label: 'ยอดขายรายปี',
                                    data: yearlyRevenues,
                                    backgroundColor: 'rgb(106, 90, 205, 1)',
                                    borderColor: 'rgb(106, 90, 205, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>

                    <!-- Daily Revenue Bar Chart -->
                    <div id="linechart-container">
                        <canvas id="revenueChart"></canvas>
                        <form method="post" action="">
                            <label for="date">เลือกวันที่:</label>
                            <input type="date" id="date" name="date">
                            <input type="submit" value="แสดงข้อมูล" id="submitButton">
                        </form>
                    </div>
                    <script>
                        var orderDates = <?php echo json_encode($order_dates); ?>;
                        var dailyRevenues = <?php echo json_encode($daily_revenues); ?>;
                        var ctxDaily = document.getElementById('revenueChart').getContext('2d');
                        new Chart(ctxDaily, {
                            type: 'bar',
                            data: {
                                labels: orderDates,
                                datasets: [{
                                    label: 'รายได้รวมตามวันที่',
                                    data: dailyRevenues,
                                    backgroundColor: 'rgba(54, 162, 235, 1)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>

                    <!-- Weekly Revenue Line Chart -->
                    <div class="chart-container">
                        <canvas id="weeklyRevenueChart"></canvas>
                    </div>
                    <script>
                        var orderDatesWeekly = <?php echo json_encode($order_dates_weekly); ?>;
                        var weeklyRevenues = <?php echo json_encode($weekly_revenues); ?>;
                        var ctxWeekly = document.getElementById('weeklyRevenueChart').getContext('2d');
                        new Chart(ctxWeekly, {
                            type: 'line',
                            data: {
                                labels: orderDatesWeekly,
                                datasets: [{
                                    label: 'ยอดขายอาทิตย์นี้',
                                    data: weeklyRevenues,
                                    backgroundColor: 'rgba(154, 205, 50, 1)',
                                    borderColor: 'rgba(154, 205, 50, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>

                    <!-- Best-Selling Books Bar Chart -->
                    <div class="chart-container">
                        <canvas id="bestSellingBooksChart"></canvas>
                        <form method="post" id="dateForm">
                            <label for="selectedDate">เลือกวันที่:</label>
                            <input type="date" id="selectedDate" name="selectedDate">
                            <input type="submit" value="ค้นหา" id="submitButton">
                        </form>
                    </div>
                    <script>
                        var bestSellingBooks = <?php echo json_encode($best_selling_books, JSON_UNESCAPED_UNICODE); ?>;
                        var totalSalesBooks = <?php echo json_encode($total_sales_books); ?>;
                        var ctxBooks = document.getElementById('bestSellingBooksChart').getContext('2d');
                        new Chart(ctxBooks, {
                            type: 'bar',
                            data: {
                                labels: bestSellingBooks,
                                datasets: [{
                                    label: 'หนังสือที่ขายดีที่สุดประจำวัน',
                                    data: totalSalesBooks,
                                    backgroundColor: 'rgba(0, 206, 209, 1)',
                                    borderColor: 'rgba(0, 206, 209, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>

                    <!-- Best-Selling Customers Bar Chart -->
                    <div class="chart-container">
                        <canvas id="bestSellingCustomersChart"></canvas>
                    </div>
                    <script>
                        var bestSellingCustomers = <?php echo json_encode($best_selling_customers, JSON_UNESCAPED_UNICODE); ?>;
                        var totalSalesCustomers = <?php echo json_encode($total_sales_customers); ?>;
                        var ctxCustomers = document.getElementById('bestSellingCustomersChart').getContext('2d');
                        new Chart(ctxCustomers, {
                            type: 'bar',
                            data: {
                                labels: bestSellingCustomers,
                                datasets: [{
                                    label: 'ลูกค้าที่ซื้อสินค้ามากที่สุด',
                                    data: totalSalesCustomers,
                                    backgroundColor: 'rgba(60, 179, 113, 1)',
                                    borderColor: 'rgba(60, 179, 113, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>

                    <!-- Best-Selling Provinces Bar Chart -->
                    <div class="chart-container">
                        <canvas id="bestSellingProvincesChart"></canvas>
                    </div>
                    <script>
                        var bestSellingProvinces = <?php echo json_encode($best_selling_provinces, JSON_UNESCAPED_UNICODE); ?>;
                        var totalSalesProvinces = <?php echo json_encode($total_sales_provinces); ?>;
                        var ctxProvinces = document.getElementById('bestSellingProvincesChart').getContext('2d');
                        new Chart(ctxProvinces, {
                            type: 'bar',
                            data: {
                                labels: bestSellingProvinces,
                                datasets: [{
                                    label: 'จังหวัดที่ขายดีที่สุด',
                                    data: totalSalesProvinces,
                                    backgroundColor: 'rgba(255, 165, 0, 1)',
                                    borderColor: 'rgba(255, 165, 0, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: { y: { beginAtZero: true } },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>