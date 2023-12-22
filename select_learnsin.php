<?php
require_once("config.php");
$db = get_pdo_connection();

// This will deal with all the submission forms below 
if (isset($_POST["search_learns_in"])) {
    $studentId = filter_var($_POST["search_learns_in_student_id"], FILTER_VALIDATE_INT);
    $classId = filter_var($_POST["search_learns_in_class_id"], FILTER_VALIDATE_INT);

    if ($studentId !== false && $classId !== false) {
        $learnsInQuery = $db->prepare("SELECT * FROM Learns_In WHERE StudentID = :studentId AND ClassID = :classId");
        $learnsInQuery->bindParam(":studentId", $studentId, PDO::PARAM_INT);
        $learnsInQuery->bindParam(":classId", $classId, PDO::PARAM_INT);
        $learnsInQuery->execute();

        $learnsInRows = $learnsInQuery->fetchAll(PDO::FETCH_ASSOC);

        // This will pop the resuults below 
        if (!empty($learnsInRows)) {
            $resultsMessage = "<h3>Results:</h3>
                <table>
                    <tr><th>StudentID</th><th>ClassID</th><th>Percentage</th></tr>";
            foreach ($learnsInRows as $row) {
                $resultsMessage .= "<tr><td>" . htmlspecialchars($row['StudentID']) . "</td><td>" . htmlspecialchars($row['ClassID']) . "</td><td>" . htmlspecialchars($row['Percentage']) . "</td></tr>";
            }
            $resultsMessage .= "</table>";
        } else {
            $resultsMessage = "<p>No results found.</p>";
        }
    } else {
        $resultsMessage = "<p>Invalid input. Please enter valid numeric values.</p>";
    }
}
?>


<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= $PROJECT_NAME ?></h1>
    <?php include("sidebar.php");?>
    <?php include("nav.php"); ?>
    <?php include("style.css"); ?>
    <!-- Learns_In Search Form -->
    <h2>Search for Learns_In</h2>
    <?php
    $selectLearnsInForm = new PhpFormBuilder();
    $selectLearnsInForm->set_att("method", "POST");
    $selectLearnsInForm->add_input("Student ID: ", array("type" => "number"), "search_learns_in_student_id");
    $selectLearnsInForm->add_input("Class ID: ", array("type" => "number"), "search_learns_in_class_id");
    $selectLearnsInForm->add_input("Submit", array("type" => "submit", "value" => "Search"), "search_learns_in");
    $selectLearnsInForm->build_form();
    
  
    echo $resultsMessage;
    ?>

</body>
</html>

