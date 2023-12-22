<?php
session_start();
require_once("config.php");
$_SESSION["pick"] = "Student";
$studentRows = "";
$studentNameData = "";
$studentData = "";
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
            $studentRows = $query->fetchAll(PDO::FETCH_ASSOC);
            $query->closeCursor();
            if (empty($studentRows)) echo "Sorry, there are no students with the credentials provided.";
            else {
                echo "<h2>Student Info: " . $studentRows[0]["FirstName"] . " " . $studentRows[0]["LastName"] . "</h2>";
                echo "<ul>";
                echo "<li>Student ID: " . $studentRows[0]["StudentID"] . "</li>";
                echo "<li>First Name: " . $studentRows[0]["FirstName"] . "</li>";
                echo "<li>Middle Initial: " . $studentRows[0]["MidInitial"] . "</li>";
                echo "<li>Last Name: " . $studentRows[0]["LastName"] . "</li>";
                echo "<li>School: " . $studentRows[0]["SchoolName"] . "</li>";
                echo "<li>Gender: " . $studentRows[0]["Gender"] . "</li>";
                echo "<li>Number of suspensions: " . $studentRows[0]["Num_Suspensions"] . "</li>";
                echo "<li>Number of detentions: " . $studentRows[0]["Num_Detentions"] . "</li>";
                echo "<li>Number of absences: " . $studentRows[0]["Num_Absences"] . "</li>";
                echo "</ul>";
                echo "<h2>Classes</h2>";
                $allClassQuery = $db->prepare("select ClassID, Percentage from ClassInSchool natural join Learns_In
                                               where StudentID = ?");
                $allClassQuery->bindParam(1, $_POST["search_id"], PDO::PARAM_INT);
                $allClassQuery->execute();
                $theStudentsClasses = $allClassQuery->fetchAll(PDO::FETCH_ASSOC);
                $allClassQuery->closeCursor();
                echo makeTable($theStudentsClasses);

                $extraQuery = $db->prepare("select ExtraName from Extracurriculars where StudentID = ?");
                $extraQuery->bindParam(1, $studentRows[0]["StudentID"], PDO::PARAM_INT);
                $extraQuery->execute();
                $extras = $extraQuery->fetchAll(PDO::FETCH_ASSOC);
                $extraQuery->closeCursor();
                if (!empty($extras)) {
                    echo "<h2>Extracurricular Activities</h2>";
                    echo makeTable($extras);
                }

            }
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
            $studentRows = $query->fetchAll(PDO::FETCH_ASSOC);
            $query->closeCursor();
            if (empty($studentRows)) echo "Sorry, there are no students with the credentials provided.";
            else {
                echo "<h2>Student Info: " . $studentRows[0]["FirstName"] . " " . $studentRows[0]["LastName"] . "</h2>";
                echo "<ul>";
                echo "<li>Student ID: " . $studentRows[0]["StudentID"] . "</li>";
                echo "<li>First Name: " . $studentRows[0]["FirstName"] . "</li>";
                echo "<li>Middle Initial: " . $studentRows[0]["MidInitial"] . "</li>";
                echo "<li>Last Name: " . $studentRows[0]["LastName"] . "</li>";
                echo "<li>School: " . $studentRows[0]["SchoolName"] . "</li>";
                echo "<li>Gender: " . $studentRows[0]["Gender"] . "</li>";
                echo "<li>Number of suspensions: " . $studentRows[0]["Num_Suspensions"] . "</li>";
                echo "<li>Number of detentions: " . $studentRows[0]["Num_Detentions"] . "</li>";
                echo "<li>Number of absences: " . $studentRows[0]["Num_Absences"] . "</li>";
                echo "</ul>";
                echo "<h2>Classes</h2>";
                $allClassQuery = $db->prepare("select ClassID, Percentage from ClassInSchool natural join Learns_In natural join Student
                                               where FirstName = ? and LastName = ?");
                $allClassQuery->bindParam(1, $_POST["search_fn"], PDO::PARAM_STR);
                $allClassQuery->bindParam(2, $_POST["search_ln"], PDO::PARAM_STR);
                $allClassQuery->execute();
                $theStudentsClasses = $allClassQuery->fetchAll(PDO::FETCH_ASSOC);
                $allClassQuery->closeCursor();
                echo makeTable($theStudentsClasses);

                $extraQuery = $db->prepare("select ExtraName from Extracurriculars where StudentID = ?");
                $extraQuery->bindParam(1, $studentRows[0]["StudentID"], PDO::PARAM_INT);
                $extraQuery->execute();
                $extras = $extraQuery->fetchAll(PDO::FETCH_ASSOC);
                $extraQuery->closeCursor();
                if (!empty($extras)) {
                    echo "<h2>Extracurricular Activities</h2>";
                    echo makeTable($extras);
                }
            }
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
<h2>Search for a Student in your Classes</h2>
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
), "search_name_fac");
$select_form_name_fac->build_form();

