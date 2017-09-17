<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if(isset($_POST['dep'], $_POST['num'], $_POST['prof'], $_POST['sem'], $_POST['intr'], $_POST['diff'], $_POST['work'], $_POST['prof_rating'], $_POST['grad'], $_POST['comm'], $_POST['user_id'])) {

	if($stmt = $mysqli->prepare('SELECT name_number FROM courses WHERE department=? AND number=?')) {
		
		$stmt->bind_param('ss', $_POST['dep'], $_POST['num']);
		$stmt->execute();
		$stmt->bind_result($name_number);
		$stmt->fetch();
		$stmt->close();

		if(isset($name_number)) {
			
			if($stmt = $mysqli->prepare("INSERT INTO evaluations (`department`, `number`, `name_number`, `semester`, `prof_name`, `interesting`, `difficulty`, `workload`, `prof_rating`, `grade`, `comment`, `user_id`, `time_added`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {

				$dep = $_POST['dep'];
				$num = $_POST['num'];
				$name_number;
				$sem = $_POST['sem'];
				$prof = $_POST['prof'];
				$intr = floatval($_POST['intr']);
				$diff = floatval($_POST['diff']);
				$work = floatval($_POST['work']);
				$prof_rating = floatval($_POST['prof_rating']);
				$grad = $_POST['grad'];
				$comm = $_POST['comm'];
				$user_id = intval($_POST['user_id']);
				$curr_time = time();


				$stmt->bind_param('sssssddddssii', 
					$dep,
					$num,
					$name_number,
					$sem,
					$prof,
					$intr,
					$diff,
					$work,
					$prof_rating,
					$grad,
					$comm,
					$user_id,
					$curr_time
				);

				$stmt->execute();

				$stmt->close();

				echo 'good';

			} else {
				echo 'error';
			}


		} else {
			echo 'error';
		}

	} else {
		echo 'error';
	}

	/*
	if($stmt = $mysqli->prepare('INSERT INTO')) {

	} else {
		echo 'error with MySQLI';
	}
	*/

	$_POST = array();
}

?>