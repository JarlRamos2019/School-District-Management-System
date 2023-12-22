<?php
session_start();
require_once("config.php");
$_SESSION["pick"] = "Class";
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

<h2>Search a Class Catalog</h2>

<?php
        $db = get_pdo_connection();

        $get_class_form = new PhpFormBuilder();
        $get_class_form->set_att("method", "POST");
        $get_class_form->add_input("School Name: ", array(
            "type" => "text"
        ), "school_name");
        $get_class_form->add_input("Submit", array(
            "type" => "submit",
            "value" => "Search"
        ), "search_school");
        $get_class_form->build_form();

        if (isset($_POST["search_school"])) {
            if (!empty($_POST["school_name"])) {
                /*
                $getClassQuery = $db->prepare("select ClassID, concat(FirstName, ' ', LastName) as Teacher,
                                               isHonorsOrAP
                                               from ClassInSchool
                                               natural join Teacher
                                               where SchoolName = ?");
                 */
                $getClassQuery = $db->prepare("call GetClassStatistics(?)");
                $getClassQuery->bindParam(1, $_POST["school_name"], PDO::PARAM_STR);
                if (!$getClassQuery->execute()) {
                    echo "<p>Error retrieving class information: </p>" . print_r($getClassQuery->errorInfo());
                } else {
                    echo "<p>Retrieving data...</p>";
                    $classInfo = $getClassQuery->fetchAll(PDO::FETCH_ASSOC);
                    $getClassQuery->closeCursor();
                    echo makeTable($classInfo);
                }
            } else echo "<p>You must enter a school name</p>";
        }
        unset($_POST["search_school"]);
?>

<?php if ($_SESSION["role"] != "Student") : ?>

<?php else : ?>
<?php endif; ?>
</body>
</html>
