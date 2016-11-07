<?PHP

	function dbConnect() {
		// SETUP CONNECTION TO DATABASE
		$db_user = 'root'; $db_pass = ''; $db_uri = "mysql:dbname=formsubmit;host=127.0.0.1";
		$conn = new PDO($db_uri, $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
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
		$sql = "SELECT * FROM event WHERE event_location = :location";
		$conn = $connect->prepare($sql);
		$conn->bindValue(':location', $location, PDO::PARAM_STR);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
	}

	function doGetAttendeesBySession($session) {
		$connect = dbConnect();
		$sql = "SELECT * FROM booking WHERE event_id = :id";
		$conn = $connect->prepare($sql);
		$conn->bindValue(':id', $session, PDO::PARAM_STR);
		$conn->execute();
		return $conn->fetchAll(PDO::FETCH_ASSOC);		
    }
    
    ?>

<html>
	<head>
		<link rel="stylesheet" href="../css/style.css">
		<!-- <meta http-equiv="refresh" content="30"> -->
		<script
			src="../js/jquery-3.1.0.min.js">
  		</script>

		<script>
			window.onload = function() {
                var darkgreen = document.getElementsByClassName('lowlight');
                var lightgreen = document.getElementsByClassName('superhighlight');
                for(var loop = 0;loop < darkgreen.length;loop++) {
					darkgreen[loop].style.display = 'none';
				}
                for(var loop = 0;loop < lightgreen.length;loop++) {
					lightgreen[loop].style.display = 'none';
				}
			}

            function showHideSessions(ses_name) {
                var sectionNodes = document.getElementsByTagName('section');
                var idname = ses_name.id;
                var idsize = idname.length;

                for(var loop = 0;loop < sectionNodes.length;loop++) {
                    if((sectionNodes[loop].className == 'superhighlight') || (sectionNodes[loop].className == 'lowlight')) {
                        if(sectionNodes[loop].id.substr(0,idsize) == ses_name.id) {
                            if(sectionNodes[loop].style.display == 'none') {
                                if(sectionNodes[loop].className == 'superhighlight') {
                                    sectionNodes[loop].style.display = 'none';
                                } else {
                                    sectionNodes[loop].style.display = 'block';
                                }
                            } else {
                                sectionNodes[loop].style.display = 'none';
                            }                        
                        }
                    }
                }    
            }

            function showHideAttendees(location) {
                var sectionNodes = document.getElementsByTagName('section');
                var idname = location.id;
                var idsize = idname.length;

                for(var loop = 0;loop < sectionNodes.length;loop++) {
                    if(sectionNodes[loop].className == 'superhighlight') {
                        if(sectionNodes[loop].id.substr(0,idsize) == location.id) {
                            if(sectionNodes[loop].style.display == 'none') {
                                sectionNodes[loop].style.display = 'block'
                            } else {
                                sectionNodes[loop].style.display = 'none'
                            }                        
                        }
                    }
                }
            }

			function showForm() {
				document.getElementById('tint').style.display = 'block';
				document.getElementById('form').style.display = 'block';
			}

			function hideForm() {
				document.getElementById('tint').style.display = 'none';
				document.getElementById('form').style.display = 'none';
			}

            function doSubmit(elem) {
				$.ajax({
					url: "http://localhost/TAFE/contact_tracker/ws/bookings_ws.php",
					method: "POST",
					data: $("form").serialize(),
					dataType: "html"
				}).done(function(msg) {
					console.log(msg);
					hideForm();
				});
			}
		</script>
	</head>
	<body>	
	<div class="header_img">
    	<img src="../img/ROLM_logo_txt.png">
	</div>

<?PHP
// Display all SSIDs and wether they have been found or not
	$locations = doGetLocations();	

	foreach($locations as $location) {
	    echo '<section id="' . $location['event_location'] . '" class="highlight" onClick="showHideSessions(this)"><span>' . $location['event_location'] . '</span></section>';
		$events = doGetSessionsByLocation($location['event_location']);
	    foreach($events as $event) {
	        echo '<section id="' . $location['event_location'] . '_' . $event['event_id'] . '" class="lowlight" onClick="showHideAttendees(this)"><span>' . $event['event_name'] . 
                '</span><span>' . $event['event_datetime'] . '</span><span>' . $event['event_capacity'] .
            '</span></section>';
            $customers = doGetAttendeesBySession($event['event_id']);
            foreach($customers as $customer) {
	            echo '<section id="' . $location['event_location'] . '_' . $event['event_id'] . '_' . $customer['booking_id'] . '" class="superhighlight"><span>' . $customer['first_name'] . '</span><span>' . $customer['last_name'] . 
                '</span><span>' . $customer['email_addr'] . '</span><span>' . $customer['phone_number'] .
                '</span></section>';
            }
        }
	}

?>
<aside class="addcircle" onClick="showForm()">+</aside>
<div id="tint"></div>
<div id="form">
	<aside onClick="hideForm()">X</aside>
	<h3>Add Event Form</h3>
	<form id="formvars">
		<div>
			<label>Event Name</label>
			<input type="text" name="event_name">
		</div>
		<div>
			<label>Event Location</label>
			<input type="text" name="event_location">
		</div>
		<div>
			<label>Event Date</label>
			<input type="date" name="event_datetime">
		</div>
		<div>
			<label>Event Time</label>
			<select name="event_length">
				<option value=".5">30 minutes</option>
				<option value="1">1 hour</option>
				<option value="1.5">1:30</option>
				<option value="2">2 hours</option>
				<option value="2.5">2.5 hours</option>
				<option value="3">3 hours</option>
			</select>
		</div>
		<div>
			<label>Capacity</label>
			<input type="text" name="event_capacity">
		</div>
		<div>
			<input type="button" value="submit" name="submit" onClick="doSubmit(this)">
		</div>
	</form>
</div>
 </div>
	</body>
</html>
