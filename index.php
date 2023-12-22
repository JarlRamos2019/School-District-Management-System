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
    try {
        $db = get_pdo_connection();

        if (isset($_POST["log_in"])) {
            if (empty($_POST["username"]) || empty($_POST["password"])) {
                echo "<p>Both username and password are required.</p>";
            } else {
                // SQL query goes here
                $query = $db->prepare("select * 
                          from Users 
                          where Username = ?");
                $query->bindParam(1, $_POST["username"], PDO::PARAM_STR);
                # $query->bindParam(2, $_POST["password"], PDO::PARAM_STR);
                $query->execute();

                if (empty($query->fetchAll(PDO::FETCH_ASSOC))) {
                    echo "<p>The credentials provided are not correct</p>";
                } else {
                    $thePassword = $_POST["password"];
                    $hash_query = $db->prepare("select Password, Role, ID
                                                from Users
                                                where Username = ?");
                    $hash_query->bindParam(1, $_POST["username"], PDO::PARAM_STR);
                    $hash_query->execute();
                    $hash = $hash_query->fetchAll(PDO::FETCH_ASSOC);
                    echo $hash[0]["Password"];
                    echo $hash[0]["Role"];
                    $ps_Is_Correct = password_verify($thePassword, $hash[0]["Password"]);

                    $rowCount = $query->fetchAll(PDO::FETCH_ASSOC);
                    if ($rowCount > 0 && $ps_Is_Correct) {
                        $_SESSION["role"] = $hash[0]["Role"];
                        $_SESSION["id"] = $hash[0]["ID"];
                        if ($_SESSION["role"] == "Faculty")
                            header("location: teacher_dashboard.php");
                        else header("location: select.php");
                    } else {
                        echo "<p>Error fetching data</p>";
                    }
                }
            }
        }

    } catch(PDOException $error) {
        echo "Error connecting: " . $error->getMessage . "<br>";
    }
    ?>
<?php

$login_form = new PhpFormBuilder();
$login_form->set_att("method", "POST");
$login_form->add_input("Username: ", array(
    "type" => "text"
), "username");
$login_form->add_input("Password: ", array(
    "type" => "password"
), "password");
$login_form->add_input("Log In", array(
    "type" => "submit",
    "value" => "Log In"
), "log_in");
$login_form->build_form();

?>

</body>
</html>
