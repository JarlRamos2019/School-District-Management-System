<?php
require_once("config.php");
$_SESSION["pick"] = "Teacher";
$insertMsg = "";
// Handle any inserts/updates/deletes before outputting any HTML
// INSERT 
if (isset($_POST["insert"])) { 
    if (!empty($_POST["insert_tID"]) && !empty($_POST["insert_school"]) &&
    !empty($_POST["insert_date_hired"]) && !empty($_POST["insert_fName"]) &&
    !empty($_POST["insert_lName"]) && 
    (($_POST["insert_satisfaction"] == 0) || !empty($_POST["insert_satisfaction"])) &&
    (($_POST["insert_pay"] == 0) || !empty($_POST["insert_pay"]))) {
        $tIDToInsert = htmlspecialchars($_POST["insert_tID"]);
        $schoolToInsert = htmlspecialchars($_POST["insert_school"]);
        $hireDateToInsert = htmlspecialchars($_POST["insert_date_hired"]);
        $FNToInsert = htmlspecialchars($_POST["insert_fName"]);
        $MIToInsert = htmlspecialchars($_POST["insert_mInit"]);
        $LNToInsert = htmlspecialchars($_POST["insert_lName"]);
        $satisfactionToInsert = htmlspecialchars($_POST["insert_satisfaction"]);
        $payToInsert = htmlspecialchars($_POST["insert_pay"]);
        
        $db = get_pdo_connection();
        $setKeyZero = $db->prepare("set foreign_key_checks = 0");
        $query = $db->prepare("insert into Teacher (TeacherID, SchoolName, SatisfactionLevel, Pay, FirstName, MidInitial,
                               LastName, DateHired) values
                               (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bindParam(1, $tIDToInsert, PDO::PARAM_INT);
        $query->bindParam(2, $schoolToInsert, PDO::PARAM_STR);
        $query->bindParam(3, $satisfactionToInsert, PDO::PARAM_STR);
        $query->bindParam(4, $payToInsert, PDO::PARAM_INT);
        $query->bindParam(5, $FNToInsert, PDO::PARAM_STR);
        $query->bindParam(6, $MIToInsert, PDO::PARAM_STR);
        $query->bindParam(7, $LNToInsert, PDO::PARAM_STR);
        $query->bindParam(8, $hireDateToInsert, PDO::PARAM_STR);
       
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
    <h2>Add Teacher Information</h2>
<?php
if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $insertMsg = "";
}
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("Teacher ID: ", array(
    "type" => "number"
), "insert_tID");
$insert_form->add_input("Insert school: ", array(
    "type" => "text"
), "insert_school");
$insert_form->add_input("Insert Satisfaction Level: ", array(
    "type" => "text"
), "insert_satisfaction");
$insert_form->add_input("Insert Pay: ", array(
    "type" => "text"
), "insert_pay");
$insert_form->add_input("Insert Date Hired: ", array(
    "type" => "date"
), "insert_date_hired");
$insert_form->add_input("Insert first name: ", array(
    "type" => "text"
), "insert_fName");
$insert_form->add_input("Insert middle initial (optional): ", array(
    "type" => "text"
),"insert_mInit");
$insert_form->add_input("Insert last name: ", array(
    "type" => "text"
), "insert_lName");
$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "insert");
$insert_form->build_form();
?>
</body>
</html>
