<?php
session_start();
require_once("config.php");
$_SESSION["pick"] = "Teacher";
$teacherInfo = "";
$advInfo = "";
$myClasses = "";
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

$db = get_pdo_connection();

$initialQuery = $db->prepare("select * from Teacher where TeacherID = ?");
$initialQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
$initialQuery->execute();
$teacherInfo = $initialQuery->fetchAll(PDO::FETCH_ASSOC);
$initialQuery->closeCursor();

$advancedQuery = $db->prepare("call GetAllTeacherStudentData(?)");
$advancedQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
$advancedQuery->execute();
$advInfo = $advancedQuery->fetchAll(PDO::FETCH_ASSOC);
$advancedQuery->closeCursor();

$myClassQuery = $db->prepare("select ClassID as Classes from ClassInSchool where TeacherID = ?");
$myClassQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
$myClassQuery->execute();
$myClasses = $myClassQuery->fetchAll(PDO::FETCH_ASSOC);
$myClassQuery->closeCursor();

?>


    <h2>Welcome, <?=$teacherInfo[0]["FirstName"]?> <?=$teacherInfo[0]["LastName"]?></h2>
    
    <h3>General Information</h3>
    <ul>
        <li>School: <?=$teacherInfo[0]["SchoolName"]?></li>
        <li>Satisfaction Level: <?=$teacherInfo[0]["SatisfactionLevel"]?> out of 10</li>
        <li>Date Hired: <?=$teacherInfo[0]["DateHired"]?></li>
        <li>Salary: $<?=$teacherInfo[0]["Pay"]?></li>
    </ul>

    <h3>Student Statistics</h3>
    <ul>
        <li>Aggregate Student Count in All Classes: <?=$advInfo[0]["StudentCount"]?></li>
        <li>Average Class Percentge for All Classes: <?=round($advInfo[0]["AveragePercentage"], 2)?></li>
    </ul>

    <h3>Your Classes</h3>
    <?php echo makeTable($myClasses); ?>
       
</body>
</html>
