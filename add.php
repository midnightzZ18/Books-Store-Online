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

  <?php include 'adminmenu.php'; ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>เพิ่มสินค้าใหม่</h2>
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bookName">ชื่อหนังสือ:</label>
                    <input type="text" class="form-control" id="bookName" name="bookName">
                </div>
                <div class="form-group">
                    <label for="quantity">จำนวน:</label>
                    <input type="text" class="form-control" id="quantity" name="quantity">
                </div>
                <div class="form-group">
                    <label for="type">ประเภท:</label>
                    <input type="text" class="form-control" id="type" name="type">
                </div>
                <div class="form-group">
                    <label for="price">ราคา:</label>
                    <input type="text" class="form-control" id="price" name="price">
                </div>
                <div class="form-group">
                    <label for="authorName">ชื่อผู้แต่ง:</label>
                    <input type="text" class="form-control" id="authorName" name="authorName"><br>
                </div>
                <div class="form-group">
                    <label for="bookImage">รูปภาพหนังสือ:</label>
                    <input type="file" class="form-control-file" id="bookImage" name="bookImage">
                </div>
                <div class="form-group">
                    <label for="description">รายละเอียด:</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea><br>
                </div>
                <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
            </form>
        </div>
    </div>
</div>
