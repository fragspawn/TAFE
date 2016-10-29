<?PHP
	$_SESSION['last_request'] = time();

	$db_user = 'root'; $db_pass = ''; $db_uri = "mysql:dbname=comp;host=127.0.0.1";
	$conn = new PDO($db_uri, $db_user, $db_pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<html>
	<head>
		<link rel="stylesheet" href="./css/style.css">
		<!-- <meta http-equiv="refresh" content="30"> -->
		
		<script src="https://code.jquery.com/jquery-3.1.0.min.js"
						integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   
						crossorigin="anonymous">
		</script>
		
		<script>
			function get_data() {
				$.getJSON( "http://www.fragspawn.com/comp/league_ws.php", function( json_data ) {
					var outdiv = '';
					var counter = 0;
					for (var key in json_data) {	
						//console.log( "JSON Row Data: " + JSON.stringify(json_data[key])); // print ROW
						outdiv += '<section class="lowlight">';
						for(var subkey in json_data[key]) {
							//console.log( "JSON Key Data: " + JSON.stringify(subkey)); // print keys	
							//console.log( "JSON Value Data: " + JSON.stringify(json_data[key][subkey])); // print values;
							outdiv += '<span>' + json_data[key][subkey] + '</span>';
						}
						outdiv += '</section>';
					}
					adiv_of_info.innerHTML = outdiv;
 				});
			}
		</script>
	</head>
	 <body>	
	<div class="header_img">
    	<img src="./img/ROLM_logo_txt.png">
	</div>
<?PHP
// Display all SSIDs and wether they have been found or not
	echo '<section class="highlight"><span>Email</span><span class="wide">Session ID</span><span class="narrow">SSIDs Found</span></section>';
	$output = query_all_from_db();	
	foreach($output as $row) {
		echo '<section class="lowlight"><span>' . $row['email'] . '</span><span class="wide">' . $row['session_ID'] . '</span><span class="narrow">' . $row['ssids'] . '</span></section>';
	}

	function query_all_from_db() {
        // BUG: There is no control for users who visit 1 SSID multiple times
		global $conn;

		$check_sql = "SELECT count(*) AS ssids, session_ID, email FROM entry GROUP BY session_ID ORDER BY ssids DESC";
		$check_conn = $conn->prepare($check_sql);
		$check_conn->execute();
		$result = $check_conn->fetchAll();

		if(empty($result)) {
			echo "nobody is here yet";
			return false;
		} else {
			return $result;
		}
	}
?>
		 
		 <input type="button" value="charge" onClick="get_data()">
		 <div id="adiv_of_info">OUT: </div>
		 </div>
	</body>
</html>
