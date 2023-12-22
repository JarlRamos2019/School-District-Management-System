<?php
require_once("config.php");
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
        //include("sidebar.php");
        //include("nav.php"); 
    ?>
    <?php

$db = get_pdo_connection();
$query = $db->prepare("SELECT * FROM Student");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

//echo makeTable($rows);
?>

<h2>Search for a Student</h2>
<?php

$login_form = new PhpFormBuilder();
$login_form->set_att("method", "POST");
$login_form->add_input("Username: ", array(
    "type" => "text"
), "username");
$login_form->add_input("Password: ", array(
    "type" => "text"
), "password");
$login_form->add_input("Log In", array(
    "type" => "submit",
    "value" => "Log In"
), "log_in");

?>

</body>
</html>
