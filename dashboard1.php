<?php
// Establish connection to MySQL database
$con = mysqli_connect("localhost", "root", "", "data_db");
    
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Query to retrieve gender distribution data
$query = "SELECT sex, COUNT(*) AS count FROM history GROUP BY sex";

$result = mysqli_query($con, $query);

// Create arrays to store gender and count data
$sexes = array();
$counts = array();

// Fetch data from database and store in arrays
while ($row = mysqli_fetch_assoc($result)) {
    $sexes[] = $row['sex'];
    $counts[] = $row['count'];
}

// Close database connection
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>ตัวอย่างกราฟวงกลม</title>
    <!-- Import necessary JavaScript libraries -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<!-- Create div to display the pie chart -->
<div id="piechart"></div>

<script type="text/javascript" >
    // Load Google Charts and initiate callback function
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function(){
        // Create data array to hold gender distribution data
        var data = [];
        data.push(['Sex', 'Count']);

        // Add gender and count data from PHP arrays to the data array
        <?php
        for ($i = 0; $i < count($sexes); $i++) {
            echo "data.push(['" . $sexes[$i] . "', " . $counts[$i] . "]);\n";
        }
        ?>

        // Define options for the pie chart
        var options = {
            'title': 'Gender Distribution',
            'width': 650,
            'height': 400,
            sliceVisibilityThreshold: .00001
        };

        // Create pie chart and draw it in the div with id "piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(google.visualization.arrayToDataTable(data), options);
    });
</script>

</body>
</html>
