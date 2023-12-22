<?php
require_once("config.php");

$updateMsg = "";
$items = "";
// Handle any inserts/updates/deletes before outputting any HTML
// UPDATE
if (isset($_POST["update"])) {
    
    if ($_SESSION["role"] == "Student")
        $_POST["update_sID"] = $_SESSION["id"];

    if (!empty($_POST["update_sID"])) {
        $targetSID = htmlspecialchars($_POST["update_sID"]);
        $fHToUpdate = htmlspecialchars($_POST["update_hardship"]);
        $gradeToUpdate = htmlspecialchars($_POST["update_grade"]);
        $FNToUpdate = htmlspecialchars($_POST["update_fName"]);
        $MIToUpdate = htmlspecialchars($_POST["update_mInit"]);
        $LNToUpdate = htmlspecialchars($_POST["update_lname"]);
        $genToUpdate = htmlspecialchars($_POST["update_gender"]);
        $susToUpdate = htmlspecialchars($_POST["update_numSus"]);
        $detToUpdate = htmlspecialchars($_POST["update_numDet"]);
        $absToUpdate = htmlspecialchars($_POST["update_numAbs"]);
        $sNameToUpdate = htmlspecialchars($_POST["update_sName"]);
        $GPAToUpdate = htmlspecialchars($_POST["update_GPA"]);

        $db = get_pdo_connection();

        if (!empty($_POST["update_sID"])) {
            $sIDquery = $db->prepare("select * from Student where StudentID = ?");
            $sIDquery->bindParam(1, $targetSID, PDO::PARAM_INT);
            $sIDquery->execute();
            $items = $sIDquery->fetchAll(PDO::FETCH_ASSOC);
            if (empty($items)) {
                echo "Sorry, no student with the provided Student ID exists.";
            }
        }
        if (!empty($_POST["update_hardship"]) && !empty($items)) {
            $query = $db->prepare("update Student set F_Hardship = ? where StudentID = ?;");
            $query->bindParam(1, $fHToUpdate, PDO::PARAM_INT);
            $query->bindParam(2, $targetSID, PDO::PARAM_INT);
            //if ($query->execute()) $updateMsg = "Success";
            //else echo "?";
            if (!$query->execute()) $errMsg = "Error executing financial hardship update:<br>" . print_r($query->errorInfo(), true);
            else echo "Financial hardship successfully updated<br>";
        }

        if (!empty($_POST["update_grade"]) && !empty($items)) {
            $gradequery = $db->prepare("update Student set Grade = ? where StudentID = ?");
            $gradequery->bindParam(1, $gradeToUpdate, PDO::PARAM_INT);
            $gradequery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$gradequery->execute()) $errMsg = "Error executing grade update:<br>" . print_r($gradequery->errorInfo(), true);
            else echo "Grade successfully updated<br>";
        }
        if (!empty($_POST["update_fName"]) && !empty($items)) {
            $fnquery = $db->prepare("update Student set FirstName = ? where StudentID = ?");
            $fnquery->bindParam(1, $FNToUpdate, PDO::PARAM_STR);
            $fnquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$fnquery->execute()) $errMsg = "Error executing first name update:<br>" . print_r($fnquery->errorInfo(), true);
            else echo "First name successfully updated<br>";
        }
        if (!empty($_POST["update_mInit"]) && !empty($items)) {
            $miquery = $db->prepare("update Student set MidInitial = ? where StudentID = ?");
            $miquery->bindParam(1, $MIToUpdate, PDO::PARAM_STR);
            $miquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$miquery->execute()) $errMsg = "Error executing middle initial update:<br>" . print_r($miquery->errorInfo(), true);
            else echo "Middle initial successfully updated<br>";
        }
        if (!empty($_POST["update_lname"]) && !empty($items)) {
            $lnquery = $db->prepare("update Student set LastName = ? where StudentID = ?");
            $lnquery->bindParam(1, $LNToUpdate, PDO::PARAM_STR);
            $lnquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$lnquery->execute()) $errMsg = "Error executing last name update:<br>" . print_r($lnquery->errorInfo(), true);
            else echo "Last name successfully updated<br>";
        }
        if (!empty($_POST["update_gender"]) && !empty($items)) {
            $genquery = $db->prepare("update Student set Gender = ? where StudentID = ?");
            $genquery->bindParam(1, $genToUpdate, PDO::PARAM_STR);
            $genquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$genquery->execute()) $errMsg = "Error executing gender update:<br>" . print_r($genquery->errorInfo(), true);
            else echo "Gender successfully updated<br>";
        }
        if (!empty($_POST["update_numSus"]) && !empty($items)) {
            $susquery = $db->prepare("update Student set Num_Suspensions = ? where StudentID = ?");
            $susquery->bindParam(1, $susToUpdate, PDO::PARAM_INT);
            $susquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$susquery->execute()) $errMsg = "Error executing number of suspensions update:<br>" . print_r($susquery->errorInfo(), true);
            else echo "Number of suspensions successfully updated<br>";
        }
        if (!empty($_POST["update_numDet"]) && !empty($items)) {
            $detquery = $db->prepare("update Student set Num_Detentions = ? where StudentID = ?");
            $detquery->bindParam(1, $detToUpdate, PDO::PARAM_INT);
            $detquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$detquery->execute()) $errMsg = "Error executing number of detentions update:<br>" . print_r($detquery->errorInfo(), true);
            else echo "Number of detentions successfully updated<br>";
        }
        if (!empty($_POST["update_numAbs"]) && !empty($items)) {
            $absquery = $db->prepare("update Student set Num_Absences = ? where StudentID = ?");
            $absquery->bindParam(1, $absToUpdate, PDO::PARAM_INT);
            $absquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$absquery->execute()) $errMsg = "Error executing number of absences update:<br>" . print_r($absquery->errorInfo(), true);
            else echo "Number of absences successfully updated<br>";
        }
        if (!empty($_POST["update_sName"]) && !empty($items)) {
            $sNamequery = $db->prepare("update Student set SchoolName = ? where StudentID = ?");
            $sNamequery->bindParam(1, $sNameToUpdate, PDO::PARAM_STR);
            $sNamequery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$sNamequery->execute()) $errMsg = "Error executing school name update:<br>" . print_r($sNamequery->errorInfo(), true);
            else echo "School name successfully updated<br>";
        }
        if (!empty($_POST["update_GPA"]) && !empty($items)) {
            $GPAquery = $db->prepare("update Student set GPA = ? where StudentID = ?");
            $GPAquery->bindParam(1, $GPAToUpdate, PDO::PARAM_STR);
            $GPAquery->bindParam(2, $targetSID, PDO::PARAM_INT);
            if (!$GPAquery->execute()) $errMsg = "Error executing GPA update:<br>" . print_r($GPAquery->errorInfo(), true);
            else echo "GPA successfully updated<br>";
        }
        if (!empty($items) && empty($_POST["update_hardship"]) && empty($_POST["update_grade"]) &&
            empty($_POST["update_fName"]) && empty($_POST["update_mInit"]) && empty($_POST["update_lName"]) &&
            empty($_POST["update_gender"]) && empty($_POST["update_numSus"]) && empty($_POST["update_numDet"]) &&
            empty($_POST["update_numAbs"]) && empty($_POST["update_sName"]) && empty($_POST["update_GPA"]))
            echo "Error: no updated values provided";

        unset($_POST["update"]);

    } else echo "Error: Student ID not provided.";
}
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
    <h2>Update Student Information</h2>

