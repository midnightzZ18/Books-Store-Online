<?php 
    header('Content-Type: application/json');
    require 'condb.php';
    $sqlQuery = "SELECT * FROM buybook ORDER BY id";
    $result = mysqli_query($conn, $sqlQuery);

    $data = array();

    foreach($result as $row) {
        $data[] = $row;
    }
    mysqli_close($conn);
    echo json_encode($data);
?>
