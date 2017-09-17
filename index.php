<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	$userDetails = getProfileInfo($uid);
}


$currentStats;
$currentStats['evals'] = 853;
$currentStats['profs'] = 271;
$currentStats['courses'] = 374;

if($stmt = $mysqli->prepare("SELECT COUNT(DISTINCT id) AS count_evals, COUNT(DISTINCT prof_name) AS count_prof, COUNT(DISTINCT department,number) AS count_course FROM evaluations")) {
	$stmt->execute();
	$stmt->bind_result($currentStats['evals'], $currentStats['profs'], $currentStats['courses']);
	$stmt->fetch();
	$stmt->close();
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
		
		<style type="text/css">
			
			.blur-image {
				width:100%;
				height: 100%;

			}

			.image-loaded {

			}

			.header-section, .full-image, .image-loaded {
				width:100%;
				height: 100%;
				background-size: cover;
				color: #FFF;
				min-height: 100%;
				text-align: center;
				display: flex;
				align-items: center;
				background-attachment: fixed;
			}



		</style>




	</head>
	<body>
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



<!--
		<div class="tile" data-scale="1.1" data-image="ect/images/AC.jpg"></div>
-->
		<!-- FULL PAGE COVER IMAGE -->
		<section id='cover1' class='hvr-underline-reveal blur-image' data-src="ect/images/bg-light.jpg" style="background-position: center center;">
			<div class='full-image'>
			<div id='cover-caption'>
				<div class='container'>
					<div class='col-sm-10 offset-sm-1'>
						<h1 id='title'  class='cover-title mb-5'>RoC <b class='amherst'>AMHERST</b></h1>
						<p class='lead mb-5 letter-spaced-1 hidden-xs-down col-lg-6 offset-lg-3' style='background-color: rgba(73, 73, 73, 0.5)'>August 2017 - Welcome to RateOurCourses! Secure login & Mobile support are here!</p>

						<p class='lead mb-5 letter-spaced-1 hidden-sm-up'>Honest evalutions from real students</p>
						
						
						<form action='' class='form mt-5 pl-lg-5 pr-lg-5'>
							<div class='input-group col-sm-6 offset-sm-3'>
								<input type='text' style='background-color: rgba(255, 255, 255, 0.9); ' class='form-control form-control-lg hidden-sm-down'  placeholder='Courses, Professors...'>
								<input type='text' class='form-control hidden-md-up' placeholder='Courses, Professors...'>
								<span class='input-group-btn'>
									<button class='btn btn-secondary' type='button'><i class='fa fa-search'></i></button>
								</span>
							</div>
						</form>
						
					</div>
				</div>
			</div>
			</div>
		</section>


		<!-- FULL PAGE COVER IMAGE 
		<section id='cover' class='hvr-underline-reveal'>
			<div id='cover-caption'>
				<div class='container'>
					<div class='col-sm-10 offset-sm-1'>
						<h1 id='title'  class='cover-title mb-5'>RoC <b class='amherst'>AMHERST</b></h1>
						<p class='lead mb-5 letter-spaced-1 hidden-xs-down col-lg-6 offset-lg-3' style='background-color: rgba(73, 73, 73, 0.5)'>August 2017 - Welcome to RateOurCourses! Secure login & Mobile support are here!</p>

						<p class='lead mb-5 letter-spaced-1 hidden-sm-up'>Honest evalutions from real students</p>
						
						
						<form action='' class='form mt-5 pl-lg-5 pr-lg-5'>
							<div class='input-group col-sm-6 offset-sm-3'>
								<input type='text' style='background-color: rgba(255, 255, 255, 0.9); ' class='form-control form-control-lg hidden-sm-down'  placeholder='Courses, Professors...'>
								<input type='text' class='form-control hidden-md-up' placeholder='Courses, Professors...'>
								<span class='input-group-btn'>
									<button class='btn btn-secondary' type='button'><i class='fa fa-search'></i></button>
								</span>
							</div>
						</form>
						
					</div>
				</div>
			</div>
		</section>
		-->
		
	
	
		<!-- MAIN CONTENT -->
		<div id='page_content' class='container'>


			<!-- ABOUT -->
			<div id='about' class='pt-5 pb-5 pl-2 pr-2'>
				<h1 class='content-heading mb-5'>About RoC</h1>
				
				<p class='content-text'>Our goal is to vastly improve the process of choosing courses at Amherst college. By providing honest, course specific, student-produced reviews, we believe we can make the often-stressful process of course selection become more transparent.</p>
				<br>


			</div>

			<hr>





	      	<!-- RECENT FIGURES -->

			<div class='row pt-4 mb-5 mt-4 slideanim'>

				<div class='col-md-4 mb-3'>
					<i class='big-logo fa fa-pencil-square-o ml-4'></i>
					<h1 class='mt-4'><?php echo $currentStats['evals'] ?></h1>
					<h3 class='text-muted'>Reviews</h3>
					<div class='col-8 offset-2 hidden-md-up mt-5'>
						<hr>
					</div>
				</div>
				
				<div class='col-md-4 mb-3'>
					<i class='big-logo fa fa-university ml-2'></i>
					<h1 class='mt-4'><?php echo $currentStats['courses'] ?></h1>
					<h3 class='text-muted'>Courses</h3>
					<div class='col-8 offset-2 hidden-md-up mt-5'>
						<hr>
					</div>
				</div>

				<div class='col-md-4 mb-3'>
					<i class='big-logo fa fa-users'></i>
					<h1 class='mt-4'><?php echo $currentStats['profs'] ?></h1>
					<h3 class='text-muted'>Professors</h3>
				</div>
			</div>





			<hr>

			<!-- FIRST FEATURETTE WITH PICTURE -->
			<div class="row featurette mt-5 pt-4 pb-4 hidden-xsd-down">
	        	<div class="col-md-5 mb-5">
	          		<div class='featurette-image'></div>
	        	</div>
	        	<div class="col-md-7 featurette-main">
	        		<div class='col-sm-12'>
	          			<h2 class="featurette-heading">Scheduling is tough. <span class="text-muted">We're here to change that.</span></h2><br>
	          			<p class="featurette-subheading pl-3 pr-3">There are hundreds of courses to choose from every semester, spanning 41 departments at the College. 

	          				<span class='hidden-md-down'>
	          					Until now, there hasn’t been a way for you to survey those courses with specific qualities in mind. RoC Amherst allows you to rank courses by how interesting, difficult, and work-intensive they are, as well as by the quality of the professor; all according to your peers. You can even limit your search to a single department or professor.
	          				</span>

	          			</p>
	        		</div>
	       	 	</div>
	      	</div>



			<hr class='hidden-sm-down'>

			<!-- SECOND FEATURETTE WITH PICTURE -->
			<div class="row featurette mt-4 pt-4 pb-4 hidden-sm-down">
	        	<div class="col-md-7 featurette-main">
	        		<div class='col-sm-12'>
	          			<h2 class="featurette-heading">Your security is our priority. <span class="text-muted">Always.</span></h2><br>
	          			<p class="featurette-subheading pl-3 pr-3 mb-5">Only current students can view or add reviews on our site, and we do not track the authors of our reviews. You retain complete anonymity while gaining access to a valuable resource. 

	          				<span class='hidden-md-down'>
	          					We believe this leads to more honest and accurate reviews, as opposed to the college’s own review system. Learn more about our security practices <a href='documentation/security.html'>here.</a>
	          				</span>

	          			</p>
	        		</div>
	       	 	</div>
	        	<div class="col-md-5">
	          		<div class='featurette-image'></div>
	        	</div>
	      	</div>



	      	<hr>





			
			<!-- LATEST REVIEWS -->
			<div id='latest'>


				<div class='card-deck p-5 slideanim'>

					<div class='card'>
						<div class='card-header'>
							<span class='hidden-sm-down'>Recent Review - ECON 111</span>
							<b class='hidden-md-up'>Most Recent Review</b>
						</div>
						<div class='card-block bg-yellow-3'>
							<h4 class="card-title mb-4">An Introduction to Economics with Enviromental...</h4>
							<ul class="list-group">
							  	<li class="list-group-item justify-content-between">
							    	Interesting ?
							    	<b class='float-right text-muted'>5.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Difficulty
							    	<b class='float-right text-muted'>3.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Workload
							    	<b class='float-right text-muted'>3.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Professor
							    	<b class='float-right text-muted'>4.0</b>
							  	</li>
							</ul>
						</div>
						<div class='card-footer bg-green'>
							4 hours ago
						</div>
					</div>

					<div class='card hidden-sm-down'>
						<div class='card-header'>
							ECON 111
						</div>
						<div class='card-block bg-yellow-2'>
							<h4 class="card-title mb-4">An Introduction to Economics with Enviromental...</h4>
							<ul class="list-group">
							  	<li class="list-group-item justify-content-between">
							    	Interesting ?
							    	<b class='float-right text-muted text-muted'>5.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Difficulty
							    	<b class='float-right text-muted'>3.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Workload
							    	<b class='float-right text-muted'>3.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Professor
							    	<b class='float-right text-muted'>4.0</b>
							  	</li>
							</ul>
						</div>
						<div class='card-footer bg-green'>
							4 hours ago
						</div>
					</div>



					<div class='card hidden-md-down'>
						<div class='card-header'>
							ECON 111
						</div>
						<div class='card-block bg-yellow-1'>
							<h4 class="card-title mb-4">An Introduction to Economics with Enviromental...</h4>
							<ul class="list-group">
							  	<li class="list-group-item justify-content-between">
							    	Interesting ?
							    	<b class='float-right text-muted text-muted'>5.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Difficulty
							    	<b class='float-right text-muted'>3.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Workload
							    	<b class='float-right text-muted'>3.0</b>
							  	</li>
							  	<li class="list-group-item justify-content-between">
							    	Professor
							    	<b class='float-right text-muted'>4.0</b>
							  	</li>
							</ul>
						</div>
						<div class='card-footer bg-green'>
							4 hours ago
						</div>
					</div>

				</div>

			</div>




			<hr class='hidden-xs-down'>
			<!-- MEET THE TEAM -->
			<div id='team' class='p-5 mb-5 hidden-xs-down slideanim'>
				<h1 class='content-heading mb-4'>Meet the Team</h1>
				<div class='row'>
					<div class='col-lg-3 mt-5'>
						<img class="rounded-circle mb-3" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="120" height="120">
						<h2>Patrick Frenett</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
					<div class='col-lg-3 mt-5'>
						<img class="rounded-circle mb-3" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="120" height="120">
						<h2>Lucas Sheiner</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
					<div class='col-lg-3 mt-5'>
						<img class="rounded-circle mb-3" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="120" height="120">
						<h2>Seumas Maceil</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
					<div class='col-lg-3 mt-5'>
						<img class="rounded-circle mb-3" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" width="120" height="120">
						<h2>Robert Schwarz</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
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














	  	$('.tile')
	    // tile mouse actions
	    .on('mouseover', function(){
	      $(this).children('.photo').css({'transform': 'scale('+ $(this).attr('data-scale') +')'});
	    })
	    .on('mouseout', function(){
	      $(this).children('.photo').css({'transform': 'scale(1)'});
	    })
	    .on('mousemove', function(e){
	      $(this).children('.photo').css({'transform-origin': ((e.pageX - $(this).offset().left) / $(this).width()) * 100 + '% ' + ((e.pageY - $(this).offset().top) / $(this).height()) * 100 +'%'});
	    })
	    // tiles set up
	    .each(function(){
	      $(this)
	        // add a photo container
	        .append('<div class="photo"></div>')
	        // some text just to show zoom level on current item in this example
	        .append('<div class="txt"><div class="x">'+ $(this).attr('data-scale') +'x</div>ZOOM ON<br>HOVER</div>')
	        // set up a background image for each tile based on data-image attribute
	        .children('.photo').css({'background-image': 'url('+ $(this).attr('data-image') +')'});
	    })


var a = document.querySelector('.blur-image');

document.addEventListener("DOMContentLoaded", function() {
  if (!a) return !1;
  var b = a.getAttribute("data-src"),
      c = document.querySelector('.full-image'),
      img = new Image;

  img.src = b;
  img.onload = function () {
    c.classList.add('image-loaded'),
    c.style.backgroundImage = 'url(' + b + ')';
    c.style.backgroundPosition = 'center';
    c.style.backgroundRepeat = 'no-repeat';
    c.style.backgroundSize = 'cover';
  };
});

    </script>


  </body>
</html>