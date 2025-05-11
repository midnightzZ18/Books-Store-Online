<?php session_start(); 
include 'condb.php';?>

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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
       <main>
                    <div class="container-fluid">
                        <h1>Dashboard</h1>
                    </head>
    <body class="sb-nav-fixed">
        <?php include 'menu1.php';?>
            <div id="layoutSidenav_content">
                 <div class="row">
                            <?php
                                // SQL query เพื่อค้นหาผลรวมของค่า id ทั้งหมดในฐานข้อมูล
                                $sql = "SELECT COUNT(id) AS total_ids FROM user_form";
                                $result = $conn->query($sql);

                                // ตรวจสอบว่ามีผลลัพธ์จากการค้นหาหรือไม่
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $total_ids = $row["total_ids"];
                                } else {
                                    $total_ids = 0; // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                }
                            ?>
                            <div class="col-xl-3">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-header"><i class="fas fa-circle-user"></i>&emsp; ข้อมูลของผู้ใช้ (User)</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <h5 class="card-title" style="font-size: 18px;"><?php echo $total_ids; ?> คน</h5>
                                        <p class="card-text"> 
                                            <a href="data_userdashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php
                                // คำสั่ง SQL เพื่อนับจำนวนรายการในฐานข้อมูล "history"
                                $sql = "SELECT COUNT(id) AS total_records FROM history";
                                $result = $conn->query($sql);

                                // ตรวจสอบว่ามีผลลัพธ์จากการค้นหาหรือไม่
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $total_records = $row["total_records"];
                                } else {
                                    $total_records = 0; // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                }
                            ?>
                            <div class="col-xl-3">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-header"><i class="fas fa-receipt"></i>&emsp; ประวัติการสั่งซื้อ</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <h5 class="card-title" style="font-size: 18px;"><?php echo $total_records; ?> รายการ</h5>
                                        <p class="card-text"> 
                                            <a href="historydashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php
                                // SQL query เพื่อค้นหาผลรวมของค่า amount ทั้งหมดในฐานข้อมูล
                                $sql = "SELECT SUM(amount) AS total_amount FROM stocks";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $total_amount = $row["total_amount"];
                                } else {
                                    $total_amount = 0; // ถ้าไม่มีข้อมูลในฐานข้อมูล
                                }
                            ?>
                            <div class="col-xl-3">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-header"><i class="fas fa-box-archive"></i>&emsp; คลังสินค้า</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <h5 class="card-title" style="font-size: 18px;"><?php echo $total_amount; ?> เล่ม</h5>
                                        <p class="card-text"> 
                                            <a href="stockdashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php
                                // Counting the number of books with less than 10 quantity
                                $sql_low_stock = "SELECT COUNT(*) AS low_stock_count FROM stocks WHERE amount < 10";
                                $result_low_stock = $conn->query($sql_low_stock);

                                if ($result_low_stock->num_rows > 0) {
                                    $row_low_stock = $result_low_stock->fetch_assoc();
                                    $low_stock_count = $row_low_stock["low_stock_count"];
                                } else {
                                    $low_stock_count = 0;
                                }
                            ?>
                            <div class="col-xl-3">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-header"><i class="fa-solid fa-square-xmark"></i>&emsp; สินค้าใกล้หมด</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <h5 class="card-title" style="font-size: 18px;"><?php echo $low_stock_count; ?> เล่ม</h5>
                                        <p class="card-text"> 
                                            <a href="lowstockdashboard.php" class="text-white" style="text-decoration: none; font-size: 14px;">ดูเพิ่มเติม...</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





<?php
$con = mysqli_connect("localhost", "root", "", "data_db");
    
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
                    // Query to retrieve gender distribution data
$query = "SELECT sex, COUNT(*) AS count FROM history GROUP BY sex";

$result = mysqli_query($con, $query);

// Create arrays to store gender and count data
$sexes = array();
$counts = array();

// Fetch data from database and store in arrays
while ($row = mysqli_fetch_assoc($result)) {
    $sexes[] = $row['sex'];
    $counts[] = $row['count'];
}

