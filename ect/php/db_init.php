<?php
session_start();

//$mysqli = new mysqli('rateourcourses.com', 'rateourc_admin', '748znbu2WT88', 'rateourc_roc2');
$mysqli = new mysqli('localhost', 'root', '', 'roc2');

if($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}


function loggedIn() {

	global $mysqli;

	if(isset($_COOKIE['login_token'])) {

		if($stmt = $mysqli->prepare("SELECT user_id FROM login_tokens WHERE token=?")) {
			$hash_token = hash('sha256', $_COOKIE['login_token']);
			$stmt->bind_param('s', $hash_token);
			$stmt->execute();
			$stmt->bind_result($uid);
			$stmt->fetch();
			$stmt->close();

			if(isset($uid)) {
				return $uid;
			} else {
				return false;
			}
		} else {
			echo 'Mysqli prepare failure';
		}
	}

	return false;
}



function getProfileInfo($id) {
	
	global $mysqli;

	if($stmt = $mysqli->prepare("SELECT username, email, role FROM users WHERE id=?")) {

		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($un, $em, $rl);

		if($stmt->fetch()) {
			$res = array();
			$res['username'] = $un;
			$res['email'] = $em;
			$res['role'] = $rl;
			return $res;
		} else {
			return false;
		}

	} else {
		echo 'SQL prepare() syntax error.';
		return false;
	}

}

?>