<?php
if (!empty($updateMsg)) {
    echo "$updateMsg<br>";
    $updateMsg = "";
}
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");
if ($_SESSION["role"] != "Student") {
    $update_form->add_input("Student ID: ", array(
        "type" => "number"
    ), "update_sID");
}
$update_form->add_input("Update level of financial hardship: ", array(
    "type" => "text"
), "update_hardship");
if ($_SESSION["role"] != "Student") {
    $update_form->add_input("Update grade (9/10/11/12): ", array(
        "type" => "text"
    ), "update_grade");
    $update_form->add_input("Update GPA: ", array(
        "type" => "text"
    ), "update_GPA");
}
$update_form->add_input("Update first name: ", array(
    "type" => "text"
), "update_fName");
$update_form->add_input("Update middle initial: ", array(
    "type" => "text"
), "update_mInit");
$update_form->add_input("Update last name: ", array(
    "type" => "text"
), "update_lname");
$update_form->add_input("Update gender: ", array(
    "type" => "text"
), "update_gender");
if ($_SESSION["role"] != "Student") {
    $update_form->add_input("Update number of suspensions: ", array(
        "type" => "text"
    ), "update_numSus");
    $update_form->add_input("Update level of detentions: ", array(
        "type" => "text"
    ), "update_numDet");
    $update_form->add_input("Update level of absences: ", array(
        "type" => "text"
    ), "update_numAbs");
    $update_form->add_input("Update school name: ", array(
        "type" => "text"
    ), "update_sName");
}
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

?>
</body>
</html>
