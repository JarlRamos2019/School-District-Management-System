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

<h2>Your Classes</h2>

<?php
        $db = get_pdo_connection();
        $classes = "";

        $teacherClassQuery = $db->prepare("call GetClassStatisticsByTeacher(?)");
        $teacherClassQuery->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
        if (!$teacherClassQuery->execute()) echo "<p>Error fetching class data</p>" . print_r($teacherClassQuery->errorInfo());
        else {
            $classes = $teacherClassQuery->fetchAll(PDO::FETCH_ASSOC);
            $teacherClassQuery->closeCursor();
            echo makeTable($classes);
        }
?>

</body>
</html>
