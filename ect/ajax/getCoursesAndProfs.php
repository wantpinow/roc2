<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';


if(isset($_POST['dep'])) {

	$resultArr = array();
	
	if($stmt = $mysqli->prepare("SELECT number, name_number FROM courses WHERE department=?")) {
		$stmt->bind_param('s', $_POST['dep']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($num, $name_num);
		
		$resultArr['courses'] = array();
		while($stmt->fetch()) {
			$resultArr['courses'][$num] = $name_num;
		}
	}


	if($stmt = $mysqli->prepare("SELECT name FROM faculty WHERE department=?")) {
		$stmt->bind_param('s', $_POST['dep']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name);
		
		$resultArr['faculty'] = array();
		while($stmt->fetch()) {
			//$resultArr['faculty'][$name] = true;
			array_push($resultArr['faculty'], $name);
		}
	}
	echo json_encode($resultArr);
} else {
	echo 'bad';
}





?>