// Close database connection
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>ตัวอย่างกราฟวงกลม</title>
    <!-- Import necessary JavaScript libraries -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
   #piechart-container {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            width: 500px;
            height: 300px;
            margin: 20px auto; /* Sets top and bottom margin to 20px, and left and right margin to auto */
            float: left; /* Disable floating */
        }
    </style>
</head>
<body>

<!-- Create div to display the pie chart -->
<div id="piechart-container">
    <div id="piechart"></div> <!-- This div will contain the pie chart -->
</div>
<script type="text/javascript">
    // Load Google Charts and initiate callback function
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function(){
        // Create data array to hold gender distribution data
        var data = [];
        data.push(['Sex', 'Count']);

        // Add gender and count data from PHP arrays to the data array
        <?php
        // Assuming $sexes and $counts are properly populated PHP arrays
        for ($i = 0; $i < count($sexes); $i++) {
            echo "data.push(['" . $sexes[$i] . "', " . $counts[$i] . "]);\n";
        }
        ?>

        // Define options for the pie chart
        var options = {
            'title': 'สิถิติการซื้อของเพศชายและหญิง',
            'width': 450,
            'height': 200,
            'sliceVisibilityThreshold': 0.00001,
            'colors': ['#FF33CC', '#66CCFF'] // Custom slice colors
        };

        // Create pie chart and draw it in the div with id "piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(google.visualization.arrayToDataTable(data), options);
    });
</script>

</body>
</html>


<?php
$con = mysqli_connect("localhost", "root", "", "data_db");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// หาวันแรกของสัปดาห์นี้
$firstDayOfWeek = date('Y-m-d', strtotime('monday this week'));

// หาวันสุดท้ายของสัปดาห์นี้
$lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));

// ดึงข้อมูลรายการสั่งซื้อและราคารวมสุทธิตามวันที่สั่งซื้อในสัปดาห์ปัจจุบัน
$query = "SELECT DATE(reg_date) AS order_date, SUM(total_price) AS weekly_revenue 
          FROM history
          WHERE DATE(reg_date) BETWEEN '$firstDayOfWeek' AND '$lastDayOfWeek'
          GROUP BY DATE(reg_date)
          ORDER BY DATE(reg_date)";

$result = mysqli_query($con, $query);

// สร้าง array เก็บข้อมูลวันที่และรายได้รวมตามวันที่
$order_dates = array();
$weekly_revenues = array();

while ($row = mysqli_fetch_assoc($result)) {
    $order_dates[] = $row['order_date'];
    $weekly_revenues[] = $row['weekly_revenue'];
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
       #linechart-container {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            width: 500px;
            height: 300px;
            margin: auto; 
            float: left; /* Disable floating */
            /* กำหนดให้ margin เป็น auto ทั้งแนวนอนและแนวตั้ง */
        }
    </style>
</head>
<body>
<!-- Create div to display the line chart -->
<div id="linechart-container">
    <canvas id="revenueChart"></canvas> <!-- Move canvas inside the div -->
</div>

<script>
    // รับข้อมูลจาก PHP และแปลงเป็น JavaScript เพื่อนำไปใช้ในการสร้างกราฟ
    var orderDates = <?php echo json_encode($order_dates); ?>;
    var weeklyRevenues = <?php echo json_encode($weekly_revenues); ?>;

    // สร้างกราฟด้วย Chart.js
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: orderDates, // ใช้วันที่เป็นแกน x
            datasets: [{
                label: 'รายได้รวมอาทิตย์นี้', // ชื่อเรื่องของข้อมูล
                data: weeklyRevenues, // ใช้รายได้รวมเป็นแกน y
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // สีพื้นหลังของกราฟ
                borderColor: 'rgba(54, 162, 235, 1)', // สีเส้นของกราฟ
                borderWidth: 1 // ความหนาของเส้นกราฟ
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // เริ่มแกน y ที่ค่า 0
                }
            }
        }
    });
</script>
</body>
</html>

















