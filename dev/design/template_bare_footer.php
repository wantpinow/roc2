<?php

////////////////////////////////////
////  RATE OUR COURSES TEMPLATE ////
////////// BARE W FOOTER  //////////
////////////////////////////////////


$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	$userDetails = getProfileInfo($uid);
}

?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Title -->
		<title>RoC - Template</title>

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
							<a class="nav-link active" href="/roc2/add-new"><i class='fa fa-pencil-square-o mr-2' style='position: relative; top: 1px'></i>Add New</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/roc2/view"><i class='fa fa-book mr-2' style='position: relative; top: 1px'></i>Show All</a>
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
				<div class='card p-4'> 
					bare template w footer
				</div>
			</div>
		</div>

		
			
	
	
		<!-- optional footer for 'main' pages -->
		
		<footer class='roc-footer-min hidden-sm-down'>
			<div class='container'>
				<p class="float-left">
	        		&copy; 2017 <span>RateOurCourses</span> &middot; <a href="/roc2/documentation">Terms</a>
	        		&middot; <a href='https://github.com/wantpinow/roc2'> Github</a>
	        	</p>
	        	<p class="float-right">
	        		<a href='/roc2/#team'>Team</a> &middot;
	        		<a href='/roc2/#contact'>Contact</a>
	        	</p>
			</div>
		</footer>
	
	
	
	    <!-- jQuery first, then Tether, then Bootstrap JS. -->
	    <script src="/roc2/ect/js/jquery-3.2.1.min.js"></script>
	    <script src="/roc2/ect/js/jquery-ui.min.js"></script>
	    <script src="/roc2/ect/js/tether.min.js"></script>
	    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js"></script>

	    <!-- External JavaScript -->
	    <script src='/roc2/ect/js/search.js'></script>

	    <!-- Page specific custom JS -->
	    <script type="text/javascript">
	    	// JS goes here
	    </script>

  	</body>
</html>