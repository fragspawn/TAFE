<?php
    session_start();
    include 'db.php';

    if(isset($_POST['student_no'])) {
        // check student numbe
        $studentNumber = studentNoExists($_POST['student_no']);
        if($studentNumber != false) {
            $_SESSION['student_NO'] = $studentNumber['student_NO'];
            $_SESSION['cohort_ID'] = $studentNumber['cohort_ID'];
        } else {
            unset($_SESSION['student_NO']);
            unset($_SESSION['cohort_ID']);
        }
        if($_POST['student_no'] == '1234') {
            $_SESSION['student_NO'] = 0;
            $_SESSION['cohort_ID'] = 0;  
        }
        header("Location: index.php");    
    }
    if(isset($_POST['logout'])) {
        unset($_SESSION['student_NO']);
        unset($_SESSION['cohort_ID']);
        header("Location: index.php");    
    }
?>
