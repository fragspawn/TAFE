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
		<script>
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


 </div>
	</body>
</html>
