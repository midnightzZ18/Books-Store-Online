<style>
    /* เอฟเฟกต์ Hover สำหรับเมนู Navbar */
    .custom-navbar .nav-link {
        transition: all 0.2s ease;
        border-radius: 0.25rem;
        padding: 8px 12px !important;
        margin: 0 2px;
    }

    .custom-navbar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.3); /* สีขาวจางๆ เมื่อ Hover */
        color: #000 !important;
        transform: translateY(-1px);
    }

    /* จัดการช่องค้นหาให้ปุ่มและช่องกรอกติดกันสวยงาม */
    .search-group {
        max-width: 300px;
    }

    .search-group .form-control {
        border-radius: 50px 0 0 50px !important; /* มนซ้าย */
        border: 1px solid #ced4da;
    }

    .search-group .btn {
        border-radius: 0 50px 50px 0 !important; /* มนขวา */
        border: 1px solid #ced4da;
        border-left: none; /* ลบเส้นคั่นกลาง */
        background-color: #fff;
        color: #000;
        transition: all 0.3s ease;
    }

    .search-group .btn:hover {
        background-color: #28a745 !important; /* เปลี่ยนเป็นสีเขียวเมื่อ Hover */
        color: #fff !important;
        border-color: #28a745;
    }

    /* ตกแต่งตัวอักษร Brand */
    .navbar-brand {
        font-weight: bold;
        letter-spacing: 1px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light custom-navbar" style="background-color: #87CEFA !important; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand" href="admin.php">ADMIN</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="confirm_order.php" style="color: #000000;">คำสั่งซื้อ</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="admin_history.php" style="color: #000000;">ประวัติการขาย</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)" onclick="checkPrivateAccess()" style="color: #000000;">รายการจัดส่ง</a>
        </li>

        <script>
        function checkPrivateAccess() {
            var password = prompt("Top Secret !!!!!");
            if (password === "midnight") { 
                window.location.href = "admin_order.php";
            } else if (password !== null) {
                alert("รหัสผ่านไม่ถูกต้อง!");
            }
        }
</script>

        <li class="nav-item">
          <a class="nav-link" href="dashboard.php" style="color: #000000;">Dashboard</a>
        </li>
      </ul>

      <form class="d-flex search-group me-lg-3" method="POST" action="admin.php">
        <div class="input-group">
          <input class="form-control" type="search" name="keyword" placeholder="ค้นหาหนังสือ..." aria-label="Search">
          <button class="btn btn-outline-success" type="submit">ค้นหา</button>
        </div>
      </form>

      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link" href="stock.php" style="color: #000000;">คลังสินค้า</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="data_user.php" style="color: #000000;">ข้อมูลผู้ใช้</a>
        </li>
        <li class="nav-item ms-lg-2">
            <a class="btn nav-link px-3" href="logout.php" 
              style="background-color: #f69988 !important; color: #ffffff !important; font-weight: bold; border-radius: 20px; border: none;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>