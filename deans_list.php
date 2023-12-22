
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

<h2>Get Dean's List Information</h2>
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

<?php if (!empty($_POST["search_school"]) && !empty($schoolInfo)) : ?>
<?php
    $deansListQuery = $db->prepare("select Student, GPA from DeansList where SchoolName = ?");
    $deansListQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
    $deansListQuery->execute();
    $deansListers = $deansListQuery->fetchAll(PDO::FETCH_ASSOC);
    $deansListQuery->closeCursor();

    $deansList12Query = $db->prepare("select Student, GPA from DeansListSeniors where SchoolName = ?");
    $deansList12Query->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
    $deansList12Query->execute();
    $deansListers12 = $deansList12Query->fetchAll(PDO::FETCH_ASSOC);
    $deansList12Query->closeCursor();
?>
<h2>View Dean's List Information: <?=$_POST["school_name"]?></h2>

<p>Valedictorian: <?=$deansListers12[0]["Student"]?></p>
<p>Salutatorian: <?=$deansListers12[1]["Student"]?></p>
<h3> Dean's List - Seniors</h3>
<?php echo makeTable($deansListers12); ?>
<h3>Dean's List - All Grades</h3>
<?php echo makeTable($deansListers); ?>


<?php elseif (isset($_POST["search_school"])) : ?>
<p>Error retrieving Dean's List information</p>   
<?php endif ; ?>
</body>
</html>

