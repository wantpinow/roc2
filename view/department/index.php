<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	$userDetails = getProfileInfo($uid);
}
// Check if the department in GET actually exists
if(isset($_GET['dep'])) {

	$depGet = urldecode($_GET['dep']);
	$possibleDep = substr($depGet, 0, 4);
	$possibleDep = strtoupper($possibleDep);

	if($stmt = $mysqli->prepare('SELECT id FROM evaluations WHERE department=? LIMIT 1')) {
		$stmt->bind_param('s', $possibleDep);
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

	// get the course averages themselves
	if($stmt = $mysqli->prepare('SELECT name_number, number, AVG(interesting) AS interesting, AVG(difficulty) AS difficulty, AVG(workload) AS workload, AVG(prof_rating) AS prof_rating, COUNT(*) AS count FROM evaluations WHERE department=? GROUP BY name_number ORDER BY number ASC')) {

		$stmt->bind_param('s', $dep);
		$stmt->execute();
		
		$reviews = array();

		$stmt->bind_result($name_number, $number, $interesting, $difficulty, $workload, $prof_rating, $count);

		$totalCount = 0;

		while($stmt->fetch()) {
			$thisReview = array();
			$thisReview['name_number'] = $name_number;
			$thisReview['number'] = $number;

			$thisReview['interesting'] = $interesting;
			$thisReview['difficulty'] = $difficulty;
			$thisReview['workload'] = $workload;
			$thisReview['prof_rating'] = $prof_rating;
			$thisReview['count'] = $count;
			$totalCount += intval($count);
			array_push($reviews, $thisReview);
		}

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
		<title>RoC - <?php echo $dep ?></title>

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
				<div class='card p-4 text-center'> 
					<h1 class='roc-header-thin'><span id='departmentName'></span> Department</h1>

					<hr class='roc-hr-fade'>

					<span class='lead roc-text-gray'>Below is a list of all <?php echo $totalCount ?> evaluations for the <?php echo $dep ?> department. The ratings are based on an average of all evaluations of that course. <span class='hidden-md-down'> To look specific evaluations (with comments) in detail, click on the course.</span></span>

				</div>
				<br>
				<div class=''>

					<div class='card pt-4 pb-0'>




	<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr id='headerRow'>
                <th class='nameCol'>Course Name</th>
                <th>Interesting?</th>
                <th>Difficulty</th>
                <th>Workload</th>
                <th id='secondLastColumn'>Professor Rating</th>
                <th id='lastColumn' class='courseLink'>Course Page</th>
            </tr>
        </thead>

        <tbody id='tableBody'>

        </tbody>
    </table>




   					</div>
				</div>

			</div>
		</div>

		
		
		
		
		
	    <!-- jQuery first, then Tether, then Bootstrap JS. -->
	    <script src="/roc2/ect/js/jquery-3.2.1.min.js"></script>
	    <script src="/roc2/ect/js/jquery-ui.min.js"></script>
	    <script src="/roc2/ect/js/tether.min.js"></script>
	    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js"></script>


	    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	    <script src="https://cdn.datatables.net/rowreorder/1.2.2/js/dataTables.rowReorder.min.js"></script>
	    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>


	    <!-- External JavaScript -->
	    <script src='/roc2/ect/js/search.js'></script>

	    <!-- Page specific custom JS -->
	    <script type="text/javascript">
	    	// get results 
	    	var dep = <?php echo  "'$dep'" ?>;
	    	var courses = <?php echo json_encode($reviews) ?>;

	    	
	    	var depName = dep;
	    	if(departments[dep] != null) {
	    		depName = departments[dep].name;
	    	}
	    	$('#departmentName').text(depName);

			
				addCoursesToTable();
			    var table = $('#example').DataTable( {
			        rowReorder: {
			            selector: 'td:nth-child(10)'
			        },
			        responsive: true,
			        "paging":   false,
        			"ordering": true,
        			"info":     false
			    } );
			

			function addCoursesToTable() {
				var tab = $('#tableBody');
				for (var i = 0; i < courses.length; i++) {
					var thisCourse = courses[i];
					var name_number = thisCourse['name_number'];
					var intr = Math.round(thisCourse['interesting']*10)/10;
					var diff = Math.round(thisCourse['difficulty']*10)/10;
					var work = Math.round(thisCourse['workload']*10)/10;
					var prof = Math.round(thisCourse['prof_rating']*10)/10;
					var count = thisCourse['count'];
					var num = thisCourse['number'];

					var number = name_number.split(' - ')[0];
					var name = name_number.split(' - ')[1];



					var htmlToAdd = "<tr id='"+num+"'><td class='nameCol'><b>"+number+"</b> - "+name+" (<b>"+count+"</b>)</td><td><b>"+intr+"</b></td><td><b>"+diff+"</b></td><td><b>"+work+"</b></td><td><b>"+prof+"</b></td><td class='courseLink'><a href='/roc2/view/course/?dep="+dep+"&num="+num+"'>"+number+"</a></td></tr>";
					tab.append(htmlToAdd);
				}
			}

			$('tr').click(function() {
				if($(this).attr('id') == 'headerRow') {
					return;
				}


				if($('#secondLastColumn').css('display') != 'none') {
					document.location.href = '/roc2/view/course/?dep='+dep+'&num='+$(this).attr('id');
				} else {
					var list = $(this).find('ul');
					list.append('<button>hi</button>');
				}
			});

			$( window ).resize(function() {
  				if($('#secondLastColumn').css('display') != 'none') {
  					$('.courseLink').hide();
  				}
			});


	    </script>

  	</body>
</html>