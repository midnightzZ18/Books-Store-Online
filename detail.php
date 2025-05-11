<?php include'condb.php'?>
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
<?php include 'menu.php'; ?>
<div class="container">
    <div class="row">
    <?php
    $ids=$_GET['id'];
$sql ="SELECT * FROM stocks, type WHERE stocks.typebook_id = type.typebook_id and stocks.book_id='$ids' " ;
$result = mysqli_query($conn,$sql);
$row=mysqli_fetch_array($result);
    ?>
        <div class="col-md-4">
            <br>
            <img src="img/<?=$row['image']?>" width="350px" class="mt-2 p-2 my-2 border" />       
         </div>
        <div class="col-md-6">
            <br>
            ID: <?=$row['book_id']?> <br>
            <h5 class="text-success"><?=$row['book_name']?> </h5>
            ประเภทสินค้า: <?=$row['type_name']?> <br>
            รายละเอียด: <?=$row['detail']?> <br>
            ชื่อผู้เขียน: <?=$row['author_name']?> <br>
            ราคา:<b  class="text-danger"> <?=$row['price']?> </b> บาท <br>
            <a class="btn btn-outline-info mt-3" href ="order.php?id=<?=$row['book_id']?>"  > Add to cart </a>
            
        </div>
        
    </div>
</div>
   <?php
   mysqli_close($conn);
?> 
</body>
</html>