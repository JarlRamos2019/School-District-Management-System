<?php
require_once("config.php");
$insertMsg = "";
// Handle any inserts/updates/deletes before outputting any HTML
// INSERT 
if (isset($_POST["insert"])) { 
    if (!empty($_POST["insert_sID"]) && !empty($_POST["insert_hardship"]) &&
    !empty($_POST["insert_grade"]) && !empty($_POST["insert_fName"]) &&
    !empty($_POST["insert_lName"]) && !empty($_POST["insert_gender"]) && 
    (($_POST["insert_numSus"] == 0) || !empty($_POST["insert_numSus"])) &&
    (($_POST["insert_numDet"] == 0) || !empty($_POST["insert_numDet"])) &&
    (($_POST["insert_numAbs"] == 0) || !empty($_POST["insert_numAbs"])) &&
    (($_POST["insert_GPA"] == 0.0) || !empty($_POST["insert_GPA"])) &&
    !empty($_POST["insert_sName"])) {
        $sIDToInsert = htmlspecialchars($_POST["insert_sID"]);
        $fHToInsert = htmlspecialchars($_POST["insert_hardship"]);
        $gradeToInsert = htmlspecialchars($_POST["insert_grade"]);
        $FNToInsert = htmlspecialchars($_POST["insert_fName"]);
        $MIToInsert = htmlspecialchars($_POST["insert_mInit"]);
        $LNToInsert = htmlspecialchars($_POST["insert_lName"]);
        $genToInsert = htmlspecialchars($_POST["insert_gender"]);
        $susToInsert = htmlspecialchars($_POST["insert_numSus"]);
        $detToInsert = htmlspecialchars($_POST["insert_numDet"]);
        $absToInsert = htmlspecialchars($_POST["insert_numAbs"]);
        $sNameToInsert = htmlspecialchars($_POST["insert_sName"]);
        $GPAToInsert = htmlspecialchars($_POST["insert_GPA"]);

        $db = get_pdo_connection();
        $setKeyZero = $db->prepare("set foreign_key_checks = 0");
        $query = $db->prepare("insert into Student (StudentID, F_Hardship, Grade, GPA, FirstName, MidInitial,
                               LastName, Gender, Num_Suspensions, Num_Detentions, Num_Absences, SchoolName) values
                               (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bindParam(1, $sIDToInsert, PDO::PARAM_INT);
        $query->bindParam(2, $fHToInsert, PDO::PARAM_INT);
        $query->bindParam(3, $gradeToInsert, PDO::PARAM_INT);
        $query->bindParam(4, $GPAToInsert, PDO::PARAM_STR);
        $query->bindParam(5, $FNToInsert, PDO::PARAM_STR);
        $query->bindParam(6, $MIToInsert, PDO::PARAM_STR);
        $query->bindParam(7, $LNToInsert, PDO::PARAM_STR);
        $query->bindParam(8, $genToInsert, PDO::PARAM_STR);
        $query->bindParam(9, $susToInsert, PDO::PARAM_INT);
        $query->bindParam(10, $detToInsert, PDO::PARAM_INT);
        $query->bindParam(11, $absToInsert, PDO::PARAM_INT);
        $query->bindParam(12, $sNameToInsert, PDO::PARAM_STR);
        
        $setKeyOne = $db->prepare("set foreign_key_checks = 1"); 

        if (!$setKeyZero->execute()) $errMsg1 = "Error with foreign key check:<br>" . print_r($setKeyZero->errorInfo(), true);
        
        if (!$query->execute()) $insertMsg =  "Error executing insert query:<br>" . print_r($query->errorInfo(), true);
        else $insertMsg = "Inserted " . $query->rowCount() . " rows";

        if (!$setKeyOne->execute()) $errMsg2 = "Error with foreign key check:<br>" . print_r($setKeyOne>errorInfo(), true);
        
        unset($_POST["insert"]);

    } else echo "Error: all required fields must be filled out <br>";
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
    <?php include("nav.php"); ?>
    <?php include("sidebar.php"); ?>
    <h2>Add Student Information</h2>
<?php
if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $insertMsg = "";
}
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("Student ID: ", array(
    "type" => "number"
), "insert_sID");
$insert_form->add_input("Insert level of financial hardship: ", array(
    "type" => "text"
), "insert_hardship");
$insert_form->add_input("Insert grade (9/10/11/12): ", array(
    "type" => "text"
), "insert_grade");
$insert_form->add_input("Insert GPA (0.0-4.0): ", array(
    "type" => "text"
), "insert_GPA");
$insert_form->add_input("Insert first name: ", array(
    "type" => "text"
), "insert_fName");
$insert_form->add_input("Insert middle initial (optional): ", array(
    "type" => "text"
),"insert_mInit");
$insert_form->add_input("Insert last name: ", array(
    "type" => "text"
), "insert_lName");
$insert_form->add_input("Insert gender: ", array(
    "type" => "text"
), "insert_gender");
$insert_form->add_input("Insert number of suspensions: ", array(
    "type" => "text"
), "insert_numSus");
$insert_form->add_input("Insert number of detentions:  ", array(
    "type" => "text"
), "insert_numDet");
$insert_form->add_input("Insert number of absences: ", array(
    "type" => "text"
), "insert_numAbs");
$insert_form->add_input("Insert name of school: ", array(
    "type" => "text"
), "insert_sName");
$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "insert");
$insert_form->build_form();
?>
</body>
</html>
