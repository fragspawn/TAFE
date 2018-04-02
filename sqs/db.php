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

function getQueueCount($cohortCode) {
    $conn = dbConnect();
    $sql = "
SELECT count(*) FROM `queue` WHERE 
    student_NO IN (
        SELECT student_NO FROM student 
            WHERE cohort_ID = :cohort AND 
                queue_DATE > NOW() - INTERVAL 1 DAY);";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cohort', $cohortCode, PDO::PARAM_INT);
    $stmt->execute();
    $retVal = $stmt->fetch();
    return $retVal[0];           
}

function getQueue($cohortCode) {
    $conn = dbConnect();
    $sql = "
SELECT * FROM `queue` 
    WHERE student_NO IN (
        SELECT student_NO FROM student WHERE 
            cohort_ID = :cohort) AND 
            queue_DATE > NOW() - INTERVAL 1 DAY;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cohort', $cohortCode, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();           
}

function getAllQueues() {
    $conn = dbConnect();
    $sql = "
SELECT * FROM queue WHERE 
    queue_DATE > NOW() - INTERVAL 1 DAY
        ORDER BY ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);    
}

function enQueue($queueData) {
    $conn = dbConnect(); 
// does the student already have an item in the queue?
    $checksql = "            
SELECT * FROM queue WHERE
    student_NO = :stno AND
    queue_DATE > NOW() - INTERVAL 1 DAY;";
    $stmt = $conn->prepare($checksql);
    $stmt->bindParam(':stno', $queueData['studentno'], PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() == 0) {   
        $sql = "
        INSERT INTO queue (student_NO, queue_TITLE, queue_DESC) 
            VALUES (:stno, :title, :desc);";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':stno', $queueData['studentno'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $queueData['title'], PDO::PARAM_STR);
        $stmt->bindParam(':desc', $queueData['desc'], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();   
    } else {
        return 0;
    }
}

function deQueue($queueID, $studentNO) {
    $conn = dbConnect();
    $checksql = "            
SELECT * FROM queue WHERE
    student_NO = :stno AND
    queue_DATE > NOW() - INTERVAL 1 DAY AND
    queue_ID = :queueno;";
    $stmt = $conn->prepare($checksql);
    $stmt->bindParam(':stno', $studentNO, PDO::PARAM_INT);
    $stmt->bindParam(':queueno', $queueID, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $sql = "
DELETE FROM queue 
    WHERE queue_ID = :queueno;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':queueno', $queueID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();   
    } else {
        return 0;
    }
}

function studentNoExists($studentNo) {
    $conn = dbConnect();
    $sql = "
SELECT * FROM student  
    INNER JOIN cohort 
        WHERE cohort.cohort_ID = student.cohort_ID AND 
              cohort.cohort_COS > now() AND 
              student_NO = :stno;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':stno', $studentNo, PDO::PARAM_INT);
    $stmt->execute();
    $retVal = $stmt->fetch();
    if($stmt->rowCount() > 0) {
        return $retVal;
    } else {
        return false;
    }
}
?>