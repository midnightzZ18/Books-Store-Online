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
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- slickJS -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <br>
        <style>
            .slideshow {
                display: flex; /* ใช้ Flexbox layout */
                max-width: 50%; /* กำหนดความกว้างสูงสุดของสไลด์โชว์ */
                margin: 0 auto; /* จัดการความกว้างเพื่อให้สไลด์โชว์อยู่กลางหน้าจอ */
                overflow: hidden; /* กำหนดให้สไลด์โชว์ซ่อนส่วนที่เกินขอบเขต */
                padding: 10px; /* เพิ่มระยะห่างด้านในของสไลด์โชว์ */
            }

            .slideshow img {
                max-width: 100%; /* กำหนดขนาดสูงสุดของรูปภาพเป็น 100% ของพื้นที่ */
                max-height: 100%; /* กำหนดความสูงสูงสุดของรูปภาพเป็น 100% ของพื้นที่ */
            }
        </style>

        <div class="slideshow">
            <?php 
                $sql = "SELECT * FROM promotions";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    echo '<a href="detailpromotion.php?id=' . $row['id'] . '"><img src="img/' . $row['image'] . '"></a>';
                }
            ?>
        </div>

        <script>
            $(document).ready(() => {
                $('.slideshow').slick({
                    autoplay: true,
                    autoplaySpeed: 2000,
                    infinite: true,
                    speed: 2000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    prevArrow: false,
                    nextArrow: false,
                });
            });
                     
            // หากต้องการหยุดการเลื่อนอัตโนมัติ เมื่อเมาส์เข้าไปบนสไลด์
            document.querySelector('.scroll-container').addEventListener('mouseenter', function() {
                this.querySelector('.scroll-content').style.animationPlayState = 'paused';
            });

            // เมื่อเมาส์ออกจากสไลด์ ให้เล่นการเลื่อนอีกครั้ง
            document.querySelector('.scroll-container').addEventListener('mouseleave', function() {
                this.querySelector('.scroll-content').style.animationPlayState = 'running';
            });
        </script>

        <h4>หนังสือ บันเทิงและท่องเที่ยว</h4>
              
        <div class="row justify-content-start text-left">
            <?php 
            // ประกาศประเภทที่คุณต้องการแสดง
            $selected_type = "004"; // ประเภท "บันเทิงและท่องเที่ยว"

            $sql = "SELECT * FROM stocks WHERE typebook_id = '$selected_type'";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_array($result)){
            ?>
            
            <div class="col-sm-3"> 
                <div>
                    <img src="img/<?=$row['image']?>" width="200px" height="250" class="mx-auto mt-3 p-2 my-2 border"> <br>
                    <h5 class="text-success"><?=$row['book_name']?> </h5> 
                    Price:<b class="text-danger"> <?=$row['price']?> </b> Bath <br>
                    Author: <?=$row['author_name']?> <br>
                    <a class="btn btn-outline-info mt-3" href ="detail.php?id=<?=$row['book_id']?>" > รายละเอียด </a>
                    <a class="btn btn-outline-info mt-3" href ="order.php?id=<?=$row['book_id']?>" > เพิ่มลงในตระกร้า </a>
                </div>
                <br>
            </div>

            <?php
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>

</html>