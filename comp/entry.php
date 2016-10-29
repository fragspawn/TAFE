<?PHP

session_start(); 

// RATE LIMIT the number of requests to only once every 3 seconds
	if(isset($_SESSION['last_request'])) {
		if((time() - $_SESSION['last_request']) < 3) {
			echo "baaaaad user TILT!";
			$_SESSION['last_request'] = time();
			die();
		}
	}

	$_SESSION['last_request'] = time();

	$db_user = 'root'; $db_pass = ''; $db_uri = "mysql:dbname=comp;host=127.0.0.1";
	$conn = new PDO($db_uri, $db_user, $db_pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//  Do prepared statements so the code is protected from SQL injection
//  Sanatise the $_GET & $_POST data so that we have no extra unwanted characters
//  Check that $_GET['ssid'] is a number before processing

// set sessions
	if(isset($_GET['ssid'])) {
		$get_ssid_parameter = sanatise_input($_GET['ssid']);
		if(!is_numeric($get_ssid_parameter)) {
			echo "baaaaad user TILT!";
			die();
		}

		if($get_ssid_parameter == '21493') { 
			// Ultimate troll video - rickroll
			$_SESSION['s21493'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
		if($get_ssid_parameter == '11632') { 
			// A gathering of the uninvited - flashmob 
			$_SESSION['s11632'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
		if($get_ssid_parameter == '64392') { 
			// Unspoken rules of social media - netiquette 
			$_SESSION['s64392'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
		if($get_ssid_parameter == '78482') { 
			// Using numbers instead of letters - leetspeek
			$_SESSION['s78482'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
		if($get_ssid_parameter == '48021') { 
			// Pretending to be someone else – spoofing 
			$_SESSION['s48021'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
		if($get_ssid_parameter == '09312') { 
			// A bad guy hacker – blackhat 
			$_SESSION['s09312'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
		if($get_ssid_parameter == '55821') { 
			// Social engineer personal info – phishing
			$_SESSION['s55821'] = true;
			insert_ssid_to_db($_GET['ssid']); 
		}
	}

	if(isset($_POST['email'])) {
		// attach e-mail to session 
		// update all Db records that relate to session ammend with e-mail
		$post_email_parameter = sanatise_input($_POST['email']); 
		$_SESSION['email'] = $post_email_parameter;
	  update_entries_with_email($post_email_parameter);
	}
?>
<html>
	<head>
		<link rel="stylesheet" href="./css/style.css">
	</head>
	 <body>	
	<div class="header_img">
    	<img src="./img/ROLM_logo_txt.png">
	</div>

<section>
	<div id="emailInputContainer">
		<form method="post" action="./entry.php?ssid=0">
			<input class="formlight" name="email" type="email" size="32" 
<?php
if(isset($_SESSION['email'])) {
	echo ' value="' . $_SESSION['email'] . '">';
} else {
	echo ' placeholder="email goes here">';
}
?>
<input class="formborderlight" type="submit" name="email_button" value="attach email to entry">
		</form>
	</div>
</section>

<?PHP
// Display all SSIDs and wether they have been found or not
		$not_found = "not found";
		if(isset($_SESSION['s21493'])) {
			// Ultimate troll video - rickroll
			echo '<section class="highlight">Ultimate troll video - rickroll</section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}
		if(isset($_SESSION['s11632'])) { 
			// A gathering of the uninvited - flashmob 
			echo '<section class="highlight">A gathering of the uninvited - flashmob </section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}
		if(isset($_SESSION['s64392'])) { 
			// Unspoken rules of social media - netiquette 
			echo '<section class="highlight">Unspoken rules of social media - netiquette </section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}
		if(isset($_SESSION['s78482'])) { 
			// Using numbers instead of letters - leetspeek
			echo '<section class="highlight">Using numbers instead of letters - leetspeek</section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}

		if(isset($_SESSION['s48021'])) { 
			// Pretending to be someone else – spoofing 
			echo '<section class="highlight">Pretending to be someone else – spoofing </section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}

		if(isset($_SESSION['s09312'])) { 
			// A bad guy hacker – blackhat 
			echo '<section class="highlight">A bad guy hacker – blackhat </section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}

		if(isset($_SESSION['s55821'])) { 
			// Social engineer personal info – phishing
			echo '<section class="highlight">Social engineer personal info – phishing</section>';
		} else {
			echo '<section class="lowlight">' . $not_found . '</section>';
		}

	function insert_ssid_to_db($ssid) {
    global $conn;

		$check_sql = "SELECT * FROM entry WHERE session_ID = :session AND SSID = :ssid";
		$check_conn = $conn->prepare($check_sql);
		$check_conn->bindParam(':session', session_id(), PDO::PARAM_STR);
		$check_conn->bindParam(':ssid', $ssid, PDO::PARAM_STR);
		$check_conn->execute();
		$result = $check_conn->fetchAll();

		if(empty($result)) {

			$sql = "INSERT INTO entry (session_ID, SSID, email) VALUES (:session_id, :ssid, ";

			if(isset($_SESSION['email'])) {
				$sql .= " :email);";
				$process = $conn->prepare($sql);
				$process->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
			} else {
				$sql .= "'');";
				$process = $conn->prepare($sql);
			}

			$process->bindParam(':session_id', session_id(), PDO::PARAM_STR);
			$process->bindParam(':ssid', $ssid, PDO::PARAM_STR);
			$process->execute();
		}
	}

	function update_entries_with_email($email) {
		global $conn;
		// update all Db records that relate to session ammend with e-mail
		$sql = "UPDATE entry SET email = :email WHERE session_ID = :session_id;";
		$process = $conn->prepare($sql);
		$process->bindParam(':email', $email, PDO::PARAM_STR);
		$process->bindParam(':session_id', session_id(), PDO::PARAM_STR);
		$process->execute();
	}

	function sanatise_input($input_string) {
    	$input_string = trim($input_string);
    	$input_string = htmlspecialchars($input_string, ENT_IGNORE, 'utf-8');
    	$input_string = strip_tags($input_string);
    	$input_string = stripslashes($input_string);
    	return $input_string;
	}

?>
	</body>
</html>
