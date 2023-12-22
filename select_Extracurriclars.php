<?php
require_once("config.php");

$db = get_pdo_connection();

// This if statement will be dealing with searching by using the studentID
if (isset($_POST["search_extracurricular_student"])) {
    $studentId = $_POST["search_extracurricular_student_id"];

    // Below are the queries for the extracurriclar 
    $extracurricular_query = $db->prepare("SELECT * FROM Extracurriculars WHERE StudentID = :studentId");
    $extracurricular_query->bindParam(':studentId', $studentId, PDO::PARAM_INT);
    $extracurricular_query->execute();
    $extracurricular_rows = $extracurricular_query->fetchAll(PDO::FETCH_ASSOC);

    // These functions below will show output/results for extracurricular
    if ($extracurricular_rows) {
        foreach ($extracurricular_rows as $extracurricular) {
            // Just some information for the extracurricular.
           echo "Student ID: " . $extracurricular['StudentID'] . "<br>";
            echo "Extracurricular Name: " . $extracurricular['ExtraName'] . "<br>";
           
        }
    } else {
        echo "No matching extracurricular found.";
    }


} elseif (issert ($_POST["search_extracurriclar_name"])){
    $extracurriclarName = $_POST["search_extracurricular_extra_name"];

    $extracurricular_query_name = $db->prepare("SELECT * FROM Extracurriculars WHERE ExtraName = :extracurricularName");
    $extracurricular_query_name->bindParam(':extracurricularName', $extracurricularName, PDO::PARAM_STR);
    $extracurricular_query_name->execute();
    $extracurricular_rows_name = $extracurricular_query_name->fetchAll(PDO::FETCH_ASSOC);


    // Will show more of  the results
    if ($extracurricular_rows_name) {
        foreach ($extracurricular_rows_name as $extracurricular) {
            // Display extracurricular information as needed
            echo "Student ID: " . $extracurricular['StudentID'] . "<br>";
            echo "Extracurricular Name: " . $extracurricular['ExtraName'] . "<br>";
            
        }
    } else {
        echo "No matching extracurricular found.";
    }
}
?>
