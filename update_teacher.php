<?php
require_once("config.php");
$_SESSION["pick"] = "Teacher";
$updateMsg = "";
$items = "";
// Handle any inserts/updates/deletes before outputting any HTML
// UPDATE
if (isset($_POST["update"])) {
    if (!empty($_POST["update_tID"])) {
        $targetTID = htmlspecialchars($_POST["update_tID"]);
        $schoolToUpdate = htmlspecialchars($_POST["update_school_name"]);
        $satisfactionToUpdate = htmlspecialchars($_POST["update_satisfaction"]);
        $payToUpdate = htmlspecialchars($_POST["update_pay"]);
        $hireDateToUpdate = htmlspecialchars($_POST["update_date_hired"]);
        $FNToUpdate = htmlspecialchars($_POST["update_fName"]);
        $MIToUpdate = htmlspecialchars($_POST["update_mInit"]);
        $LNToUpdate = htmlspecialchars($_POST["update_lName"]);
       
        $db = get_pdo_connection();

        if (!empty($_POST["update_tID"])) {
            $tIDquery = $db->prepare("select * from Teacher where TeacherID = ?");
            $tIDquery->bindParam(1, $targetTID, PDO::PARAM_INT);
            $tIDquery->execute();
            $items = $tIDquery->fetchAll(PDO::FETCH_ASSOC);
            if (empty($items)) {
                echo "Sorry, no teacher with the provided Teacher ID exists.";
            }
        }
        if (!empty($_POST["update_school_name"]) && !empty($items)) {
            $query = $db->prepare("update Teacher set SchoolName = ? where TeacherID = ?;");
            $query->bindParam(1, $schoolToUpdate, PDO::PARAM_STR);
            $query->bindParam(2, $targetTID, PDO::PARAM_INT);
            //if ($query->execute()) $updateMsg = "Success";
            //else echo "?";
            if (!$query->execute()) $errMsg = "Error executing school update:<br>" . print_r($query->errorInfo(), true);
            else echo "School successfully updated<br>";
        }

        if (!empty($_POST["update_satisfaction"]) && !empty($items)) {
            $satisfactionQuery = $db->prepare("update Teacher set SatisfactionLevel = ? where TeacherID = ?");
            $satisfactionQuery->bindParam(1, $satisfactionToUpdate, PDO::PARAM_INT);
            $satisfactionQuery->bindParam(2, $targetTID, PDO::PARAM_INT);
            if (!$satisfactionQuery->execute()) $errMsg = "Error executing satisfaction level update:<br>" . print_r($satisfactionQuery->errorInfo(), true);
            else echo "Satisfaction level successfully updated<br>";
        }
        if (!empty($_POST["update_pay"]) && !empty($items)) {
            $payQuery = $db->prepare("update Teacher set Pay = ? where TeacherID = ?");
            $payQuery->bindParam(1, $payToUpdate, PDO::PARAM_INT);
            $payQuery->bindParam(2, $targetTID, PDO::PARAM_INT);
            if (!$payQuery->execute()) $errMsg = "Error executing pay update:<br>" . print_r($payQuery->errorInfo(), true);
            else echo "Pay successfully updated<br>";
        }
        if (!empty($_POST["update_date_hired"]) && !empty($items)) {
            $dateQuery = $db->prepare("update Teacher set DateHired = ? where TeacherID = ?");
            $dateQuery->bindParam(1, $hireDateToUpdate, PDO::PARAM_STR);
            $dateQuery->bindParam(2, $targetTID, PDO::PARAM_INT);
            if (!$dateQuery->execute()) $errMsg = "Error executing hire date update:<br>" . print_r($dateQuery->errorInfo(), true);
            else echo "Hire date successfully updated<br>";
        }
        if (!empty($_POST["update_fName"]) && !empty($items)) {
            $fnquery = $db->prepare("update Teacher set FirstName = ? where TeacherID = ?");
            $fnquery->bindParam(1, $FNToUpdate, PDO::PARAM_STR);
            $fnquery->bindParam(2, $targetTID, PDO::PARAM_INT);
            if (!$fnquery->execute()) $errMsg = "Error executing first name update:<br>" . print_r($fnquery->errorInfo(), true);
            else echo "First name successfully updated<br>";
        }
        if (!empty($_POST["update_mInit"]) && !empty($items)) {
            $miquery = $db->prepare("update Teacher set MidInitial = ? where TeacherID = ?");
            $miquery->bindParam(1, $MIToUpdate, PDO::PARAM_STR);
            $miquery->bindParam(2, $targetTID, PDO::PARAM_INT);
            if (!$miquery->execute()) $errMsg = "Error executing middle initial update:<br>" . print_r($miquery->errorInfo(), true);
            else echo "Middle initial successfully updated<br>";
        }
        if (!empty($_POST["update_lName"]) && !empty($items)) {
            $lnquery = $db->prepare("update Teacher set LastName = ? where TeacherID = ?");
            $lnquery->bindParam(1, $LNToUpdate, PDO::PARAM_STR);
            $lnquery->bindParam(2, $targetTID, PDO::PARAM_INT);
            if (!$lnquery->execute()) $errMsg = "Error executing last name update:<br>" . print_r($lnquery->errorInfo(), true);
            else echo "Last name successfully updated<br>";
        }
        if (!empty($items) && empty($_POST["update_tID"]) && empty($_POST["update_school_name"]) &&
        empty($_POST["update_fName"]) && empty($_POST["update_mInit"]) && empty($_POST["update_lName"]) &&
        empty($_POST["update_satisfaction"]) && empty($_POST["update_pay"]) && empty($_POST["update_date_hired"]))
        echo "Error: no updated values provided";

        unset($_POST["update"]);

    } else echo "Error: Teacher ID not provided.";
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
    <h2>Update Teacher Information</h2>

<?php
if (!empty($updateMsg)) {
    echo "$updateMsg<br>";
    $updateMsg = "";
}
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");
$update_form->add_input("Teacher ID: ", array(
    "type" => "number"
), "update_tID");
$update_form->add_input("Update School: ", array(
    "type" => "text"
), "update_school_name");
$update_form->add_input("Update Satisfaction Level: ", array(
    "type" => "number"
), "update_satisfaction");
$update_form->add_input("Update Pay: ", array(
    "type" => "number"
), "update_pay");
$update_form->add_input("Update Date Hired: ", array(
    "type" => "date"
), "update_date_hired");
$update_form->add_input("Update First Name: ", array(
    "type" => "text"
), "update_fName");
$update_form->add_input("Update Middle Initial: ", array(
    "type" => "text"
), "update_mInit");
$update_form->add_input("Update Last Name: ", array(
    "type" => "text"
), "update_lName");
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

?>
</body>
</html>
