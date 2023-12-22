
<?php
require_once("config.php");
$_SESSION["pick"] = "School";
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

    <?php

$db = get_pdo_connection();
$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); 
?>

<h2>Get School Information</h2>
    <?php
    $select_school_form = new PhpFormBuilder();
    $select_school_form->set_att("method", "POST");
    $select_school_form->add_input("School Name: ", array(
        "type" => "text"
    ), "school_name");
    $select_school_form->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search_school");
    $select_school_form->build_form();  
    if (isset($_POST["search_school"])) {
        if (!empty($_POST["school_name"])) {
            $getSchoolQuery = $db->prepare("select * from School where Name = ?");
            $getSchoolQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
            if (!$getSchoolQuery->execute()) echo "<p>Error retrieving school information</p>";
            $schoolInfo = $getSchoolQuery->fetchAll(PDO::FETCH_ASSOC); 
        } else echo "<p>You must enter a school name</p>";
    }
?>

<?php if (!empty($_POST["school_name"]) && !empty($schoolInfo) && isset($_POST["search_school"])) : ?>
<?php
    $countQuery = $db->prepare("call GetStudentCountInSchool(?)");
    $countQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
    $countQuery->execute();
    $studentCount = $countQuery->fetchAll(PDO::FETCH_ASSOC);
    $countQuery->closeCursor();

    $persQuery = $db->prepare("call GetAveragePercentageInSchool(?)");
    $persQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
    if (!$persQuery->execute()) echo "<p>Error executing query</p>" . print_r($persQuery->errorInfo());
    $percentage = $persQuery->fetchAll(PDO::FETCH_ASSOC);
    $percentage[0]["AveragePercentage"] = round($percentage[0]["AveragePercentage"], 2);
    $persQuery->closeCursor();

    $gpaQuery = $db->prepare("call GetAverageGPAInSchool(?)");
    $gpaQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
    if (!$gpaQuery->execute()) echo "<p>Error executing GPA query: </p>" . print_r($gpaQuery->errorInfo());
    $avgGPA = $gpaQuery->fetchAll(PDO::FETCH_ASSOC);
    $avgGPA[0]["AverageGPA"] = round($avgGPA[0]["AverageGPA"], 2);
    $gpaQuery->closeCursor();
  ?>
<h2>School Information: <?=$schoolInfo[0]["Name"]?></h2>

<p>Name: <?=$schoolInfo[0]["Name"]?></p>
<p>District: <?=$schoolInfo[0]["DistrictName"]?></p>
<p>Student Body Count: <?=$studentCount[0]["StudentCount"]?></p>
<p>Average Class Percentage: <?=$percentage[0]["AveragePercentage"]?>%</p>
<p>Average GPA: <?=$avgGPA[0]["AverageGPA"]?></p>

<?php unset($_POST["search_school"]); ?>

<?php else : ?>
<?php
    if (isset($_POST["search_school"]) && !empty($_POST["school_name"])) echo "<p>The school entered does not exist</p>";
?>
<?php endif ; ?>
</body>
</html>

