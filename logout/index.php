<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	$userDetails = getProfileInfo($uid);

	if(isset($_POST['logout_submit'])) {
		if($stmt = $mysqli->prepare("DELETE FROM login_tokens WHERE token=? AND user_id=?")) {
			$stmt->bind_param('si', hash('sha256', $_COOKIE['login_token']), $uid);
			$stmt->execute();
			$stmt->close();
			setcookie("login_token", '1', time() - 60*60, '/', NULL, NULL, TRUE);
			$loggedOut = true;
			unset($userDetails);
		}
	}


} else {
	header('Location: ../login');
}



?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/roc2/ect/bootstrap_4/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		
		<!-- FA icons -->
		<link rel='stylesheet' href='/roc2/ect/css/font-awesome-4.7.0/css/font-awesome.min.css'>
		
		<!-- Custom CSS -->
		<link rel='stylesheet' href='/roc2/ect/css/home_styles.css'>
		<link rel='stylesheet' href='/roc2/ect/css/Hover-master/css/hover.css'>
		
	</head>
	<body class='bg-acc'>
		<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
			<div class='container nav_container' style='' id='nav_container'>
				<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<a class="navbar-brand mr-5" href="/roc2/">RoC <b class='amherst'>AMHERST</b></a>
				
				<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
					<ul class="navbar-nav mr-auto mt-2 mt-md-0">
						<li class='nav-item hidden-lg-up'>
							<hr>
						</li>
						<li class="nav-item mr-3"> <!-- USE CLASS 'active' TO SHOW ACTIVE TAB -->
							<a class="nav-link" href="/roc2/add-new"><i class='fa fa-pencil-square-o mr-2' style='position: relative; top: 1px'></i>Add New</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/roc2/show-all"><i class='fa fa-book mr-2' style='position: relative; top: 1px'></i>Show All</a>
						</li>
					</ul>
					<form class="form-inline my-2 my-lg-0 mr-4 hidden-md-down" action='/roc2/search/' method=''>
						<div class='input-group'>
							<input class="form-control" type="text" placeholder="Search for courses...">
							<span class='input-group-btn'>
								<button class='btn btn-secondary' type='submit'><i class='fa fa-search'></i></button>
							</span>
						</div>
					</form>
					<ul class="navbar-nav">
						<?php
							if(isset($userDetails)) {
								echo "<li class='nav-item'><a class='nav-link' href='/roc2/logout'><i class='fa fa-user'></i> <strong class='letter-spaced-1 ml-1'>" . $userDetails['username'] . "</strong></a></li>";
							} else {
								echo "<li class='nav-item'><a class='nav-link' href='/roc2/login'><i class='fa fa-sign-in'></i> <strong class='letter-spaced-1 ml-1'>Login / Register</strong></a></li>";
							}
						?>
					</ul>
					
				</div>
				
			</div>
		</nav>






		<div style='min-height: 100%;' class='bg-ac'>
			<div class='container pl-xs-5 pr-xs-5 pt-4 pb-4'>
			


				<div class='card col-md-8 offset-md-2 col-lg-6 offset-lg-3 text-center pr-sm-5 pl-sm-5'>
					

					<?php
						if(isset($loggedOut)) {
							echo "<h1 class='mt-4 mb-sm-5 mb-4 content-heading'>See you soon!</h1><span class='text-muted mb-4'>We hope you enjoyed your time at RoC <b class='amherst'>AMHERST</b> and look forward to seeing you very soon.</span>";
						} else {
							echo "<h1 class='mt-4 mb-sm-5 mb-4 content-heading'>Leaving?</h1><span class='text-muted mb-4'>Don't forget to <a href='../add-new'>add your own reviews</a> to keep RoC as up to date as possible. The more we all put in, the more we all get out of it!</span><form action='' method='POST'><button type='submit' name='logout_submit' class='btn btn-primary mb-4'>Log Out</button></form>";
						}
					?>



					
				</div>
			</div>
		</div>

		
			
	
	

	

				


	
	
		<!-- FOOTER --> 
		<hr style='margin: 0px'>
	    <footer class='pt-5 pl-5 pr-5 bg-faded pb-lg-5'>
	  		<p class="float-right hidden-md-down"><a href="#">Back to top</a></p>
	        <p class="float-left hidden-md-down">
	        	&copy; 2017 <span class='letter-spaced-1'>RateOurCourses</span> &middot; <a href="documentation">Terms</a>
	        	&middot; <a href='https://github.com/wantpinow'> Github</a>
	        </p>

	       <div class="hidden-lg-up" style='text-align: center;margin: 0px;'>&copy; 2017 <span class='letter-spaced-1'>RateOurCourses</span><br><br><a href="#">Privacy</a> &middot; <a href="#">Terms</a></div>		        
	       <br><br> 
		</footer>	
	
	
	
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>



  </body>
</html>