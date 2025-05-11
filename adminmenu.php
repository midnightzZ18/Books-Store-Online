<nav class="navbar navbar-expand-lg navbar-light bg-light custom-navbar" style="background-color: #87CEFA!important;">
  <div class="container d-flex justify-content-between">
    <a class="navbar-brand" href="admin.php">ADMIN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-0 mb-lg-0">
    
          <li class="nav-item dropdown">
          <a class="nav-link " href="confirm_order.php"  role="button"  aria-expanded="false" style="color: #000000;">
            คำสั่งซื้อ
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link " href="admin_order.php"  role="button"  aria-expanded="false" style="color: #000000;">
           รายการจัดส่ง
          </a>
        </li>

      <li class="nav-item dropdown">
          <a class="nav-link " href="admin_history.php"  role="button"  aria-expanded="false" style="color: #000000;">
          ประวัติการขาย
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link " href="dashboard.php"  role="button"  aria-expanded="false" style="color: #000000;">
           Dashboard
          </a>
        </li>
      </ul>
      <form class="d-flex" method = "POST" action= "admin.php">
          <input class="form-control me-2" type="search" name = "keyword" placeholder="ค้นหา" aria-label="Search" >
          <button class="btn btn-outline-success" type="submit" style="color: #000000;">ค้นหา</button>
          
      <ul class="navbar-nav me-auto mb-0 mb-lg-0">
      <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="stock.php" style="color: #000000;">คลังสินค้า</a>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="data_user.php" style="color: #000000;">ข้อมูลUser</a>
        </li>
       
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="logout.php" style="color: #000000;">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>