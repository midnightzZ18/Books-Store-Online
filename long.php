<?php
    @include 'condb.php';
    session_start();
    if(!isset($_SESSION['admin_name'])){
    header('location:login_form.php');
    }
?>
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
    <div class="container">
        <br><br>
        <div class="col-md-11 black-border-frame"></div> <br>
        <h4>หนังสือ วรรณกรรม</h4>
        <h1>welcome <span><?php echo $_SESSION['admin_name'] ?></span></h1>
        <div class="row justify-content-start text-left">
            <?php 
            // ประกาศประเภทที่คุณต้องการแสดง
            $selected_type = echo $_SESSION['admin_name'] ; // ประเภท "วรรณกรรม"

            $sql = "SELECT * FROM user_form WHERE admin_name = '$selected_type'";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_array($result)){
            ?>
            
            <div class="col-sm-3"> 
                <div>
                    <img src="img/<?=$row['image']?>" width="200px" height="250" class="mx-auto mt-3 p-2 my-2 border"> <br>
                    <h5 class="text-success"><?=$row['name']?> </h5> 
                    Price:<b class="text-danger"> <?=$row['sex']?> </b> Bath <br>
                    Author: <?=$row['email']?> <br>
                    <a class="btn btn-outline-info mt-3" href ="detail.php?id=<?=$row['book_id']?>" > รายละเอียด </a>
                </div>
                <br>
            </div>

            <?php
            }
            mysqli_close($conn);
            ?>
        </div>

        <style>
            .black-border-frame {
                border: 1px solid black;
                padding: 100px;
                margin-top: -30px;
                background-color: #000000; 
            }
        </style>

    </div>
</body>

</html>