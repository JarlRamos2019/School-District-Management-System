<?php
require_once("config.php");
$_SESSION["pick"] = "Student";

$updateMsg = "";
$items = "";
// Handle any inserts/updates/deletes before outputting any HTML
// UPDATE
if (isset($_POST["update"])) {
    
    if (!empty($_POST["update_sID"])) {
        $targetSID = htmlspecialchars($_POST["update_sID"]);
        $targetCID = htmlspecialchars($_POST["update_cID"]);
        $percentageToUpdate = htmlspecialchars($_POST["update_percentage"]);

        $db = get_pdo_connection();

        if (!empty($_POST["update_sID"]) && !empty($_POST["update_cID"])) {
            $sIDquery = $db->prepare("select * from Student natural join Learns_In 
                                      where StudentID = ? and ClassID = ?");
            $sIDquery->bindParam(1, $targetSID, PDO::PARAM_INT);
            $sIDquery->bindParam(2, $targetCID, PDO::PARAM_STR);
            $sIDquery->execute();
            $items = $sIDquery->fetchAll(PDO::FETCH_ASSOC);
            if (empty($items)) {
                echo "Sorry, no student with the provided Student ID and class exists.";
            } else {
                if (!empty($_POST["update_percentage"]) && !empty($items)) {
                    $query = $db->prepare("update Learns_In set Percentage = ? where StudentID = ? and ClassID = ?");
                    $query->bindParam(1, $percentageToUpdate, PDO::PARAM_INT);
                    $query->bindParam(2, $targetSID, PDO::PARAM_INT);
                    $query->bindParam(3, $targetCID, PDO::PARAM_STR);
                    if (!$query->execute()) $errMsg = "Error executing percentage update:<br>" . print_r($query->errorInfo(), true);
                    else echo "Percentage successfully updated<br>";
                }
            }
        }
       
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
    <h2>Update Student Grades</h2>

<?php
if (!empty($updateMsg)) {
    echo "$updateMsg<br>";
    $updateMsg = "";
}
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");

$update_form->add_input("Student ID: ", array(
    "type" => "number"
), "update_sID");
$update_form->add_input("Class ID: ", array(
    "type" => "text"
), "update_cID");
$update_form->add_input("Percentage: ", array(
    "type" => "number"
), "update_percentage");
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

?>
</body>
</html>
