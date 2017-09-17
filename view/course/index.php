<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	$userDetails = getProfileInfo($uid);
}
// Check if the department in GET actually exists
if(isset($_GET['dep'], $_GET['num'])) {

	$depGet = urldecode($_GET['dep']);
	$numGet = urldecode($_GET['num']);
	$possibleDep = substr($depGet, 0, 4);
	$possibleDep = strtoupper($possibleDep);
	$possibleNum = substr($numGet, 0, 4);
	$possibleNum = strtoupper($possibleNum);

	if($stmt = $mysqli->prepare('SELECT id FROM evaluations WHERE department=? AND number=?')) {
		$stmt->bind_param('ss', $possibleDep, $possibleNum);
		$stmt->bind_result($res);
		$stmt->execute();
		$stmt->fetch();
		if(!$res) {
			header('Location: /roc2/view');
		}
		$stmt->close();
	} else {
		header('Location: /roc2/view');
	}

	$dep = $possibleDep;
	$num = $possibleNum;

	// get the courses themselves
	if($stmt = $mysqli->prepare('SELECT name_number, semester, prof_name, interesting, difficulty, workload, prof_rating, grade, comment, time_added FROM evaluations WHERE department=? AND number=? ORDER BY time_added DESC')) {

		$stmt->bind_param('ss', $dep, $num);
		$stmt->execute();
		
		$reviews = array();

		$stmt->bind_result($name_number, $semester, $prof_name, $interesting, $difficulty, $workload, $prof_rating, $grade, $comment, $time_added);

		$totalCount = 0;

		$avgIntr = 0;
		$avgDiff = 0;
		$avgWork = 0;
		$avgProf = 0;

		while($stmt->fetch()) {
			$thisReview = array();
			$thisReview['name_number'] = $name_number;
			$thisReview['semester'] = $semester;
			$thisReview['prof_name'] = $prof_name;

			$thisReview['interesting'] = $interesting;
			$thisReview['difficulty'] = $difficulty;
			$thisReview['workload'] = $workload;
			$thisReview['prof_rating'] = $prof_rating;

			$avgIntr += $interesting;
			$avgDiff += $difficulty;
			$avgWork += $workload;
			$avgProf += $prof_rating;			

			$thisReview['grade'] = $grade;
			$thisReview['comment'] = $comment;
			$thisReview['time_added'] = $time_added;

			$totalCount ++;
			array_push($reviews, $thisReview);
		}

		$avgIntr = round($avgIntr/$totalCount, 1);
		$avgDiff = round($avgDiff/$totalCount, 1);
		$avgWork = round($avgWork/$totalCount, 1);
		$avgProf = round($avgProf/$totalCount, 1);

		$stmt->close();	
	}

} else {
	header('Location: /roc2/view');
}

if(!$reviews) {
	$reviews = false;
}

