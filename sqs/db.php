<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function sanatise_input($input_string) {
    $input_string = trim($input_string);
    $input_string = htmlspecialchars($input_string, ENT_IGNORE, 'utf-8');
    $input_string = strip_tags($input_string);
    $input_string = stripslashes($input_string);
    return $input_string;
}

function dbConnect() {
    $conn = new PDO("mysql:host=localhost;dbname=sqs", 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

function getQueue($cohortCode) {
    $conn = dbConnect();
    $sql = "SELECT * FROM `queue` WHERE student_NO IN (SELECT student_NO FROM student WHERE cohort_ID = " . 
            $cohortCode . ") AND queue_DATE > NOW() - INTERVAL 1 DAY;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();           
}

function getAllQueues() {
    $conn = dbConnect();
    $sql = 'SELECT * FROM queue WHERE queue_DATE > NOW() - INTERVAL 1 DAY';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();    
}

function addQueue($queueData) {
    $conn = dbConnect();
    $sql = "INSERT into queue (student_NO, queue_TITLE, queue_DESC) VALUES (1, 'foobar', 'barfoo');";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();   
}

function deQueue($queueID) {
    $conn = dbConnect();
    $sql = 'DELETE * FROM queue WHERE queue_ID = ' . $queueID;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();   
}

function studentNoExists($studentNo) {
    $conn = dbConnect();
    $sql = "SELECT * FROM student WHERE student_NO = :stno;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':stno', $studentNo, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}

?>
