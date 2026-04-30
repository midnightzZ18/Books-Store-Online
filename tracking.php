<?php include 'condb.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js" ></script>
</head>
<body>
    <?php include 'menu.php'; ?>
    <br> <h3 style="text-align:center;">ติดตามพัสดุ</h3> 
    <div class="container" style="display: flex; flex-direction: column; align-items: center;">
        <div style="display: flex; align-items: center;">
            <img src="img\parcel.png" width="100px" height="100px"> <hr width="200">
            <img src="img\truck.png" width="100px" height="100px"> <hr width="200">
            <img src="img\motorcycle.png" width="100px" height="100px"> 
        </div>
        <div style="display: flex; align-items: center;">
            <a style="color: #000000;">เข้ารับพัสดุ</a> <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;           
            <a style="color: #000000;">ระหว่างขนส่ง</a> <br>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            <a style="color: #000000;">จัดส่งพัสดุ</a> <br>
        </div>
    </div> 
    <div class="container">
        <div style="display: flex; align-items: center;">
            <br> 
            <h5>ผู้รับ</h5> &nbsp;&nbsp;
            <input class="form-control me-2" type="search" placeholder="ผู้รับ">
            <h5>สถานะ</h5> &nbsp;&nbsp;
            <input class="form-control me-2" type="search" placeholder="สถานะ">
        </div> <br>
        <div style="display: flex; align-items: center;">
            <h5>หมายเลขพัสดุ</h5> 
            <input class="form-control me-2" type="search" placeholder="หมายเลขพัสดุ">
        </div>
    </div>


 
</body>
</html>