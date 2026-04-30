<style>
    /* เอฟเฟกต์ Hover สำหรับเมนู */
    .custom-navbar .nav-link {
        transition: all 0.2s ease;
        border-radius: 8px;
        padding: 8px 15px !important;
    }
    .custom-navbar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.5);
        color: #d81b60 !important; /* สีชมพูเข้มตอน hover */
    }

    /* ช่องค้นหาแบบมน (Capsule) */
    .search-group-user .form-control {
        border-radius: 50px 0 0 50px;
        border: 1px solid #ffb6c1;
    }
    .search-group-user .btn {
        border-radius: 0 50px 50px 0;
        background-color: #ffb6c1;
        color: white;
        border: 1px solid #ffb6c1;
    }
    .search-group-user .btn:hover {
        background-color: #ff99aa;
        color: white;
    }

    /* ปุ่ม Logout สีชมพูเข้ม */
    .btn-logout-user {
        background-color: #f8bbd0 !important;
        color: #ad1457 !important;
        font-weight: bold;
        border-radius: 20px;
        border: none;
        transition: 0.3s;
    }
    .btn-logout-user:hover {
        background-color: #f48fb1 !important;
        transform: scale(1.05);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light custom-navbar" style="background-color: #FFCCCC !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="shop.php" style="color: #ad1457;">🌸 BOOK STORE</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
            ประเภทหนังสือ
          </a>
          <ul class="dropdown-menu border-0 shadow">
            <li><a class="dropdown-item" href="self_dev.php">การพัฒนาตนเอง</a></li>
            <li><a class="dropdown-item" href="literature.php">วรรณกรรม</a></li>
            <li><a class="dropdown-item" href="Food_health.php">อาหารและสุขภาพ</a></li>
            <li><a class="dropdown-item" href="Entertain_travel.php">บันเทิงและท่องเที่ยว</a></li>
            <li><a class="dropdown-item" href="anime.php">การ์ตูน มังงะ</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link text-dark" href="cart.php">ตะกร้าสินค้า</a></li>
        <li class="nav-item"><a class="nav-link text-dark" href="index_coupon.php">คูปอง</a></li>
      </ul>

      <form class="d-flex search-group-user me-3" method="POST" action="shop.php">
        <div class="input-group">
          <input class="form-control" type="search" name="keyword" placeholder="ค้นหาชื่อหนังสือ..." aria-label="Search">
          <button class="btn" type="submit">ค้นหา</button>
        </div>
      </form>

      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a class="nav-link text-dark" href="user.php">ข้อมูลของฉัน</a></li>
        <li class="nav-item ms-lg-2">
            <a class="btn btn-logout-user px-3 py-1" href="logout.php">ออกจากระบบ</a>
        </li>
      </ul>
    </div>
  </div>
</nav>