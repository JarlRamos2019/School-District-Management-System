
<?php
require_once("config.php");
$_SESSION["pick"] = "Teacher";
$rows = "";
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
$teacher_query = false;
$teacher_query = $db->prepare("SELECT * FROM Teacher");
    $teacher_query->execute();
    $teacher_rows = $teacher_query->fetchAll(PDO::FETCH_ASSOC);
    ?>
<h2>Search for a Teacher</h2>
    <?php
    $select_teacher_form = new PhpFormBuilder();
    $select_teacher_form->set_att("method", "POST");
    $select_teacher_form->add_input("Teacher ID: ", array(
        "type" => "number"
    ), "search_teacher_id");
    $select_teacher_form->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search_teacher");
    $select_teacher_form->build_form();
    ?>
    <p>OR</p>
    <?php
    $select_teacher_form_name = new PhpFormBuilder();
    $select_teacher_form_name->set_att("method", "POST");
    $select_teacher_form_name->add_input("First Name: ", array(
        "type" => "text"
    ), "search_teacher_fn");
    $select_teacher_form_name->add_input("Last Name: ", array(
        "type" => "text"
    ), "search_teacher_ln");
    $select_teacher_form_name->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search_teacher_name");
    $select_teacher_form_name->build_form();
    $select_teacher_all = new PhpFormBuilder();
    $select_teacher_all->set_att("method", "POST");
    $select_teacher_all->add_input("Full Teacher Roster", array(
        "type" => "submit",
        "value" => "Full Teacher Roster"
    ), "search_all_teachers");
    $select_teacher_all->build_form();
    if (isset($_POST["search_teacher"])) {
        if (!empty($_POST["search_teacher_id"])) {
            echo "searching by teacher ID...";
            $teacherQuery = $db->prepare("select * from Teacher where TeacherID = ?");
            $teacherQuery->bindParam(1, $_POST["search_teacher_id"]);
        }
        if ($teacherQuery) {
            if ($teacherQuery->execute()) {
                $tRows = $teacherQuery->fetchAll(PDO::FETCH_ASSOC);
                echo makeTable($tRows);
                if (empty($tRows)) echo "Sorry, there are no teachers with the credentials provided.";
            } else {
                echo "Error executing select query:<br>";
                print_r($teacherQuery->errorInfo());
            }
        } else echo "Error executing select query: no TeacherID specified<br>";

        unset($_POST["search_teacher"]);

    } else if (isset($_POST["search_teacher_name"])) {
        if (!empty($_POST["search_teacher_fn"]) && !empty($_POST["search_teacher_ln"])) {
            echo "searching by Teacher Name...";
            $teacherQuery = $db->prepare("select * from Teacher where FirstName = ? and LastName = ?");
            $teacherQuery->bindParam(1, $_POST["search_teacher_fn"], PDO::PARAM_STR);
            $teacherQuery->bindParam(2, $_POST["search_teacher_ln"], PDO::PARAM_STR);
            if ($teacherQuery) {
              if ($teacherQuery->execute()) {
                $rows = $teacherQuery->fetchAll(PDO::FETCH_ASSOC);
                echo makeTable($rows);
                if (empty($rows)) echo "Sorry, there are no teachers with the credentials given.";
            } else {
                echo "Error executing select query:<br>";
                print_r($teacherQuery->errorInfo());
            }
    $teacherQuery->bindParam(2, $_POST["search_teacher_ln"], PDO::PARAM_STR);
        }
               } else echo "Error executing select query: no full name specified<br>";

        unset($_POST["search_teacher_name"]);
    } else if (isset($_POST["search_all_teachers"])) {
        echo "Retrieving all teacher information...";
        $allQuery = $db->prepare("select * from Teacher");
        if (!$allQuery->execute()) echo "Error executing select query:<br>" . print_r($allQuery->errorInfo());
            $allRows = $allQuery->fetchAll(PDO::FETCH_ASSOC);
            echo makeTable($allRows);
            unset($_POST["search_all_teachers"]);
    }
   ?>
</body>
</html>