<?php
$con = mysqli_connect("localhost", "root", "", "data_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "SELECT DATE_FORMAT(reg_date, '%Y-%m') AS order_month, SUM(total_price) AS monthly_revenue 
          FROM history
          GROUP BY DATE_FORMAT(reg_date, '%Y-%m')
          ORDER BY DATE_FORMAT(reg_date, '%Y-%m')";

$result = mysqli_query($con, $query);

$order_months = array();
$monthly_revenues = array();

while ($row = mysqli_fetch_assoc($result)) {
    $order_months[] = $row['order_month'];
    $monthly_revenues[] = $row['monthly_revenue'];
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
      .chart-container {
    border: 2px solid #ccc;
    border-radius: 10px;
    padding: 20px;
    background-color: #f9f9f9;
    width: 500px;
    height: 300px;
    margin: 20px auto; /* Sets top and bottom margin to 20px, and left and right margin to auto */
    float: right; /* Position the container to the right */
}

    </style>
</head>
<body>
<div class="chart-container">
    <canvas id="monthlyRevenueChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var orderMonths = <?php echo json_encode($order_months); ?>;
    var monthlyRevenues = <?php echo json_encode($monthly_revenues); ?>;

    var ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    var monthlyRevenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: orderMonths,
            datasets: [{
                label: 'Monthly Revenue',
                data: monthlyRevenues,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>







<?php
$con = mysqli_connect("localhost", "root", "", "data_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "SELECT YEAR(reg_date) AS order_year, SUM(total_price) AS yearly_revenue 
          FROM history
          GROUP BY YEAR(reg_date)
          ORDER BY YEAR(reg_date)";

$result = mysqli_query($con, $query);

$order_years = array();
$yearly_revenues = array();

while ($row = mysqli_fetch_assoc($result)) {
    $order_years[] = $row['order_year'];
    $yearly_revenues[] = $row['yearly_revenue'];
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
       .chart-container {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            width: 500px;
            height: 300px;
            margin: 20px auto; /* Sets top and bottom margin to 20px, and left and right margin to auto */
            float: left; /* Disable floating */
        }
    </style>
</head>
<body>
<div class="chart-container">
    <canvas id="yearlyRevenueChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var orderYears = <?php echo json_encode($order_years); ?>;
    var yearlyRevenues = <?php echo json_encode($yearly_revenues); ?>;

    var ctx = document.getElementById('yearlyRevenueChart').getContext('2d');
    var yearlyRevenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: orderYears,
            datasets: [{
                label: 'Yearly Revenue',
                data: yearlyRevenues,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>



<?php
$con = mysqli_connect("localhost", "root", "", "data_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if form is submitted and selectedDate is set
if(isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];

    // Construct the query with the selected date
    $query = "SELECT book_name, SUM(orderQty) AS total_sales 
              FROM history
              WHERE DATE(reg_date) = '$selectedDate'
              GROUP BY book_name
              ORDER BY total_sales DESC";
} else {
    // Default query without filtering by date
    $query = "SELECT book_name, SUM(orderQty) AS total_sales 
              FROM history
              GROUP BY book_name
              ORDER BY total_sales DESC";
}

$result = mysqli_query($con, $query);

$best_selling_books = array();
$total_sales = array();

while ($row = mysqli_fetch_assoc($result)) {
    $best_selling_books[] = $row['book_name'];
    $total_sales[] = $row['total_sales'];
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
       .chart-container {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f9f9f9;
            width: 500px;
            height: 300px;
            margin: 20px auto; /* Sets top and bottom margin to 20px, and left and right margin to auto */
            float: left; /* Disable floating */
            position: relative; /* Relative positioning for absolute positioning of button */
        }

        #submitButton {
            position: absolute;
            bottom: 10px; /* Adjust as needed */
            left: 60%; /* Centers the button horizontally */
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

<div class="chart-container">
    <canvas id="bestSellingBooksChart"></canvas>
    <form method="post" id="dateForm">
        <label for="selectedDate">เลือกวันที่:</label>
        <input type="date" id="selectedDate" name="selectedDate">
        <input type="submit" value="ค้นหา" id="submitButton">
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var bestSellingBooks = <?php echo json_encode($best_selling_books); ?>;
    var totalSales = <?php echo json_encode($total_sales); ?>;

    var ctx = document.getElementById('bestSellingBooksChart').getContext('2d');
    var bestSellingBooksChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bestSellingBooks,
            datasets: [{
                label: 'หนังสือที่ขายดีที่สุดประจำวัน',
                data: totalSales,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>















<!-- <?php mysqli_close($conn);?>  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>        
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>