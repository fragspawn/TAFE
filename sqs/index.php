<?php
    include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQS</title>
    <link rel="stylesheet" href="css/sqs.css">
    <script src="js/sqs.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js"></script>
</head>
<body>
<?php
    if(isset($_SESSION['student_NO'])) {
        // show queue
        include 'queue.html';
    } else {
        // show student_NO entry
        include 'login.html';
    }
    //debug - delete me!
    print_r($_SESSION);
?>
</body>
</html>
