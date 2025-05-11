<?php
      $con= mysqli_connect("localhost","root","12345678","data_db") or die("Error: " . mysqli_error($con));
      mysqli_query($con, "SET NAMES 'utf8' ");
      error_reporting( error_reporting() & ~E_NOTICE );
      date_default_timezone_set('Asia/Bangkok');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Addrss</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php
    $sql_provinces = "SELECT * FROM provinces";
    $query = mysqli_query($con, $sql_provinces);
?>

<div class="container">
  <h2>ระบุจังหวัดที่ท่านอยู่เพื่อให้สินค้าได้อยู่ในมือท่าน</h2>
  <p>กรอกข้อมูลตรงนี้เลย!!</p>
  <form>
    <div class="form-group">
      <label for="sel1">จังหวัด:</label>
      <select class="form-control" name="Ref_prov_id" id="provinces">
            <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
             <?php foreach ($query as $value) { ?>
            <option value="<?=$value['id']?>"><?=$value['name_th']?></option>
            <?php } ?>
      </select>
      <br>

      <label for="sel1">อำเภอ:</label>
      <select class="form-control" name="Ref_dist_id" id="amphures">
      </select>
      <br>

      <label for="sel1">ตำบล:</label>
      <select class="form-control" name="Ref_subdist_id" id="districts">
      </select>
      

      <label for="sel1">รหัสไปรษณีย์:</label>
       <input type="text" name="zip_code" id="zip_code" class="form-control">
          <br>
        <a href="cart.php"> <button type="button" class="btn btn-primary btn-lg btn-block">ระบุจังหวัดเสร็จแล้วไปหน้าต่อไปกันเลย!</button></a>
    </div>
  </form>
</div>
</body>
</html>
<?php include('script.php');?>