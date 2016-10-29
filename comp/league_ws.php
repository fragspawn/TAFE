<?PHP

$db_user = 'root'; $db_pass = ''; $db_uri = "mysql:dbname=comp;host=127.0.0.1";
$conn = new PDO($db_uri, $db_user, $db_pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$output = query_all_from_db();	
if($output) {
	header('Content-Type: application/json');
	echo json_encode($output);
}

function query_all_from_db() {
        // BUG: There is no control for users who visit 1 SSID multiple times
		global $conn;

		$check_sql = "SELECT email, session_ID, count(*) AS ssids FROM entry GROUP BY session_ID ORDER BY ssids DESC, entry_date DESC";
		$check_conn = $conn->prepare($check_sql);
		$check_conn->execute();
		$result = $check_conn->fetchAll(PDO::FETCH_ASSOC);
	
		if(empty($result)) {
			//echo "nobody is here yet";
			return array("result"=>"false");
		} else {
			return $result;
		}
	}
?>