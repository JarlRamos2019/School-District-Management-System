<?php
require_once("config.php");
$_SESSION["pick"] = "Class";
$updateMsg = "";
$items = "";
// Handle any inserts/updates/deletes before outputting any HTML
// UPDATE
if (isset($_POST["update"])) {
    if (!empty($_POST["update_cID"])) {
        $targetCID = htmlspecialchars($_POST["update_cID"]);
        $tIDToUpdate = htmlspecialchars($_POST["update_teacher_id"]);
        $honorsAPToUpdate = htmlspecialchars($_POST["update_honors_or_ap"]);
              
        $db = get_pdo_connection();

        if (!empty($_POST["update_cID"])) {
            $cIDquery = $db->prepare("select * from ClassInSchool where ClassID = ?");
            $cIDquery->bindParam(1, $targetCID, PDO::PARAM_STR);
            $cIDquery->execute();
            $items = $cIDquery->fetchAll(PDO::FETCH_ASSOC);
            if (empty($items)) {
                echo "Sorry, no class with the provided Class ID exists.";
            }
        }
        if (!empty($_POST["update_teacher_id"]) && !empty($items)) {
            $query = $db->prepare("update ClassInSchool set TeacherID = ? where ClassID = ?;");
            $query->bindParam(1, $tIDToUpdate, PDO::PARAM_INT);
            $query->bindParam(2, $targetCID, PDO::PARAM_STR);
            if (!$query->execute()) echo "Error executing teacher ID update:<br>" . print_r($query->errorInfo(), true);
            else echo "Teacher ID successfully updated<br>";
        }

        if (!empty($_POST["update_honors_or_ap"]) && !empty($items)) {
            $honorsAPQuery = $db->prepare("update ClassInSchool set isHonorsOrAP = ? where ClassID = ?");
            $honorsAPQuery->bindParam(1, $honorsAPToUpdate, PDO::PARAM_INT);
            $honorsAPQuery->bindParam(2, $targetCID, PDO::PARAM_STR);
            if (!$honorsAPQuery->execute()) echo "Error executing Honors/AP update:<br>" . print_r($satisfactionQuery->errorInfo(), true);
            else echo "Honors/AP status successfully updated<br>";
        }
        if (!empty($items) && empty($_POST["update_cID"]) && empty($_POST["update_teacher_id"]) &&
        empty($_POST["update_honors_or_ap"]))
        echo "Error: no updated values provided";

        unset($_POST["update"]);

    } else echo "Error: Class ID not provided.";
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
    <h2>Update Class Information</h2>

<?php
if (!empty($updateMsg)) {
    echo "$updateMsg<br>";
    $updateMsg = "";
}
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");
$update_form->add_input("Class ID: ", array(
    "type" => "text"
), "update_cID");
$update_form->add_input("Update Teacher ID: ", array(
    "type" => "text"
), "update_teacher_id");
$update_form->add_input("Update Honors/AP Status: ", array(
    "type" => "number"
), "update_honors_or_ap");
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

?>
</body>
</html>
