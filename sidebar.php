
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
</head>

<style>
<?php include("style.css"); ?>
</style>

<body>

<div id="sidebar">
    <?php

    $userRole = $_SESSION["role"]; 

if ($userRole === "Faculty") {
        echo '<a href="teacher_dashboard.php">Dashboard</a>';
        echo '<a href="teachers_class_list.php">My Classes</a>';
        echo '<a href="select.php">My Students</a>';
        echo '<a href="index.php">Sign Out</a>';
       //links as needed
    } elseif ($userRole === "Student") {
        echo '<a href="select.php">Dashboard</a>';
        echo '<a href="update_for_student.php">Update Info</a>';
        echo '<a href="student_classes.php">My Classes</a>';
        echo '<a href="index.php">Sign Out</a>';
      //links as needed
    } else {
        echo '<a href="select_district.php">Districts</a>';
        echo '<a href="select_school.php">Schools</a>';
        echo '<a href="select_teacher.php">Teachers</a>';
        echo '<a href="select_ClassInSchool.php">Classes</a>';
        echo '<a href="select.php">Students</a>';
        echo '<a href="index.php">Sign Out</a>';
    }
    ?>
</div>

</body>
</html>