?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Title -->
		<title>RoC - <?php echo explode(' - ' , $reviews[0]['name_number'])[0]?></title>

		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/roc2/ect/bootstrap_4/css/bootstrap.min.css">
		
		<!-- FA icons -->
		<link rel='stylesheet' href='/roc2/ect/css/font-awesome-4.7.0/css/font-awesome.min.css'>

		<!-- JQuery UI CSS -->
		<link rel='stylesheet' href='/roc2/ect/css/jquery-ui.min.css'>
		
		<!-- Custom CSS -->
		<link rel='stylesheet' href='/roc2/ect/css/template_styles.css'>
		<link rel='stylesheet' href='/roc2/ect/css/Hover-master/css/hover.css'>
		<link rel='stylesheet' href='/roc2/ect/css/datatables.bootstrap.css'>
		<link rel='stylesheet' href='/roc2/ect/css/RateYo/jquery.rateyo.min.css'>

		<!-- table CSS -->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.2/css/rowReorder.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">

		<style type="text/css">
			table {
				background-color: rgb(255,255,255);
			}
			tbody {
				cursor: pointer;
			}
			#example_wrapper {
				padding: 0px;
			}
			label {
				margin-left: 20px;
				margin-right: 20px;
			}
			.nameCol {
				min-width: 350px;
			}
			@media (max-width: 575px) {
				.nameCol {
					min-width: 200px;
				}
				label {
					margin-left: 5px;
					margin-right: 5px;
				}
			}	
			#example {
				margin-bottom: 0px !important;
			}	
		</style>

		<!-- .json Files -->
		<script type="text/javascript" src='/roc2/ect/js/json/departments.json'></script>

	</head>

	<body>

		<!-- Navigation Bar -->
		<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
			<div class='container nav_container mx-0 mx-sm-auto' id='nav_container'>
				<!--  Button for mobile / smaller screens -->
				<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#toggleNavbar" aria-controls="toggleNavbar" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				
				<!-- permanent RoC header-->
				<a class="navbar-brand mr-5" href="/roc2/">RoC <b class='amherst'>AMHERST</b></a>

				<!-- collapsable elements -->
				<div class="collapse navbar-collapse" id="toggleNavbar">

					<!-- left aligned elements -->
					<ul class="navbar-nav mr-auto mt-3 mt-md-0">
						<li class="nav-item mr-3"> <!-- USE CLASS 'active' TO SHOW ACTIVE TAB -->
							<a class="nav-link" href="/roc2/add-new"><i class='fa fa-pencil-square-o mr-2' style='position: relative; top: 1px'></i>Add New</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="/roc2/view"><i class='fa fa-book mr-2' style='position: relative; top: 1px'></i>Show All</a>
						</li>
					</ul>

					<!-- right aligned search bar -->
					<form class="form-inline my-2 my-lg-0 mr-4 hidden-md-down" action='/roc2/search/' method=''>
						<div class='input-group'>
							<input class="form-control searchBar" type="text" name='search' placeholder="Search for courses...">
							<span class='input-group-btn'>
								<button class='btn btn-secondary' type='submit'><i class='fa fa-search'></i></button>
							</span>
						</div>
					</form>

					<!-- right aligned login/logout button -->
					<ul class="navbar-nav">
						<?php
							if(isset($userDetails)) {
								echo "<li class='nav-item'><a class='nav-link' href='/roc2/logout'><i class='fa fa-user'></i> <strong class='letter-spaced-1 ml-1'>" . $userDetails['username'] . "</strong></a></li>";
							} else {
								echo "<li class='nav-item'><a class='nav-link' href='/roc2/login'><i class='fa fa-sign-in'></i> <strong class='letter-spaced-1 ml-1'>Login</strong></a></li>";
							}
						?>
					</ul>
				</div>
			</div>
		</nav>






		<!-- PAGE CONTENT -->
		<div class='bg-ac'>
			<div class='container pl-xl-5 pr-xl-5 pt-4 pb-4'>
				<div class='card p-4 text-center mb-4'> 
					<h1 class=' roc-h1-smallify' style="font-weight: 600;"><?php echo $reviews[0]['name_number'] ?></h1>

					<hr class='mb-4'>

					

					<div class='row'>
			    		<div class='col-lg-3 col-sm-6 mb-5 mb-lg-2'>
			    			
			    			<h3 class="mb-3">Interesting</h3>
			    			<h1><?php echo $avgIntr; ?></h1>
			    		</div>
			    		<div class='col-lg-3 col-sm-6 mb-5 mb-lg-2'>
			    			<h3 class="mb-3">Difficulty</h3>
			    			<h1><?php echo $avgDiff; ?></h1>

			    		</div>
			    		<div class='col-lg-3 col-sm-6 mb-5 mb-sm-2'>
			    			
			    			<h3 class="mb-3">Workload</h3>
			    			<h1><?php echo $avgWork; ?></h1>
			    		</div>
			    		<div class='col-lg-3 col-sm-6'>
			    			
			    			<h3 class="mb-3">Professor</h3>
			    			<h1><?php echo $avgProf; ?></h1>
			    		</div>

			    	</div>


    				<hr class='roc-hr-fade'>



					<span class='lead roc-text-gray'>These averages are from all <?php echo $totalCount; ?> reviews submitted to RateOurCourses. Taken this class yourself and wish to submit your a review? <a href='/roc2/add-new'>Click here to add to add your own!</a> </span>

				</div>
				<br>
				<div id='mainDiv' class='pr-lg-5 pl-lg-5'>
<!--
<div class="card text-center mb-4">
  	<div class="card-header">
    	<h5 class='mb-0'>Danielle Benedetto (Grade Recieved: A)</h5>
  	</div>
  	<div class="card-block">
  		<div class='row'>
    		<div class='col-lg-3 col-sm-6'>
    			
    			<h4 class="mb-3">Interesting</h4>
    			<div class='rating ml-auto mr-auto mb-5 mb-lg-2'></div>
    		</div>
    		<div class='col-lg-3 col-sm-6'>
    			<h4 class="mb-3">Difficulty</h4>
    			<div class='rating ml-auto mr-auto mb-5 mb-lg-2'></div>

    		</div>
    		<div class='col-lg-3 col-sm-6'>
    			
    			<h4 class="mb-3">Workload</h4>
    			<div class='rating ml-auto mr-auto mb-5 mb-sm-2'></div>
    		</div>
    		<div class='col-lg-3 col-sm-6'>
    			
    			<h4 class="mb-3">Professor</h4>
    			<div class='rating ml-auto mr-auto'></div>
    		</div>

    		<hr class='mt-lg-4 mt-5 mb-4'>

    		<span class='lead roc-text-gray ml-auto mr-auto'>I really loved this class, benedetto does a great job at teaching it!!</span>

    	</div>

  	</div>
  	<div class="card-footer text-muted">
    	Fall 2015
  	</div>
