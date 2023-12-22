<?php
session_start();
require_once("config.php");
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

<?php if ($_SESSION["role"] == "Student") : ?>
<?php 
$db = get_pdo_connection();
$query = $db->prepare("SELECT * FROM Student");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

$classQuery = $db->prepare("call GetStudentClasses(?)");
$classQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_STR);
$classQuery->execute();
$classData = $classQuery->fetchAll(PDO::FETCH_ASSOC);
?>

    <h2>Your Classes</h2>
<?php
    if (empty($classData))
        echo "<p>You are not enrolled in any classes at this time.</p>";
    else echo makeTable($classData);
?>

<a href="enroll.php">Enroll in classes</a>

<?php else : ?>
<?php
    echo "<h2>Error: wrong data for current session</h2>";
?>
<?php endif; ?>
</body>
</html>
