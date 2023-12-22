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

<h2>Class Information</h2>

<?php
        $db = get_pdo_connection();
        $classInfo = "";

        $get_class_form = new PhpFormBuilder();
        $get_class_form->set_att("method", "POST");
        $get_class_form->add_input("Class: ", array(
            "type" => "text"
        ), "class_id");
        $get_class_form->add_input("Submit", array(
            "type" => "submit",
            "value" => "Search"
        ), "search_class");
        $get_class_form->build_form();

        if (isset($_POST["search_class"])) {
            if (!empty($_POST["class_id"])) {
                /*
                $getClassQuery = $db->prepare("select ClassID, concat(FirstName, ' ', LastName) as Teacher,
                                               isHonorsOrAP
                                               from ClassInSchool
                                               natural join Teacher
                                               where SchoolName = ?");
                 */

                $verification = $db->prepare("select * from ClassInSchool where TeacherID = ? and ClassID = ?");
                $verification->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
                $verification->bindParam(2, $_POST["class_id"], PDO::PARAM_STR);
                $verification->execute();
                $isTeachersClass = $verification->fetchAll(PDO::FETCH_ASSOC);
                $verification->closeCursor();

                if (empty($isTeachersClass)) echo "<p>You do not teach the class that you entered.</p>";
                else {
                    $getClassQuery = $db->prepare("call GetInfoAboutClass(?)");
                    $getClassQuery->bindParam(1, $_POST["class_id"], PDO::PARAM_STR);
                    if (!$getClassQuery->execute()) {
                        echo "<p>Error retrieving class information: </p>" . print_r($getClassQuery->errorInfo());
                    } else {
                        echo "<p>Retrieving data...</p>";
                        $classInfo = $getClassQuery->fetchAll(PDO::FETCH_ASSOC);
                        $getClassQuery->closeCursor();
                    }
                }
            } else echo "<p>You must enter a class ID</p>";
        }
?>
<?php if (!empty($isTeachersClass) && !empty($classInfo)) : ?>
<h2>General Class Information: <?=$_POST["class_id"]?></h2>
<ul>
<li>Student Body Count: <?=$classInfo[0]["StudentCount"]?></li>
<li>Average Percentage: <?=round($classInfo[0]["AveragePercentage"], 2)?>%</li>
<li>Percetage of Students Passing the Course: <?=round($classInfo[0]["PercentPassing"], 2)?>%</li>
<li>Average Number of Suspensions: <?=round($classInfo[0]["AverageSuspensions"], 2)?></li>
<li>Average Number of Detentions: <?=round($classInfo[0]["AverageDetentions"], 2)?></li>
<li>Average Number of Absences: <?=round($classInfo[0]["AverageAbsences"], 2)?></li>
</ul>

<h2>Class Roster</h2>
<?php
      if (isset($_POST["search_class"])) {
        if (!empty($_POST["class_id"])) {          
            $resultQuery = $db->prepare("select concat(Student.FirstName, ' ', Student.LastName) as Student,
                                         Percentage 
                                         from ClassInSchool
                                         natural join Learns_In
                                         natural join Student
                                         where ClassID = ?");
            $resultQuery->bindParam(1, $_POST["class_id"], PDO::PARAM_STR);
            if (!$resultQuery->execute()) echo "<p>Error retrieving class data</p>";
            $classRoster = $resultQuery->fetchAll(PDO::FETCH_ASSOC);
            $resultQuery->closeCursor();
            echo makeTable($classRoster);
        }
    
        unset($_POST["search_class"]);
      }
?>

<?php endif ; ?>

<?php if ($_SESSION["role"] != "Student") : ?>

<?php else : ?>
<?php endif; ?>
</body>
</html>