if (isset($_POST["search_fac"])) {
    if (!empty($_POST["search_id_fac"])) {
        $idQuery = $db->prepare("call GetStudentFromClasses(?, ?)");
        $idQuery->bindParam(1, $_POST["search_id_fac"], PDO::PARAM_INT);
        $idQuery->bindParam(2, $_SESSION["id"], PDO::PARAM_INT);
        if (!$idQuery->execute()) echo "<p>Error executing student search</p>";
        else {
            $studentData = $idQuery->fetchAll(PDO::FETCH_ASSOC);
            $idQuery->closeCursor();
            if (empty($studentData)) echo "<p>No students with the provided credentials were found in your classes.</p>";
            else {
                echo "<h2>Student Info: " . $studentData[0]["FirstName"] . " " . $studentData[0]["LastName"] . "</h2>";
                echo "<ul>";
                echo "<li>Student ID: " . $studentData[0]["StudentID"] . "</li>";
                echo "<li>First Name: " . $studentData[0]["FirstName"] . "</li>";
                echo "<li>Middle Initial: " . $studentData[0]["MidInitial"] . "</li>";
                echo "<li>Last Name: " . $studentData[0]["LastName"] . "</li>";
                echo "<li>Number of absences: " . $studentData[0]["Num_Absences"] . "</li>";
                echo "</ul>";
                echo "<h2>Classes</h2>";
                $allClassQuery = $db->prepare("select ClassID, Percentage from ClassInSchool natural join Learns_In
                                                       where StudentID = ? and TeacherID = ?");
                $allClassQuery->bindParam(1, $_POST["search_id_fac"], PDO::PARAM_INT);
                $allClassQuery->bindParam(2, $_SESSION["id"], PDO::PARAM_INT);
                $allClassQuery->execute();
                $theStudentsClasses = $allClassQuery->fetchAll(PDO::FETCH_ASSOC);
                $allClassQuery->closeCursor();
                echo makeTable($theStudentsClasses);
            }
        }
       
    } echo "<p>Error: no Student ID provided</p>";
    unset($_POST["search_fac"]);
} else if (isset($_POST["search_name_fac"])) {
    if (!empty($_POST["search_fn_fac"]) && !empty($_POST["search_ln_fac"])) {
        $nameQuery = $db->prepare("call GetStudentFromClassesName(?, ?, ?)");
        $nameQuery->bindParam(1, $_POST["search_fn_fac"], PDO::PARAM_STR);
        $nameQuery->bindParam(2, $_POST["search_ln_fac"], PDO::PARAM_STR);
        $nameQuery->bindParam(3, $_SESSION["id"], PDO::PARAM_INT);
        if (!$nameQuery->execute()) echo "<p>Error executing student search</p>";
        else {
            $studentNameData = $nameQuery->fetchAll(PDO::FETCH_ASSOC);
            if (empty($studentNameData)) echo "<p>No students with the provided credentials were found in your classes.</p>";
            else {
                $nameQuery->closeCursor();
                echo "<h2>Student Info: " . $studentNameData[0]["FirstName"] . " " . $studentNameData[0]["LastName"] . "</h2>";
                echo "<ul>";
                echo "<li>Student ID: " . $studentNameData[0]["StudentID"] . "</li>";
                echo "<li>First Name: " . $studentNameData[0]["FirstName"] . "</li>";
                echo "<li>Middle Initial: " . $studentNameData[0]["MidInitial"] . "</li>";
                echo "<li>Last Name: " . $studentNameData[0]["LastName"] . "</li>";
                echo "<li>Gender: " . $studentNameData[0]["Gender"] . "</li>";
                echo "<li>Number of absences: " . $studentNameData[0]["Num_Absences"] . "</li>";
                echo "</ul>";
                echo "<h2>Classes</h2>";
                $allClassQuery = $db->prepare("select ClassID, Percentage from ClassInSchool natural join Learns_In natural join Student 
                                               where FirstName = ? and LastName = ? and TeacherID = ?");
                $allClassQuery->bindParam(1, $_POST["search_fn_fac"], PDO::PARAM_STR);
                $allClassQuery->bindParam(2, $_POST["search_ln_fac"], PDO::PARAM_STR);
                $allClassQuery->bindParam(3, $_SESSION["id"], PDO::PARAM_INT);
                if (!$allClassQuery->execute()) echo "Error executing query: " . print_r($allClassQuery->errorInfo());
                $theStudentsClasses = $allClassQuery->fetchAll(PDO::FETCH_ASSOC);
                $allClassQuery->closeCursor();
                echo makeTable($theStudentsClasses);
            }
        }
    } echo "<p>Error: no Student first/last name provided</p>";
    unset($_POST["search_name_fac"]);
} else if (isset($_POST["search_all_fac"])) {
    echo "Retrieving all student information...";
    $allQuery = $db->prepare("select StudentID, concat(Student.FirstName, ' ', Student.LastName) as Student, ClassID, Percentage 
                              from Student natural join Learns_In natural join ClassInSchool
                              where TeacherID = ?");
    $allQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
    if (!$allQuery->execute()) echo "Error executing select query:<br>" . print_r($allQuery->errorInfo());
    $resultAll = $allQuery->fetchAll(PDO::FETCH_ASSOC);
    echo makeTable($resultAll);
    unset($_POST["search_all_fac"]);
}


$select_form_all_fac = new PhpFormBuilder();
$select_form_all_fac->set_att("method", "POST");
$select_form_all_fac->add_input("Submit", array(
    "type" => "submit",
    "value" => "All Students"
), "search_all_fac");
$select_form_all_fac->build_form();


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
    <p>Number of suspensions: <?=$arrayOfInfo[0]["Num_Suspensions"]?></p>
    <p>Level of financial hardship: <?=$arrayOfInfo[0]["F_Hardship"]?></p>

<?php endif; ?>
</body>
</html>
