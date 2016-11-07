<?PHP

	function dbConnect() {
		// SETUP CONNECTION TO DATABASE
		$db_user = 'root'; $db_pass = ''; $db_uri = "mysql:dbname=student_votes;host=127.0.0.1";
		$conn = new PDO($db_uri, $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	function getVenues() {
		$connect = dbConnect();
		$sql = "SELECT * FROM venue";
		$conn = $connect->prepare($sql);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
	}

	function doGetStudents() {
		$connect = dbConnect();
		$sql = "SELECT * FROM students";
		$conn = $connect->prepare($sql);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
	}

	function addVote($student, $vote) {
		$connect = dbConnect();
		$check_sql = "SELECT student_no FROM votes WHERE student_no = " . $student;
		$conn = $connect->prepare($check_sql);
		$conn->execute();
		$result = $conn->fetchAll(PDO::FETCH_ASSOC);	

		if(count($result) > 0) {
			echo 'vote already made';
		} else {
			$check2_sql = "SELECT * FROM venue";
			$conn = $connect->prepare($check2_sql);
			$conn->execute();
			$venues = $conn->fetchAll(PDO::FETCH_ASSOC);

			if(array_search($vote, array_column($venues, 'venue_url'))) {
				$sql = "INSERT INTO votes (student_no, vote_choice) VALUES ('" . $student . "', '" . $vote . "')";
				$conn = $connect->prepare($sql);
				$conn->execute();
			} else {
				echo 'invalid venue';
			}
		}
	}

	function doGetVotes() {
		$connect = dbConnect();
		$sql = "SELECT count(*) AS vote_count, vote_choice FROM votes GROUP BY vote_choice ORDER BY vote_count DESC";
		$conn = $connect->prepare($sql);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
    }

	if(isset($_POST['student_no'])) {
		$students = doGetStudents();
		if(array_search($_POST['student_no'] ,array_column($students,'id'))) {
			addVote($_POST['student_no'], $_POST['voted_place']);
		} else {
			echo 'invalid Student Number';
		}
	}
    
    ?>

<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
		<!-- <meta http-equiv="refresh" content="30"> -->
		<script>
		</script>
	</head>
	<body>	
	<div class="header_img">
    	<img src="img/ROLM_logo_txt.png">
	</div>

		<form method="POST" action="./vote.php">
			<input class="formlight" name="student_no" type="number" size="24" placeholder="Student Number"> 
			<select class="formlight" name="voted_place">
			<?php 
			$venues = getVenues();
			foreach($venues as $venue) {
				echo '<option value="' . $venue['venue_url'] . '">' . $venue['venue_url'] . '</option>';
			}
			?>
			</select>
			<input class="formborderlight" type="submit" name="submit" value="vote">
		</form>


<?PHP
// Display all SSIDs and wether they have been found or not
	$votes = doGetVotes();	

	foreach($votes as $vote) {
	    echo '<section id="' . $vote['vote_choice'] . '" class="highlight"><span>' .  $vote['vote_choice'] . '</span><span>' . $vote['vote_count'] . '</span></section>';
	}
?>

	</body>
</html>
