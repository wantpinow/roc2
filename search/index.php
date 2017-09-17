<?php
$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if(isset($_GET['search'])) {
	$search_query = urldecode($_GET['search']);

	// If there is a course sent from the autocomplete in the form 'DEPPNUMM', send them straight to that course page\
	// If the number isn't recognized, send them to that department page

	$possibleDep = substr($search_query, 0, 4);
	$possibleNum = substr($search_query, 4, 8);

	if($stmt = $mysqli->prepare('SELECT id FROM evaluations WHERE department=? AND number=? LIMIT 1')) {
		$stmt->bind_param('ss', $possibleDep, $possibleNum);
		$stmt->bind_result($res);
		$stmt->execute();
		$stmt->fetch();
		if($res) {
			header("Location: /roc2/view/course/?dep=" . $possibleDep . "&num=" . $possibleNum);
		} else if($stmt = $mysqli->prepare('SELECT id FROM evaluations WHERE department=? LIMIT 1')) {
			$stmt->bind_param('s', $possibleDep);
			$stmt->bind_result($res);
			$stmt->execute();
			$stmt->fetch();
			if($res) {
				header("Location: /roc2/view/department/?dep=" . $possibleDep);
			} else {
				header("Location: /roc2/view");
			}
			$stmt->close();
		}
		$stmt->close();
	}
	
	
	
}

?>



<!DOCTYPE html>
<html>
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/roc2/ect/bootstrap_4/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		
		<!-- FA icons -->
		<link rel='stylesheet' href='/roc2/ect/css/font-awesome-4.7.0/css/font-awesome.min.css'>
		
		<!-- Custom CSS -->
		<link rel='stylesheet' href='/roc2/ect/css/home_styles.css'>
		<link rel='stylesheet' href='/roc2/ect/css/Hover-master/css/hover.css'>
		<link rel='stylesheet' href='/roc2/ect/css/RateYo/jquery.rateyo.min.css'>

		<!-- .json files -->
		<!--<script type="text/javascript" src='/roc2/ect/js/json/departments.json'></script>-->

	</head>
<body>

    <form action='' method='get'>
        <p><label>Search:</label><input type='text' name='search' value='' class='searchThis' style="width:400px"></p>
    </form>

	<!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <!-- External JavaScript -->
    <script src='/roc2/ect/js/search.js'></script>


    <script type="text/javascript">





		




    </script>

</body>
</html>