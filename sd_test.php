<?php
require_once("config.php");
$_SESSION["pick"] = "District";
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
    <?php

    $db = get_pdo_connection();
    $district_query = $db->prepare("SELECT * FROM District");
    $district_query->execute();
    $district_rows = $district_query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h2>Search for a District</h2>

    <?php
    $select_district_form = new PhpFormBuilder();
    $select_district_form->set_att("method", "POST");
    $select_district_form->add_input("District Name: ", array(
        "type" => "text"
    ), "search_district_name");
    $select_district_form->add_input("Submit", array(
        "type" => "submit",
        "value" => "Search"
    ), "search_district");
    $select_district_form->build_form();
    ?>
</body>
</html>
