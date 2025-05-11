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
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js" ></script>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container"> <br>
        <div class="col-md-2 black-border-frame"></div>
        <div class="text-center"> 
        <a href="add.php" class="btn btn-primary">เพิ่มสินค้า</a>
        <a href="addcoupon.php" class="btn btn-primary">เพิ่มคูปอง</a>
        <a href="addpromotion.php" class="btn btn-primary">เพิ่มโปรโมชั่น</a>
    </div>
       
    <div class="text"> 
        </div><br>
        <h4>หนังสือขายดี</h4>
        <div class="row justify-content-start text-left">
            <?php 
            $sql = "SELECT * FROM stocks ORDER BY book_id" ;
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
            ?>
            
            <div class="col-sm-3"> 
                <div>
                    <img src="img/<?=$row['image']?>" width="200px" height="250" class="mx-auto mt-3 p-2 my-2 border"> <br>
                    <h5 class="text-success"><?=$row['book_name']?> </h5> 
                    Price:<b class="text-danger"> <?=$row['price']?> </b> Bath <br>
                    Author: <?=$row['author_name']?> <br>
                    <a class="btn btn-outline-info mt-3" href="edit.php?id=<?=$row['book_id']?>"> แก้ไข </a>
                    <a class="btn btn-outline-info mt-3" href ="detail.php?id=<?=$row['book_id']?>" > รายละเอียด </a>
                    <a class="btn btn-outline-info mt-3" href="delete.php?id=<?=$row['book_id']?>"> ลบ </a>
                    
                </div>
                <br>
            </div>

            <?php
            }
            mysqli_close($conn);
            ?>
        </div>

        <style>
        body {
            background-image:url('1.png');/* Set your desired background color */
        }
          
        </style>

    </div>
</body>
</html>
