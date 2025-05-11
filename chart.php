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

    <style>
        body {
            width: 1200px; /* Increased width for side-by-side layout */
            margin: 3rem auto;
        }
        .chart-container {
            width: 45%; /* Adjust the width as needed */
            height: auto;
            margin-bottom: 20px;
            display: inline-block; /* Display charts in a horizontal line */
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'menu1.php';?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="stockdashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box-archive"></i></div>
                            คลังสินค้า
                        </a>
                        <a class="nav-link" href="lowstockdashboard.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-square-xmark"></i></div>                                
                            สินค้าใกล้หมด
                        </a>
                        <a class="nav-link" href="data_userdashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-circle-user"></i></div>
                            ข้อมูลของผู้ใช้ (User)
                        </a>
                        <a class="nav-link" href="historydashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
                            ประวัติการขาย
                        </a>
                        <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            ประเภทหนังสือ
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="self_dev.php">พัฒนาตนเอง</a>
                                <a class="nav-link" href="literature.php">วรรณกรรม</a>
                                <a class="nav-link" href="Food_health.php">อาหาร/สุขภาพ</a>
                                <a class="nav-link" href="Entertain_travel.php">บันเทิง/ท่องเที่ยว</a>
                                <a class="nav-link" href="anime.php">การ์ตูน/มังงะ</a>
                            </nav>
                        </div> -->
                        <a class="nav-link" href="chart.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            กราฟข้อมูล
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="chart-container">
                    
                    <!-- First Chart -->
                    <p>จำนวนหนังสือที่ขายออกไปในแต่ละประเภท</p>
                    <canvas id="graphCanvas"></canvas>
                </div>
                <div class="chart-container">
                    <!-- Second Chart -->
                    <p>จำนวนหนังสือที่ขายออกไปในแต่ละประเภท</p>
                    <canvas id="graphCanvas2"></canvas>
                </div>
                <div class="chart-container">
                    <!-- Third Chart -->
                    <p>จำนวนหนังสือที่ขายออกไปในแต่ละประเภท</p>
                    <canvas id="graphCanvas3"></canvas>
                </div>
                <div class="chart-container">
                    <!-- Third Chart -->
                    <p>จำนวนหนังสือที่ขายออกไปในแต่ละประเภท</p>
                    <canvas id="graphCanvas4"></canvas>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            showGraph();
            showGraph2();
            showGraph3(); 
            showGraph4();// Call the function for the third chart
        });
        function showGraph() {
            $.post('datapiechart.php', function(data) {
                console.log(data);
                let type_Book = [];
                let amount = [];
                for (let i in data) {
                    type_Book.push(data[i].type_Book )
                    amount.push(data[i].amount )
                }
                let chartdata = {
                    labels: type_Book,
                    datasets: [{
                        label: function(context) {
                            return context.dataset.data[context.dataIndex] + ' books sold';
                        },
                        backgroundColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'], // Yellow, Pink, Blue, Green, Red
                        borderColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'],
                        hoverBackgroundColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'],
                        hoverBorderColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'],
                        data: amount
                    }]
                };

                let graphTarget = $('#graphCanvas');
                let pieGraph = new Chart(graphTarget, {
                    type: 'pie',
                    data: chartdata,
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontColor: 'black',
                            }
                        }
                    }
                });
            });
        }

        function showGraph2() {
            // Modify this function to load data for the second chart
            $.post('datapiechart.php', function(data) {
                console.log(data);
                let type_Book = [];
                let amount = [];
                for (let i in data) {
                    type_Book.push(data[i].type_Book )
                    amount.push(data[i].amount )
                }
                let chartdata = {
                    labels: type_Book,
                    datasets: [{
                        label: function(context) {
                            return context.dataset.data[context.dataIndex] + ' books sold';
                        },
                        backgroundColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'], // Yellow, Pink, Blue, Green, Red
                        borderColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'],
                        hoverBackgroundColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'],
                        hoverBorderColor: ['#FFFF00', '#FF69B4', '#1E90FF', '#00FF00', '#FF0000'],
                        data: amount
                    }]
                };

                let graphTarget = $('#graphCanvas2');
                let pieGraph = new Chart(graphTarget, {
                    type: 'doughnut',
                    data: chartdata,
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontColor: 'black',
                            }
                        }
                    }
                });
            });
        }

        function showGraph3() {
            // Modify this function to load data for the third chart
            $.post('datapiechart.php', function(data) {
                console.log(data);
                let type_Book = [];
                let amount = [];
                for (let i in data) {
                    type_Book.push(data[i].type_Book )
                    amount.push(data[i].amount )
                }
                let chartdata = {
                    labels: type_Book,
                    datasets: [{
                        label: function(context) {
                            return context.dataset.data[context.dataIndex] + ' books sold';
                        },
                        backgroundColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'], // Customize these colors
                        borderColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'],
                        hoverBackgroundColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'],
                        hoverBorderColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'],
                        data: amount
                    }]
                };

                let graphTarget = $('#graphCanvas3');
                let pieGraph = new Chart(graphTarget, {
                    type: 'line',
                    data: chartdata,
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontColor: 'black',
                            }
                        }
                    }
                });
            });
        }
        function showGraph4() {
            // Modify this function to load data for the third chart
            $.post('datapiechart.php', function(data) {
                console.log(data);
                let type_Book = [];
                let amount = [];
                for (let i in data) {
                    type_Book.push(data[i].type_Book )
                    amount.push(data[i].amount )
                }
                let chartdata = {
                    labels: type_Book,
                    datasets: [{
                        label: function(context) {
                            return context.dataset.data[context.dataIndex] + ' books sold';
                        },
                        backgroundColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'], // Customize these colors
                        borderColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'],
                        hoverBackgroundColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'],
                        hoverBorderColor: ['#FF5733', '#FFC0CB', '#00CED1', '#228B22', '#FF4500'],
                        data: amount
                    }]
                };

                let graphTarget = $('#graphCanvas4');
                let pieGraph = new Chart(graphTarget, {
                    type: 'bar',
                    data: chartdata,
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                fontColor: 'black',
                            }
                        }
                    }
                });
            });
        }
    </script>
</body>
</html>
