<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	header('Location: ../');
}



if(isset($_POST['register_submit'])) {

	if(isset($_POST['register_email'], $_POST['register_password'], $_POST['register_password_confirm'])) {

		$email = $_POST['register_email'];
		$password = $_POST['register_password'];
		$passwordConfirm = $_POST['register_password_confirm'];

		if(preg_match("/^[a-zA-Z_-]+(17|18|19|20|21)$/", $email)) {
			if($password === $passwordConfirm) {
				if(preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $password)) {
					if($stmt = $mysqli->prepare("SELECT id FROM users WHERE username=? OR email=?")) {
						$username = $email;
						$email .= '@amherst.edu';
						$stmt->bind_param('ss', $username, $email);
						$stmt->execute();
						$stmt->store_result();
						if($stmt->num_rows == 0) {
							if($stmt = $mysqli->prepare("INSERT INTO users (username, password, email, time_added) VALUES (?,?,?,?)")) {
								$password_hashed = hash('sha256',$password);
								$stmt->bind_param("sssi", $username, $password_hashed, $email, time());
								$stmt->execute();
								$stmt->close();
								header("Location: ../");								
							} else {
								$registerError = 'Internal MySQLi error. Please contact Patrick at patrick@RateOurCourses.com.';
							}
						} else {
							$registerError = 'Email already in use. If this problem persists please contact Patrick at patrick@RateOurCourses.com';
						}
					} else {
						$registerError = 'Internal MySQLi error. Please contact Patrick at patrick@RateOurCourses.com.';
					}
				} else {
					$registerError = 'Invalid Password. Please use at least one lowercase, uppercase, and numeric character.';
				}
			} else {
				$registerError = 'Passwords do not match. Try again.';
			}

		} else {
			$registerError = 'Invalid email address. Please use a valid Amherst College student email address.';
		}




	} else {
		$registerError = 'ERROR : Not all variables POSTed. Please contact Patrick at patrick@RateOurCourses.com.';
	}

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
	<body class='bg-ac'>
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
						<li class='nav-item'>
							<a class="nav-link" href="/roc2/login"><i class='fa fa-sign-in'></i> <strong class='letter-spaced-1 ml-1'>Login / Register</strong></a>
						</li>
					</ul>
					
				</div>
				
			</div>
		</nav>






		<div style='min-height: 100%;'>
			<div class='container pl-xs-5 pr-xs-5 pt-4 pb-4'>
			


				<div class='card col-md-8 offset-md-2 text-center pr-sm-5 pl-sm-5'>
					<h1 class='mt-4 mb-sm-4 mb-3 content-heading'>Create an Account</h1>

					<span class='text-muted'>After completing the form below, a confirmation link will be sent to your Amherst email address. By registering with RoC you agree to our <a href='documentation'>Terms of Service & Privacy Policy.</a></span>

					
					<?php
						if(isset($registerError)) {
							echo "<h4 class='text-warning mt-4'>" . $registerError . "</h4>";
						}

					?>

					<div class='mt-3 mb-3'>
						<hr>
					</div>

					<form action='' method='POST'>
						<div class='form-group row'>
							<label for='email' class='col-sm-4 col-form-label'>Amherst Email : </label>
							<div class='col-sm-8'>
								

								<div class='input-group'>
									<input type='text' class="form-control" name='register_email' placeholder="Email..." requdired>
									<div class="input-group-addon">@amherst.edu</div>
								</div>




							</div>
						</div>
						<div class='form-group row'>
							<label for='password' class='col-sm-4 col-form-label'>Password : </label>
							<div class='col-sm-8'>	
								<input type='password' class="form-control" name='register_password' placeholder="Password..." reqduired> 
							</div>
						</div>
						<div class='form-group row mb-5'>
							<label for='password' class='col-sm-4 col-form-label'>Confirm Password : </label>
							<div class='col-sm-8'>	
								<input type='password' class="form-control" name='register_password_confirm' placeholder="Password..." requdired> 
							</div>
						</div>

						<button type="submit" name='register_submit' class="btn btn-primary mb-3">Create Account</button>

						<div class='pb-3'>
							<hr>
							Already have an account? Sign in <a href='../login'>here!</a>
						</div>
						
					</form>


					
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








    <script type="text/javascript">
		$(window).scroll(function() {
		  $(".slideanim").each(function(){
		    var pos = $(this).offset().top;

		    var winTop = $(window).scrollTop();
		    if (pos < winTop + 600) {
		      $(this).addClass("slide");
		    }
		  });
		});
    </script>


  </body>
</html>