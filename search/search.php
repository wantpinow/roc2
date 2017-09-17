<?php
$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if(isset($_GET['term']) && $_GET['term'] !== '') {

	$likeVar = '%' . $_GET['term'] . '%';

	if($stmt = $mysqli->prepare("SELECT department, number, name_number, COUNT(department) FROM evaluations WHERE name_number LIKE ? GROUP BY name_number ORDER BY COUNT(department) DESC LIMIT 10")) {

		$stmt->bind_param('s', $likeVar);
		$stmt->execute();
		$stmt->bind_result($dep, $num, $name_num, $count);

		$res = array();
		$labels = array();
		$tick = 0;
		while($stmt->fetch()) {
			$thisCourse = array();
			$thisCourse['department'] = $dep;
			$thisCourse['number'] = $num;
			$thisCourse['name_number'] = $name_num;
			$thisCourse['count'] = $count;
			array_push($res, $thisCourse);
			//array_push($labels, $name_num);
			$tick = $tick + 1;

			$thisCourse = array();
			$thisCourse['Id'] = $dep . $num;
			$thisCourse['Count'] = $count;
			$thisCourse['Title'] = $name_num;

			array_push($labels, $thisCourse);
		}

		if(count($labels) == 0) {
			$thisCourse = array();
			$thisCourse['Id'] = '';
			$thisCourse['Count'] = 0;
			$thisCourse['Title'] = 'No Courses Found';
			array_push($labels, $thisCourse);
		}

		//echo json_encode($res);
		echo json_encode($labels);

	} else {
		var_dump($mysqli);
	}
}



?>