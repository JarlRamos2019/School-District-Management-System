<?php
require_once("config.php");
$_SESSION["pick"] = "Class";
$insertMsg = "";
// Handle any inserts/updates/deletes before outputting any HTML
// INSERT 
if (isset($_POST["insert"])) { 
    if (!empty($_POST["insert_cID"]) && (!empty($_POST["insert_honors_or_ap"]) || ($_POST["insert_honors_or_ap"] == 0)) && 
    (!empty($_POST["insert_tID"]))) {
        $cIDToInsert = htmlspecialchars($_POST["insert_cID"]);
        $tIDToInsert = htmlspecialchars($_POST["insert_tID"]);
        $honorsAPToInsert = htmlspecialchars($_POST["insert_honors_or_ap"]);
              
        $db = get_pdo_connection();
        $setKeyZero = $db->prepare("set foreign_key_checks = 0");
        $query = $db->prepare("insert into ClassInSchool (ClassID, TeacherID, isHonorsOrAP) values (?, ?, ?)");
        $query->bindParam(1, $cIDToInsert, PDO::PARAM_STR);
        $query->bindParam(2, $tIDToInsert, PDO::PARAM_INT);
        $query->bindParam(3, $honorsAPToInsert, PDO::PARAM_INT);
               
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
    <h2>Add Class Information</h2>
<?php
if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $insertMsg = "";
}
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("Class ID: ", array(
    "type" => "text"
), "insert_cID");
$insert_form->add_input("Teacher ID: ", array(
    "type" => "number"
), "insert_tID");
$insert_form->add_input("Insert Honors/AP status (0/1): ", array(
    "type" => "number"
), "insert_honors_or_ap");
$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "insert");
$insert_form->build_form();
?>
</body>
</html>
