<?php

	function rateLimit() {
		// RATE LIMIT CLIENT
		session_start();
		if(isset($_SESSION['last_request'])) {
			if((time() - $_SESSION['last_request']) < 3) {
				echo json_encode(array("result", "Client throttled"));
				$_SESSION['last_request'] = time();
				die();
			}
		} 
		$_SESSION['last_request'] = time();	
	}

	function checkReferrer() {  //incomplete
		$_SERVER['HTTP_REFERER'];
	}

	function dbConnect() {
		// SETUP CONNECTION TO DATABASE
		$db_user = 'root'; $db_pass = ''; $db_uri = "mysql:dbname=formsubmit;host=127.0.0.1";
		$conn = new PDO($db_uri, $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}

	function doValidate($input, $type) {
		// Progressively check input
		$input_string = trim($input);
		$input_string = htmlspecialchars($input_string, ENT_IGNORE, 'utf-8');
		$input_string = strip_tags($input_string);
		$input_string = stripslashes($input_string);
		
		if($type == 'string') {
			return $input_string;
		}
		
		if($type == 'int') {
			if(is_numeric($input_string)) {
				return $input_string;
			} else {
				return false;
			}
		}
		
		if($type == 'index') {
			if(is_numeric($input_string)) {
				if($input_string > 0) {
					return $input_string;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}		
	}
	function delBooking($email_addr) {
		$connect = dbConnect();
		$sql = "DELETE FROM booking WHERE email_addr = :email_addr";
		$conn = $connect->prepare($sql);
		$conn->bindValue(':email_addr', $email_addr);
		return $conn->execute();
	}
	
	function doNewEvent($post_array) {
		// TAKE FORM DATA AND INSERT INTO DATABASE
		// Build insert statement
		$connect = dbConnect();
		$sql = "INSERT INTO event (event_name, event_location, event_datetime, event_length, event_capacity) VALUES (:event_name, :event_location, :event_datetime, :event_length, :event_capacity);";
		$conn = $connect->prepare($sql);

		foreach($post_array as $fieldkey=>$fieldval) {
			$conn->bindValue(':' . $fieldkey, $fieldval);
		}

		$conn->execute();
		return $connect->lastInsertId();
	}

	function doNewBooking($post_array) {
		// TAKE FORM DATA AND INSERT INTO DATABASE
		// Build insert statement
		$connect = dbConnect();
		$sql = "INSERT INTO booking (first_name, last_name, email_addr, phone_number, event_id) VALUES (:first_name, :last_name, :email_addr, :phone_number, :event_id);";
		$conn = $connect->prepare($sql);

		foreach($post_array as $fieldkey=>$fieldval) {
			$conn->bindValue(':' . $fieldkey, $fieldval);
		}

		$conn->execute();
		return $connect->lastInsertId();
	}

	function doGetLocations() {
		$connect = dbConnect();
		$sql = "SELECT DISTINCT event_location FROM event";
		$conn = $connect->prepare($sql);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
	}

	function doGetSessionsByLocation($location) {
		$connect = dbConnect();
		$sql = "SELECT * FROM event WHERE event_location = :location AND event_datetime > now()";
		$conn = $connect->prepare($sql);
		$conn->bindValue(':location', $location, PDO::PARAM_STR);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
	}

	function doGetAllBookingsForEvent($event_id) {
		$connect = dbConnect();
		$sql = "SELECT * FROM booking WHERE event_id = :eventid";
		$conn = $connect->prepare($sql);
		$conn->bindValue(':eventid', $event_id, PDO::PARAM_STR);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
	}
		
	// EVENT LIST BY LOCATION (JSON)
	if(isset($_GET['ws_venue'])) {
		if($outVal = doValidate($_GET['ws_venue'], 'string')) {
			if($outVal == 'ALL') {
				$result = doGetLocations();
				if(sizeof($result) == 0) {
					echo json_encode(array("result", "No Results"));
				} else {
					echo json_encode($result);
				}
			} else {
				$result = doGetSessionsByLocation($outVal);
				if(sizeof($result) == 0) {
					echo json_encode(array("result", "No Results"));
				} else {
					echo json_encode($result);
				}
			}
		} else {
			echo json_encode(array("result", "Invalid Get Parameter"));
		}
		exit();
	}

	// VIEW BOOKINGS ON EVENT ID
	if(isset($_GET['ws_bookings_for_event'])) {
		$sanatised_input = doValidate($_GET['ws_bookings_for_event'], 'index');
		if($sanatised_input) { 
		$result = doGetAllBookingsForEvent($sanatised_input);
			if(sizeof($result) == 0) {
				echo json_encode(array("result", "No Results"));
			} else {
			echo json_encode($result);
			}
		} else {
			echo json_encode(array("result", "Invalid Get Parameter"));
		}
		exit();
	}

	// VENUE LIST BY LOCATION (HTML)
	if(isset($_GET['venue'])) {
		if($outVal = doValidate($_GET['venue'], 'string')) {
			$result = doGetSessionsByLocation($outVal);
			if(sizeof($result) == 0) {
				echo json_encode(array("result", "No Results"));
			} else {
				foreach($result as $row) {
					echo '<li><a href="#" onClick="showForm(\'' . $row['event_id'] . '\')">' . $row['event_name'] . ' / ' . $row['event_datetime'] . ' / ' . $row['event_datetime'] . '</a></li>';
				}
			}
		} else {
			echo json_encode(array("result", "Invalid Get Parameter"));
		}
		exit();
	}

	// DELETE ALL BOOKINGS ON E-MAIL ADDRESS
	if(isset($_POST['email_addr'])) {
		$res = delBooking(doValidate($_POST['email_addr'], 'string'));
		if($res) {
			echo json_encode(array("result", "bookings deleted"));
		} else {
			echo json_encode(array("result", "insert failed"));
		}
		exit();
	}
	
	// NEW BOOKING	
	if(isset($_POST['event_id'])) {
		$formdata = array();
		foreach($_POST as $fieldkey=>$fieldval) {
			switch($fieldkey) {
				case 'first_name':
					$formdata['first_name'] = doValidate($fieldval, 'string');
					break;
				case 'last_name':
					$formdata['last_name'] = doValidate($fieldval, 'string');
					break;
				case 'email_addr':
					$formdata['email_addr'] = doValidate($fieldval, 'string');
					break;
				case 'phone_number':
					$formdata['phone_number'] = doValidate($fieldval, 'string');
					break;
				case 'event_id':
					$formdata['event_id'] = doValidate($fieldval, 'index');
					break;
			}
		}

		if(sizeof($formdata) == 5) {
			$result = doNewBooking($formdata);
			if($result > 0) {
				echo json_encode(array("result", $result));
			} else {
				echo json_encode(array("result", "insert failed"));
			}
		} else {
			echo json_encode(array("result", "form Invalid"));
		}
		exit();
	}

	// NEW EVENT 
	if(isset($_POST['event_name'])) {
		$formdata = array();
		foreach($_POST as $fieldkey=>$fieldval) {
			switch($fieldkey) {
				case 'event_name':
					$formdata['event_name'] = doValidate($fieldval, 'string');
					break;
				case 'event_location':
					$formdata['event_location'] = doValidate($fieldval, 'string');
					break;
				case 'event_datetime':
					$formdata['event_datetime'] = doValidate($fieldval, 'string');
					break;
				case 'event_length':
					$formdata['event_length'] = doValidate($fieldval, 'string');
					break;
				case 'event_capacity':
					$formdata['event_capacity'] = doValidate($fieldval, 'index');
					break;
			}
		}

		if(sizeof($formdata) == 5) {
			$result = doNewEvent($formdata);
			if($result > 0) {
				echo json_encode(array("result", $result));
			} else {
				echo json_encode(array("result", "insert failed"));
			}
		} else {
			echo json_encode(array("result", "form Invalid"));
		}
		exit();
	}
	
	echo json_encode(array("result", "Invalid Request"));
?>
