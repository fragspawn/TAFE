<?php
session_start();
include 'db.php';

if(isset($_POST['student_no'])) {
    // check student number
    $studentNumber = studentNoExists($_POST['student_no']);

    if($studentNumber != false) {
        $_SESSION['student_NO'] = $studentNumber['student_NO'];
        $_SESSION['cohort_ID'] = $studentNumber['cohort_ID'];
    } else {
        unset($_SESSION['student_NO']);
        unset($_SESSION['cohort_ID']);
    }
}


?>
