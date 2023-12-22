<?php
if ($_SESSION["role"] == "Student") $pages = [];
//else $pages = ["select", "insert", "update", "delete"];
?>
<nav>
<?php

if ($_SESSION["role"] == "Administration" && $_SESSION["pick"] == "Student") {
    echo "<a href='select.php'>select students</a>";
    echo "<a href='insert.php'>add students</a>";
    echo "<a href='update.php'>update student info</a>";
    echo "<a href='delete.php'>delete students</a>";
} else if ($_SESSION["role"] == "Administration" && $_SESSION["pick"] == "Teacher") {
    echo "<a href='select_teacher.php'>select teachers</a>";
    echo "<a href='insert_teacher.php'>add teachers</a>";
    echo "<a href='update_teacher.php'>update teacher info</a>";
    echo "<a href='delete_teacher.php'>delete teachers</a>";
} else if ($_SESSION["role"] == "Administration" && $_SESSION["pick"] == "School") {
    echo "<a href='select_school.php'>school info</a>";
    echo "<a href='deans_list.php'>dean's list</a>";
} else if ($_SESSION["role"] == "Administration" && $_SESSION["pick"] == "Class") {
    echo "<a href='select_ClassInSchool.php'>class catalogs</a>";
    echo "<a href='select_specific_class.php'>class information</a>";
    echo "<a href='insert_ClassInSchool.php'>add a class</a>";
    echo "<a href='update_ClassInSchool.php'>update a class</a>";
    echo "<a href='delete_ClassInSchool.php'>delete a class</a>";
} else if ($_SESSION["role"] == "Faculty" && $_SESSION["pick"] == "Class") {
    echo "<a href='teachers_class_list.php'>class list</a>";
    echo "<a href='teachers_classes.php'>class info</a>";
} else if ($_SESSION["role"] == "Faculty" && $_SESSION["pick"] == "Student") {
    echo "<a href='select.php'>search for student</a>";
    echo "<a href='update_student_teacher.php'>update student grades</a>";
}
?>
</nav>