</div>
-->



				</div>

			</div>
		</div>

		
		
		
		
		
	    <!-- jQuery first, then Tether, then Bootstrap JS. -->
	    <script src="/roc2/ect/js/jquery-3.2.1.min.js"></script>
	    <script src="/roc2/ect/js/jquery-ui.min.js"></script>
	    <script src="/roc2/ect/js/tether.min.js"></script>
	    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js"></script>
	    <script src="/roc2/ect/css/RateYo/jquery.rateyo.min.js"></script>




	    <!-- External JavaScript -->
	    <script src='/roc2/ect/js/search.js'></script>

	    <!-- Page specific custom JS -->
	    <script type="text/javascript">
	    	// get results 
	    	var dep = <?php echo  "'$dep'" ?>;
	    	var reviews = <?php echo json_encode($reviews) ?>;

	    	// sort reviews by semester
	    	for (var i = 0; i < reviews.length; i++) {
	    		// enumerate the semester
	    		if(reviews[i]['semester'].charAt(0) == 'F') {
	    			reviews[i]['semester_num'] = parseInt(reviews[i]['semester'].charAt(1) + reviews[i]['semester'].charAt(2));
	    		} else {
	    			reviews[i]['semester_num'] = parseInt(reviews[i]['semester'].charAt(1) + reviews[i]['semester'].charAt(2)) + 0.5;
	    		}
	    	}
	    	// sort array by semester_num
	    	reviews.sort(function(a, b) {
    			return b.semester_num - a.semester_num;
			});




	    	
  			function buildOutput() {

  				var div = $('#mainDiv');

  				for (var i = 0; i < reviews.length; i++) {
  					var thisReview = reviews[i];


  					var htmlToAdd = '<div class="card text-center mb-5"><div class="card-header"><h5 class="mb-0">Prof. '+thisReview['prof_name'];

  					if(thisReview['grade'] != '-') {
  						htmlToAdd += ' (Grade Recieved: '+thisReview['grade']+')';
  					}

  					htmlToAdd += '</h5></div><div class="card-block"><div class="row"><div class="col-lg-3 col-sm-6"><h4 class="mb-3">Interesting</h4><div id="intr_'+i+'" class="ml-auto mr-auto mb-5 mb-lg-2"></div></div><div class="col-lg-3 col-sm-6"><h4 class="mb-3">Difficulty</h4><div id="diff_'+i+'" class="ml-auto mr-auto mb-5 mb-lg-2"></div></div><div class="col-lg-3 col-sm-6"><h4 class="mb-3">Workload</h4><div id="work_'+i+'" class="ml-auto mr-auto mb-5 mb-sm-2"></div></div><div class="col-lg-3 col-sm-6"><h4 class="mb-3">Professor</h4><div id="prof_'+i+'" class="ml-auto mr-auto"></div></div>'

  					if(thisReview['comment'] != '' && thisReview['comment'] != ' ') {
  						htmlToAdd += '<hr class="mt-lg-4 mt-5 mb-4"><span class="lead roc-text-gray ml-auto mr-auto pr-4 pl-4">'+thisReview['comment']+'</span>';
  					}

  					htmlToAdd += '</div></div><div class="card-footer text-muted">'+semesterToFull(thisReview['semester'])+'</div></div>';

  					div.append(htmlToAdd);
  				}

  				for (var i = 0; i < reviews.length; i++) {

  					var thisReview = reviews[i];

  					$("#intr_" + i).rateYo({
    					rating: thisReview['interesting'],
    					readOnly: true
  					});
  					$("#diff_" + i).rateYo({
    					rating: thisReview['difficulty'],
    					readOnly: true
  					});
  					$("#work_" + i).rateYo({
    					rating: thisReview['workload'],
    					readOnly: true
  					});
  					$("#prof_" + i).rateYo({
    					rating: thisReview['prof_rating'],
    					readOnly: true
  					});


  				}

  			}


  			buildOutput();

  			function semesterToFull(str) {
  				var res = '';
  				if(str.charAt(0) == 'F') {
  					res += 'Fall ';
  				} else {
  					res += 'Spring ';
  				}
  				res += '20' + str.charAt(1) + str.charAt(2);
  				return res;
  			}

	    </script>

  	</body>
</html>