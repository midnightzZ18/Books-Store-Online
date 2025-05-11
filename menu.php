
<nav class="navbar navbar-expand-lg navbar-light bg-light custom-navbar" style="background-color: #FFCCCC!important;">
  <div class="container d-flex justify-content-between">
    <a class="navbar-brand" href="shop.php">Online Book Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-0 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000000;">
            ประเภทหนังสือ
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="self_dev.php">การพัฒนาตนเอง</a></li>
            <li><a class="dropdown-item" href="literature.php">วรรณกรรม</a></li>
            <li><a class="dropdown-item" href="Food_health.php">อาหารและสุขภาพ</a></li>
            <li><a class="dropdown-item" href="Entertain_travel.php">บันเทิงและท่องเที่ยว</a></li>
            <li><a class="dropdown-item" href="anime.php">การ์ตูน มังงะ</a></li>
          </ul>
        </li>
      </ul>

      <form class="d-flex" method = "POST" action= "shop.php">
          <input class="form-control me-2" type="search" name = "keyword" placeholder="ค้นหา" aria-label="Search" >
          <button class="btn btn-outline-success" type="submit" style="color: #000000;">ค้นหา</button>
          
           
      </form>
      <ul class="navbar-nav me-auto mb-0 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="cart.php" style="color: #000000;">ตะกร้าสินค้า</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="user.php" style="color: #000000;">ข้อมูลของฉัน</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index_coupon.php" style="color: #000000;">คูปอง</a>
        </li>
      
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="logout.php" style="color: #000000;">ล็อกเอ้าท์</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
