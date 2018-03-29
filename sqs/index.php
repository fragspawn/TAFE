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
?>
</body>
</html>
