<?php
require_once("config.php");
$_SESSION["pick"] = "District";
$search_district_name = "";
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
    <?php include("nav.php"); ?>
    <?php include("sidebar.php"); ?>
    <?php

    $db = get_pdo_connection();
    $district_query = $db->prepare("SELECT * FROM District");
    $district_query->execute();
    $district_rows = $district_query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h2>Search for a District</h2>

    <?php
    $select_district_form = new PhpFormBuilder();
    $select_district_form->set_att("method", "POST");
    $select_district_form->add_input("District Name: ", array(
        "type" => "text"
    ), "search_district_name");
    $select_district_form->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search_district");
    $select_district_form->build_form();
    ?>

    <?php
    if (isset($_POST["search_district"])) {
        // Handle search by district name
        $search_district_name = $_POST["search_district_name"];
        
        // Show the distrcit querys with this function below:
        $district_query = $db->prepare("SELECT * FROM District WHERE Name = :name");
        $district_query->bindParam(':name', $search_district_name, PDO::PARAM_STR);
        $district_query->execute();
        $district_results = $district_query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($district_results)) {
            $teacher_data = $db->prepare("call HarvestDistrictTeacherData(?)");
            $teacher_data->bindParam(1, $_POST["search_district_name"], PDO::PARAM_STR);
            $teacher_data->execute();
            $tData = $teacher_data->fetchAll(PDO::FETCH_ASSOC);
            $teacher_data->closeCursor();

            $student_data = $db->prepare("call HarvestDistrictStudentData(?)");
            $student_data->bindParam(1, $_POST["search_district_name"], PDO::PARAM_STR);
            $student_data->execute();
            $sData = $student_data->fetchAll(PDO::FETCH_ASSOC);
            $student_data->closeCursor();
        }
    }
?>
<?php if (!empty($district_results)) : ?>
<h2>View District Information: <?=$_POST["search_district_name"]?></h2>

<h3>Student Statistics</h3>

<ul>
<li>Student Body Count: <?=$sData[0]["StudentCount"]?></li>
<li>Average GPA: <?=$sData[0]["AverageGPA"]?></li>
<li>Average Hardship: <?=$sData[0]["AvgHardship"]?></li>
<li>Average number of suspensions: <?=$sData[0]["AvgSuspensions"]?></li>
<li>Average number of detentions: <?=$sData[0]["AvgDetentions"]?></li>
<li>Average number of absences: <?=$sData[0]["AvgAbsences"]?></li>
</ul>

<h3>Teacher Statistics</h3>

<ul>
<li>Teacher Count: <?=$tData[0]["TeacherCount"]?></li>
<li>Average Degree of Satisfaction: <?=$tData[0]["AvgSatisfaction"]?></li>
<li>Average Pay: $<?=$tData[0]["AvgPay"]?></li>
</ul>
<?php else : ?>
<?php 
if (isset($_POST["search_district"])) echo "<p>Error: district search not valid</p>";?> 
<?php endif; ?>
</body>
</html>
