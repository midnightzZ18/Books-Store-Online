<?php
    include 'condb.php';
    session_start();
    
    if (!isset($_SESSION['user_name'])) {
        header('location:login_form.php');
        exit;
    }

    $sql = "SELECT * FROM coupons WHERE coupon_id > 0";
    $result = $conn->query($sql);
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Coupons</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .coupon-container {
            margin-top: 50px;
        }
        .coupon-code {
            font-size: 24px;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
        }
        .copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .copy-button:hover {
            background-color: #0056b3;
        }
        .discount {
            font-size: 16px;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
    
    <br><h3>นี่คือคูปองของคุณ</h3>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="coupon-container">
                    <?php
                    
                    // Check if coupons are found for the user
                    if ($result->num_rows > 0) {
                        // Display coupons found
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='coupon-name'>" . $row["coupon_name"] .
                            "<div class='coupon-code'>" . $row["coupon_code"] . 
                            "<button class='copy-button' onclick='copyToClipboard(\"" . $row["coupon_code"] . "\")
                            '>Copy</button><div class='discount'>ส่วนลด: " . $row["discount"] . "บาท</div></div>";
                        }
                    } else {
                        // No coupons found
                        echo "คุณยังไม่มีคูปองที่ใช้งานได้";
                    }

                    // Close database connection
                    $conn->close();

                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyToClipboard(text) {
            var input = document.createElement('textarea');
            input.innerHTML = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('คัดลอกโค้ดสำเร็จ: ' + text);
        }
    </script>
</body>
</html>