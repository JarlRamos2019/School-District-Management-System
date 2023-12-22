<?php
session_start();
require_once("config.php");
$_SESSION["pick"] = "Student";
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
    <?php

$db = get_pdo_connection();
$query = $db->prepare("SELECT * FROM Student");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

//echo makeTable($rows);
?>
<?php if ($_SESSION["role"] == "Administration") : ?>
<h2>Search for a Student</h2>
<?php
$select_form = new PhpFormBuilder();
$select_form->set_att("method", "POST");
$select_form->add_input("Student ID: ", array(
    "type" => "number"
), "search_id");
$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search");
$select_form->build_form();
?>
<p>OR</p>
<?php
$select_form_name = new PhpFormBuilder();
$select_form_name->set_att("method", "POST");
$select_form_name->add_input("First Name: ", array(
    "type" => "text"
), "search_fn");
$select_form_name->add_input("Last Name: ", array(
    "type" => "text"
), "search_ln");
$select_form_name->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search_name");
$select_form_name->build_form();

$select_form_all = new PhpFormBuilder();
$select_form_all->set_att("method", "POST");
$select_form_all->add_input("Submit", array(
    "type" => "submit",
    "value" => "Full Student Roster"
), "search_all");
$select_form_all->build_form();

$db = get_pdo_connection();
$query = false;

if (isset($_POST["search"])) {
    if (!empty($_POST["search_id"])) {
        echo "searching by Student ID...";
        $query = $db->prepare("select * from Student where StudentID = :id");
        $query->bindParam(":id", $_POST["search_id"], PDO::PARAM_STR);
    }
    if ($query) {
        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            echo makeTable($rows);
            if (empty($rows)) echo "Sorry, there are no students with the credentials provided.";
        } else {
            echo "Error executing select query:<br>";
            print_r($query->errorInfo());
        }      
    } else echo "Error executing select query: no Student ID specified<br>";

    unset($_POST["search"]);

} else if (isset($_POST["search_name"])) {
    if (!empty($_POST["search_fn"]) && !empty($_POST["search_ln"])) {
        echo "searching by Student Name...";
        $query = $db->prepare("select * from Student where FirstName = ? and LastName = ?");
        $query->bindParam(1, $_POST["search_fn"], PDO::PARAM_STR);
        $query->bindParam(2, $_POST["search_ln"], PDO::PARAM_STR);
    }
    if ($query) {
        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            echo makeTable($rows);
            if (empty($rows)) echo "Sorry, there are no students with the credentials provided.";
        } else {
            echo "Error executing select query:<br>";
            print_r($query->errorInfo());
        }
    } else echo "Error executing select query: no full name specified<br>";

    unset($_POST["search_name"]);
} else if (isset($_POST["search_all"])) {
    echo "Retrieving all student information...";
    $allQuery = $db->prepare("select * from Student");
    if (!$allQuery->execute()) echo "Error executing select query:<br>" . print_r($allQuery->errorInfo());
    $resultAll = $allQuery->fetchAll(PDO::FETCH_ASSOC);
    echo makeTable($resultAll);
    unset($_POST["search_all"]);
}
?>
<?php elseif ($_SESSION["role"] == "Faculty") : ?>
<h2>Search for a Student</h2>
<?php
$select_form_fac = new PhpFormBuilder();
$select_form_fac->set_att("method", "POST");
$select_form_fac->add_input("Student ID: ", array(
    "type" => "number"
), "search_id_fac");
$select_form_fac->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search_fac");
$select_form_fac->build_form();
$studentData = "";
if (isset($_POST["search_fac"])) {
    if (!empty($_POST["search_id_fac"])) {
        $idQuery = $db->prepare("call GetStudentFromClasses(?, ?)");
        $idQuery->bindParam(1, $_POST["search_id_fac"], PDO::PARAM_INT);
        $idQuery->bindParam(2, $_SESSION["id"], PDO::PARAM_INT);
        if (!$idQuery->execute()) echo "<p>Error executing student search</p>";
        else {
            $studentData = $idQuery->fetchAll(PDO::FETCH_ASSOC);
            $idQuery->closeCursor();
        }
    } echo "<p>Error: no Student ID provided</p>";
}
?>
<p>OR</p>
<?php
$select_form_name_fac = new PhpFormBuilder();
$select_form_name_fac->set_att("method", "POST");
$select_form_name_fac->add_input("First Name: ", array(
    "type" => "text"
), "search_fn_fac");
$select_form_name_fac->add_input("Last Name: ", array(
    "type" => "text"
), "search_ln_fac");
$select_form_name_fac->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "serch_name_fac");
$select_form_name_fac->build_form();

if (isset($_POST["search_name_fac"])) {
    if (!empty($_POST["search_fn_fac"]) && !empty($_POST["search_ln_fac"])) {
        $nameQuery = $db->prepare("call GetStudentFromClassesName(?, ?, ?)");
        $nameQuery->bindParam(1, $_POST["search_fn_fac"], PDO::PARAM_STR);
        $nameQuery->bindParam(2, $_POST["search_ln_fac"], PDO::PARAM_STR);
        $nameQuery->bindParam(3, $_SESSION["id"], PDO::PARAM_INT);
        if (!$nameQuery->execute()) echo "<p>Error executing student search</p>";
        else {
            $studentNameData = $idQuery->fetchAll(PDO::FETCH_ASSOC);
            $nameQuery->closeCursor();
        }
    } echo "<p>Error: no Student first/last name provided</p>";
}

?>
<?php else : ?>
<?php
$studentQuery = $db->prepare("select * from Student where StudentID = ?");
$studentQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_STR);
$studentQuery->execute();
$arrayOfInfo = $studentQuery->fetchAll(PDO::FETCH_ASSOC);

?>
    <h2>View Student Information: <?=$arrayOfInfo[0]["FirstName"]?> <?=$arrayOfInfo[0]["LastName"]?></h2>
    <h3>General Information</h3>
    <p>First Name: <?=$arrayOfInfo[0]["FirstName"]?></p>
    <p>Middle Initial: <?=$arrayOfInfo[0]["MidInitial"]?></p>
    <p>Last Name: <?=$arrayOfInfo[0]["LastName"]?></p>
    <p>ID: <?=$arrayOfInfo[0]["StudentID"]?></p>
    <p>Gender: <?=$arrayOfInfo[0]["Gender"]?></p>
    <p>GPA: <?=$arrayOfInfo[0]["GPA"]?></p>
    <p>School: <?=$arrayOfInfo[0]["SchoolName"]?></p>

    <h3>Disciplinary & Socioeconomic Statistics</h3>
    <p>Number of absences: <?=$arrayOfInfo[0]["Num_Absences"]?></p>
    <p>Number of detentions: <?=$arrayOfInfo[0]["Num_Detentions"]?></p> 

<?php endif; ?>
</body>
</html>
