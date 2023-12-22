<?php
require_once("config.php");
$_SESSION["pick"] = "Class";

$deleteMsg = "";
// Handle any inserts/updates/deletes before outputting any HTML
// DELETE
if (isset($_POST["delete"])) {

    //echo "deleting...<br>";

    $db = get_pdo_connection();
    $query = false;

    if (!empty($_POST["delete_id"])) {
        $fkQuery1 = $db->prepare("set foreign_key_checks = 0");
        $fkQuery2 = $db->prepare("set foreign_key_checks = 1");
        $verification = $db->prepare("select * from ClassInSchool where ClassID = ?");
        $verification->bindParam(1, $_POST["delete_id"], PDO::PARAM_STR);
        $verification->execute();
        $res = $verification->fetchAll(PDO::FETCH_ASSOC);
        if (empty($res)) echo "Sorry, no class with the provided ID was found.";
        else {
            $query = $db->prepare("delete from ClassInSchool where ClassID = ?");
            $query->bindParam(1, $_POST["delete_id"], PDO::PARAM_STR);
            if ($query) {
                if (!$fkQuery1->execute()) echo "Error executing foreign key check query 1";
                if ($query->execute()) {
                    $deleteMsg = "Deleted " . $query->rowCount() . " rows";
                } else $deleteMsg = print_r($query->errorInfo(), true);
                if (!$fkQuery2->execute()) echo "Error executing foreign key check query 2";
            } else $deleteMsg = "Error executing delete query: Unable to delete<br>";
        }
    } else echo "Unable to delete: missing Class ID<br>";
    unset($_POST["delete"]);
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
    <h2>Delete Class Information</h2>

<?php
if (!empty($deleteMsg)) {
    echo $deleteMsg;
    $deleteMsg = "";
}
$delete_form = new PhpFormBuilder();
$delete_form->set_att("method", "POST");
$delete_form->add_input("Class ID: ", array(
    "type" => "text"
), "delete_id");
$delete_form->add_input("Delete", array(
    "type" => "submit",
    "value" => "Delete"
), "delete");
$delete_form->build_form();
?>
</body>
</html>
