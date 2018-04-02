<?php
    header('Content-Type: application/json');
    include '../session.php';

    if(isset($_SESSION['student_NO'])) {
        if($_GET['getData'] == 'listqueue') {
            $res = getQueue($_SESSION['cohort_ID']);
            echo json_encode($res);
            exit();
        }
        if($_GET['getData'] == 'getAllQueues') {
            if($_SESSION['student_NO'] == 0) {
                $res = getAllQueues($_SESSION['cohort_ID']);
                echo json_encode($res);
                exit();
            }
        }
        if($_GET['getData'] == 'noInQueue') {
            $res = getQueueCount($_SESSION['cohort_ID']);
            echo json_encode(Array('noInQueue'=>(int)$res));
            exit();
        } 
        if($_GET['getData'] == 'enqueue') {
            $data = Array('studentno'=>$_SESSION['student_NO'], 
                            'title'=>$_POST['problem'],
                            'desc'=>$_POST['description']);
            $res = enQueue($data);
            echo json_encode(Array('enQueued'=>(int)$res));
            exit();
        }
        if($_GET['getData'] == 'dequeue') {
            $res = deQueue($_GET['queueid'], $_SESSION['student_NO']);
            echo json_encode(Array('deQueued'=>(int)$res));
            exit();
        }
    }
?>