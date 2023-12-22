<?php
session_start();
require_once("config.php");
$_SESSION["pick"] = "Class";
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
<?php
        include("sidebar.php");
        include("nav.php");
?>

<h2>Search a Class</h2>

<?php
        $classInfo = "";
        $db = get_pdo_connection();

        $get_class_form = new PhpFormBuilder();
        $get_class_form->set_att("method", "POST");
        $get_class_form->add_input("School Name: ", array(
            "type" => "text"
        ), "school_name");
        $get_class_form->add_input("Class ID: ", array(
            "type" => "text"
        ), "class_id");
        $get_class_form->add_input("Submit", array(
            "type" => "submit",
            "value" => "Search"
        ), "search_class_in_school");
        $get_class_form->build_form();

        if (isset($_POST["search_class_in_school"])) {
            if (!empty($_POST["school_name"])) {
                $getClassQuery = $db->prepare("call GetSpecificClassInSchool(?, ?)");
                $getClassQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
                $getClassQuery->bindParam(2, $_POST["class_id"], PDO::PARAM_STR);
                if (!$getClassQuery->execute()) {
                    echo "<p>Error retrieving class information: </p>" . print_r($getClassQuery->errorInfo());
                } else {
                    echo "<p>Retrieving data...</p>";
                    $classInfo = $getClassQuery->fetchAll(PDO::FETCH_ASSOC);
                    $getClassQuery->closeCursor();
                }
                if ($classInfo[0]["ClassID"] == NULL) {
                    $zeroQuery = $db->prepare("call GetClassWithNoStudents(?, ?)");
                    $zeroQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
                    $zeroQuery->bindParam(2, $_POST["class_id"], PDO::PARAM_STR);
                    if (!$zeroQuery->execute()) {
                        echo "<p>Error retrieving class information: </p>" . print_r($getClassQuery->errorInfo());
                    } else {
                        $zeroClassInfo = $zeroQuery->fetchAll(PDO::FETCH_ASSOC);
                        $zeroQuery->closeCursor();
                    }
                }
            } else echo "<p>You must enter a school name</p>";
        }



?>
<?php if (!empty($classInfo[0]["ClassID"])) : ?>
<h2>Class Information: <?=$classInfo[0]["ClassID"]?></h2>
<p>Class ID: <?=$classInfo[0]["ClassID"]?></p>
<p>Teacher: <?=$classInfo[0]["Teacher"]?></p>
<p>Student Count: <?=$classInfo[0]["StudentCount"]?></p>
<p>Average Percentage: <?=$classInfo[0]["AvgPercentage"]?>%</p>
<p>Is It An Honors/AP Class? 
<?php 
        if ($classInfo[0]["isHonorsOrAP"] == 1) echo "Yes</p>";
        else echo "No</p>";
?>
<h2>Student Roster</h2>
<?php
        $listQuery = $db->prepare("call GetClassStudents(?, ?)");
        $listQuery->bindParam(1, $classInfo[0]["ClassID"], PDO::PARAM_STR);
        $listQuery->bindParam(2, $classInfo[0]["TeacherID"], PDO::PARAM_INT);
        $listQuery->execute();
        $listOfStudents = $listQuery->fetchAll(PDO::FETCH_ASSOC);
        $listQuery->closeCursor();
        echo makeTable($listOfStudents);

        unset($_POST["search_school"]);
?>
<?php elseif (!empty($zeroClassInfo[0]["ClassID"])) : ?>
<h2>Class Information: <?=$zeroClassInfo[0]["ClassID"]?></h2>
<p>Class ID: <?=$zeroClassInfo[0]["ClassID"]?></p>
<p>Teacher: <?=$zeroClassInfo[0]["Teacher"]?></p>
<p>Is It An Honors/AP Class? 
<?php 
        if ($zeroClassInfo[0]["isHonorsOrAP"] == 1) echo "Yes</p>";
        else echo "No</p>";
?>
<?php elseif (isset($_POST["search_class_in_school"])) : ?>
<?php
?>
        <p>There is no class with the provided credentials.</p>
<?php endif; ?>
</body>
</html>
