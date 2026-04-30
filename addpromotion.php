<?php
    include 'condb.php';
    session_start();
    
    if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] != 'admin') {
        header('location:login_form.php');
        exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Promotion</title>
    <link rel="icon" type="image/png" href="img/logo-web.png">
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><br>
        <h2 class="text-center">Add Promotion</h2>
        <!-- Display error message if exists -->
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <form action="addpromotion_process.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="promotion_name">Promotion Name:</label>
                <input type="text" class="form-control" id="promotion_name" name="promotion_name" required>
            </div>
            <div class="form-group">
                <label for="promoStartDate">Start Date:</label>
                <input type="date" class="form-control" id="promoStartDate" name="promoStartDate" required>
            </div>
            <div class="form-group">
                <label for="promoEndDate">End Date:</label>
                <input type="date" class="form-control" id="promoEndDate" name="promoEndDate" required>
            </div>
            <div class="form-group">
                <label for="detail">Detail:</label>
                <textarea class="form-control" id="detail" name="detail" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            </div><br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Promotion Details -->
        <div class="row">
            <?php
            $sql = "SELECT * FROM promotions ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
            ?>
                <div class="col-md-4">
                    <br>
                    <img src="img/<?= $row['image'] ?>" width="350px" class="mt-2 p-2 my-2 border" />
                    <div class="text-center">
                        <p><?= $row['promotion_name'] ?></p>
                        <p><?= $row['detail'] ?></p>
                        <div>
                            <a href="edit_promotion.php?id=<?= $row['id'] ?>" class="btn btn-info">Edit</a>
                            <a href="delete_promotion.php?id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>
