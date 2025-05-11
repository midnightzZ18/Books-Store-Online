<?php
    @include 'condb.php';
    session_start();
    if(!isset($_SESSION['admin_name'])){
        header('location:login_form.php');
    }

    // Check if the id parameter is set
    if(isset($_GET['id'])) {
        $book_id = $_GET['id'];

        // Retrieve the book details from the database
        $sql = "SELECT * FROM stocks WHERE book_id = $book_id";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "ไม่พบข้อมูลหนังสือ";
            exit();
        }
    } else {
        echo "ไม่พบรหัสหนังสือ";
        exit();
    }

    // Check if the form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $bookName = $_POST['bookname'];
        $price = $_POST['price'];
        $authorName = $_POST['authorname'];
        $detail = $_POST['detail'];
        $typebookid = $_POST['typebookid'];
        $image = $_POST['image']; // assuming you're storing image path in database
        $amount = $_POST['amount'];

        // Update the book details in the database
        $sql = "UPDATE stocks SET 
                book_name = '$bookname', 
                price = '$price', 
                author_name = '$authorname', 
                detail = '$detail', 
                typebook_id = '$typebookid',
                image = '$image',
                amount = '$amount'
                WHERE book_id = $book_id";
        if(mysqli_query($conn, $sql)) {
            echo "แก้ไขข้อมูลสำเร็จ";
            header("Location: admin.php");
    exit; // Stop further execution
        } else {
            echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
    <?php include 'adminmenu.php'; ?>
    <div class="container">
        <br><br>
        <div class="col-md-11 black-border-frame"></div> <br>
        <h4>Edit Book</h4>
        <form method="post">
        <div class="form-group">
                <label for="image">Image:</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($row['image']); ?>">
            </div>
            <div class="form-group">
                <label for="bookName">Book Name:</label>
                <input type="text" class="form-control" id="bookName" name="bookName" value="<?php echo htmlspecialchars($row['book_name']); ?>">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">
            </div>
            <div class="form-group">
                <label for="authorName">Author Name:</label>
                <input type="text" class="form-control" id="authorName" name="authorName" value="<?php echo htmlspecialchars($row['author_name']); ?>">
            </div>
            <div class="form-group">
                <label for="detail">รายละเอียด:</label>
                <textarea class="form-control" id="detail" name="detail" rows="3"><?php echo htmlspecialchars($row['detail']); ?></textarea>
            </div> 
            <div class="form-group">
                <label for="typebookid">ประเภท:</label>
                <input type="text" class="form-control" id="typebookid" name="typebookid" value="<?php echo htmlspecialchars($row['typebook_id']); ?>">
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($row['amount']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
