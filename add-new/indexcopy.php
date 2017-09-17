<?php

$doc_root = $_SERVER["DOCUMENT_ROOT"];
require_once $doc_root . '/roc2/ect/php/db_init.php';

if($uid = loggedIn()) {
	$userDetails = getProfileInfo($uid);
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
		<link rel='stylesheet' href='/roc2/ect/css/RateYo/jquery.rateyo.min.css'>

		<!-- .json files -->
		<script type="text/javascript" src='/roc2/ect/js/json/departments.json'></script>
		
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
							<a class="nav-link active" href="/roc2/add-new"><i class='fa fa-pencil-square-o mr-2' style='position: relative; top: 1px'></i>Add New</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/roc2/show-all"><i class='fa fa-book mr-2' style='position: relative; top: 1px'></i>Show All</a>
						</li>
					</ul>
					<form class="form-inline my-2 my-lg-0 mr-4 hidden-md-down" action='/roc2/search/' method=''>
						<div class='input-group'>
							<input class="form-control searchBar" type="text" placeholder="Search for courses...">
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






		<div style='min-height: 100%;'>
			<div class='container pl-xs-5 pr-xs-5 pt-4 pb-4'>
			


				<div class='card col-md-8 offset-md-2 text-center pr-sm-5 pl-sm-5'>
					<h1 class='mt-4 mb-sm-4 mb-3 content-heading'>Add a New Evaluation</h1>

					<span class='text-muted'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. <a href=''>A link.</a></span>

					<div class='mt-3 mb-3'>
						<hr>
					</div>

					<form name='foo' novalidate>
						<div class='form-group row'>
							<label for='add_department' class='col-sm-4 col-form-label'>Department : </label>
							<div class='col-sm-8'>
								<div class='input-group'>
									<select class="custom-select form-control" id="add_department">  
									    <option value=''>Choose a Department...</option>
									    <!-- INSERT DEPARTMENTS FROM FILE (use base JS as JQuery not loaded) -->
										    <script type="text/javascript">
										    	for(var key in departments) {
										    		var optionNode = document.createElement('option');
										    		var textNode = document.createTextNode(departments[key].name);
										    		optionNode.appendChild(textNode);
										    		optionNode.value = key;
										    		document.getElementById('add_department').appendChild(optionNode);
										    	}
										    </script>
									    <!-- END JS -->
									</select>
								</div>
							</div>
						</div>
						<div class='form-group row'>
							<label for='add_course' class='col-sm-4 col-form-label'>Course Name : </label>
							<div class='col-sm-8'>
								<div class='input-group'>
									<select class="custom-select form-control" id="add_course">  
									    <option value=''>Choose a Course...</option>
									</select>
								</div>
							</div>
						</div>
						<div class='form-group row mb-4'>
							<label for='add_professor_name' class='col-sm-4 col-form-label'>Professor Name : </label>
							<div class='col-sm-8'>
								<div class='input-group'>
									<select class="custom-select form-control" id="add_professor_name">  
									    <option value=''>Choose a Professor...</option>
									</select>
									<input id='other_prof_name' type='text' class='form-control' placeholder='Professor Name...' style='display:none;'>
								</div>
								<!--
								<div class='input-group'>
									<button class='btn btn-secondary text-muted form-control mt-2' type='button'><span><i class='fa fa-plus mr-2'></i> Add Another Professor</span></button>
								</div>
								-->
							</div>
						</div>
						<div class='form-group row'>
							<div class='col-sm-12'>
								<label for='' class='col-sm-12 col-form-label hidden-lg-up'>Semester Taken : </label>
								<div class="btn-group mt-3" role="group" aria-label="First group">
								    <button id='semester_down' onclick='moveSemesterDown()' type="button" class="btn btn-secondary" style="cursor: pointer;"><i class='fa fa-arrow-left'></i></button>
									<button id='semester_1' onclick='changeSemester(this)' value='Fall 16' type="button" class="btn btn-secondary hidden-md-down">Fall 16</button>
								    <button id='semester_2' onclick='changeSemester(this)' value='Spring 17' type="button" class="btn btn-secondary" style='width:103px'>Spring 17</button>
									<button id='semester_3' onclick='changeSemester(this)' value='Fall 17' type="button" class="btn btn-secondary" style='width:103px'>Fall 17</button>
								    <button id='semester_up' onclick='moveSemesterUp()' type="button" class="btn btn-secondary" style="cursor: pointer;" ><i class='fa fa-arrow-right'></i></button>
								    <input id='semester' name='semester' type='text' hidden>
								</div>
							</div>
						</div>

						<hr class='mt-5'>

						<div class='form-group row pl-lg-4 pr-lg-4'>
							<div class='col-lg-6 mt-4 pr-xl-0 pl-lg-5'>
								<h5 data-toggle="tooltip" data-placement="top" title="How interesing the class was, how engaging you found the material to be" style='cursor: default;'>Interesting?</h5>
								<div id="interesting_rateYo" class='mt-3' style="margin-left: auto; margin-right: auto"></div>
								<input id='interesting' name='interesting' type='number' hidden>
							</div>
							<div class='col-lg-6 mt-4 pl-xl-0 pr-lg-5'>
								<h5 data-toggle="tooltip" data-placement="top" title="How hard YOU found the class. This includes readings, tests, projects, ect." style='cursor: default;'>Difficulty</h5>
								<div id="difficulty_rateYo" class='mt-3' style="margin-left: auto; margin-right: auto"></div>
								<input id='difficulty' name='difficulty' type='number' hidden>
							</div>
						</div>
						<div class='form-group row pl-lg-4 pr-lg-4'>
							<div class='col-lg-6 mt-4 pr-xl-0 pl-lg-5'>
								<h5 data-toggle="tooltip" data-placement="top" title="How much time YOU spent on the class - including preparation for tests" style='cursor: default;'>Workload</h5>
								<div id="workload_rateYo" class='mt-3' style="margin-left: auto; margin-right: auto"></div>
								<input id='workload' name='workload' type='number' hidden>
							</div>
							<div class='col-lg-6 mt-4 pl-xl-0 pr-lg-5'>
								<h5 data-toggle="tooltip" data-placement="top" title="The quality of the professor's teaching - both in this class and overall as an educator" style='cursor: default;'>Professor Rating</h5>
								<div id="prof_rating_rateYo" class='mt-3' style="margin-left: auto; margin-right: auto"></div>
								<input id='prof_rating' name='prof_rating' type='number' hidden>
							</div>
						</div>

						<hr class='mt-5 mb-5'>

						<div class='input-group col-lg-6 offset-lg-3 mb-4'>
							<select class="form-control custom-select" id="add_grade" name='grade'>  
							    <option value='-'>Enter your Grade (optional)</option>
							    <option value='A+'>A+</option>
							    <option value='A'>A</option>
							    <option value='A-'>A-</option>
							    <option value='B+'>B+</option>
							    <option value='B'>B</option>
							    <option value='B-'>B-</option>
							    <option value='C+'>C+</option>
							    <option value='C'>C</option>
							    <option value='C-'>C-</option>
							    <option value='D'>D</option>
							    <option value='F'>F</option>
							    <option value='Pass'>Pass</option>
							</select>
						</div>

						<div class='form-group col-lg-10 offset-lg-1'>
							<textarea class="form-control" rows="4" id="comment" placeholder="Enter a Comment (optional)..."></textarea>
						</div>
						


						<button type="submit" class="btn btn-primary mb-5 mt-4">Add Evalutation</button>
						
						

					</form>


					
				</div>
			</div>
		</div>

		
			
	
	

	

				


	
	
	
	
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js"></script>
    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js"></script>


    <!-- Additional JQuery Plugins -->
    <script src="/roc2/ect/css/RateYo/jquery.rateyo.min.js"></script>

    <!-- External JavaScript -->
    <script src='/roc2/ect/js/search.js'></script>





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

		$(function () {

		  $("#interesting_rateYo").rateYo({
		    halfStar: true,
		    starWidth: "40px"
		  });
		  $("#difficulty_rateYo").rateYo({
		    halfStar: true,
		    starWidth: "40px"
		  });
		  $("#workload_rateYo").rateYo({
		    halfStar: true,
		    starWidth: "40px"
		  });
		  $("#prof_rating_rateYo").rateYo({
		    halfStar: true,
		    starWidth: "40px"
		  });


		  $("#interesting_rateYo").click(function(){
		  	var rating = $("#interesting_rateYo").rateYo("rating");
		  	document.getElementById('interesting').value = rating;
		  });
		  $("#difficulty_rateYo").click(function(){
		  	var rating = $("#difficulty_rateYo").rateYo("rating");
		  	document.getElementById('difficulty').value = rating;
		  });
		  $("#workload_rateYo").click(function(){
		  	var rating = $("#workload_rateYo").rateYo("rating");
		  	document.getElementById('workload').value = rating;
		  });
		  $("#prof_rating_rateYo").click(function(){
		  	var rating = $("#prof_rating_rateYo").rateYo("rating");
		  	document.getElementById('prof_rating').value = rating;
		  });
		});


		$(function () {
		  $('[data-toggle="tooltip"]').tooltip();
		});


		$('#add_professor_name').change(function() {
			if($(this).val() == 'other') {
				$('#other_prof_name').show();
			} else {
				$('#other_prof_name').hide();
			}
		});



		$('#add_department').change(function() {
			var thisDepartment = $('#add_department').val();

			$.post('/roc2/ect/ajax/getCoursesAndProfs.php', {dep: thisDepartment}, function(data) {
				var courseAndFacultyData = JSON.parse(data);
				var courses = courseAndFacultyData.courses;
				var faculty = courseAndFacultyData.faculty;

				faculty = faculty.sort(compareSurnames);

				var addCourseNode = $('#add_course')
				addCourseNode.empty();

				if(Object.keys(courses).length === 0) {
					addCourseNode.append($('<option />').val('').text('Choose a Course...'));
				} else {
					for(var num in courses) {
						addCourseNode.append($('<option />').val(num).text(courses[num]));
					}
				}

				
				var addProfNode = $('#add_professor_name')
				addProfNode.empty();

				if(Object.keys(faculty).length === 0) {
					addProfNode.append($('<option />').val('').text('Choose a Professor...'));
				} else {
					for(var key in faculty) {
						addProfNode.append($('<option />').val(faculty[key]).text(faculty[key]));
					}
					addProfNode.append($('<option />').val('other').text('(Other Professor)'));	
				}
				

			})


		});


		

		function compareSurnames(a, b) {
		    var splitA = a.split(" ");
		    var splitB = b.split(" ");
		    var lastA = splitA[splitA.length - 1];
		    var lastB = splitB[splitB.length - 1];

		    if (lastA < lastB) return -1;
		    if (lastA > lastB) return 1;
		    return 0;
		}




		var semester;
		var semesterOptions = ['Fall 14', 'Spring 15', 'Fall 15', 'Spring 16', 'Fall 16', 'Spring 17', 'Fall 17'];

		function changeSemester(newSemester) {
			if(semester == null) {
				semester = newSemester;
			} else {
				semester.style.backgroundColor = 'rgb(255,255,255)';
				semester = newSemester;
			}
			semester.style.backgroundColor = 'rgb(200,200,200)';
			document.getElementById('semester').value = semester.value[0] + semester.value[semester.value.length-2] + semester.value[semester.value.length-1];
		}




		function moveSemesterDown() {

			var sem_1 = document.getElementById('semester_1');
			var sem_2 = document.getElementById('semester_2');
			var sem_3 = document.getElementById('semester_3');

			if(sem_1.value == semesterOptions[0]) {
				return;
			} else {
				var currVal = sem_1.value;
				var currIndex;
				for(var i=0; i<semesterOptions.length; i++) {
					if(semesterOptions[i] == currVal) {
						currIndex = i;
						break;
					}
				}

				sem_1.innerHTML = semesterOptions[i-1];
				sem_1.value = semesterOptions[i-1];
				sem_1.style.backgroundColor = 'rgb(255,255,255)';

				sem_2.innerHTML = semesterOptions[i];
				sem_2.value = semesterOptions[i];
				sem_2.style.backgroundColor = 'rgb(255,255,255)';

				sem_3.innerHTML = semesterOptions[i+1];
				sem_3.value = semesterOptions[i+1];
				sem_3.style.backgroundColor = 'rgb(255,255,255)';

				semester = null;
				document.getElementById('semester').value = null;
			}
			
		}



		function moveSemesterUp() {

			var sem_1 = document.getElementById('semester_1');
			var sem_2 = document.getElementById('semester_2');
			var sem_3 = document.getElementById('semester_3');

			if(sem_3.value == semesterOptions[semesterOptions.length-1]) {
				return;
			} else {
				var currVal = sem_3.value;
				var currIndex;
				for(var i=0; i<semesterOptions.length; i++) {
					if(semesterOptions[i] == currVal) {
						currIndex = i;
						break;
					}
				}

				sem_1.innerHTML = semesterOptions[i-1];
				sem_1.value = semesterOptions[i-1];
				sem_1.style.backgroundColor = 'rgb(255,255,255)';

				sem_2.innerHTML = semesterOptions[i];
				sem_2.value = semesterOptions[i];
				sem_2.style.backgroundColor = 'rgb(255,255,255)';

				sem_3.innerHTML = semesterOptions[i+1];
				sem_3.value = semesterOptions[i+1];
				sem_3.style.backgroundColor = 'rgb(255,255,255)';

				semester = null;
				document.getElementById('semester').value = null;
			}
			
		}







    </script>


  </body>
</html>