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
?>

<h2>Enroll In Classes</h2>
<?php
$enroll_form = new PhpFormBuilder();
$enroll_form->set_att("method", "POST");
$enroll_form->add_input("Class ID", array(
    "type" => "text"
), "class_id");
$enroll_form->add_input("Enroll", array(
    "type" => "submit",
    "value" => "Enroll"
), "enroll");
$enroll_form->build_form();

if (isset($_POST["enroll"])) {
    if (!empty($_POST["class_id"])) {
        echo "<p>Enrolling in class...</p>";
        $enrollQuery = $db->prepare("call Enroll(?, ?, null)");
        $enrollQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_STR);
        $enrollQuery->bindParam(2, $_POST["class_id"], PDO::PARAM_STR);
        if (!$enrollQuery->execute()) echo "<p>Error entering a class</p>";
        else echo "<p>Successfully entered a class</p>";
    } else echo "<p>You must enter a value.</p>";

    unset($_POST["enroll"]);
} 
?>
<?php else : ?>
<?php
    echo "<h2>Error: wrong data for current session</h2>";
?>
<?php endif; ?>
</body>
</html>
