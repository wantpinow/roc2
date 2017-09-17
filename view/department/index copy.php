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

					<span class='lead roc-text-gray'>Below is a list of all <?php echo $totalCount ?> evaluations for the <?php echo $dep ?> department. The ratings are based on an average of all evaluations of that course. To look specific evaluations (with comments) in detail, click on the course.</span>

				</div>
				<br>
				<div class='pr-lg-5 pl-lg-5'>

<div class='card pt-4 pb-4'>

<!--<table id="example" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">-->
<!--
<table id="example" class="display nowrap" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th>Course Name</th>
            <th>Interesting?</th>
            <th>Difficulty</th>
            <th>Workload</th>
            <th>Professor</th>
         </tr>
      </thead>
      <tbody id='tableBody'>
         <tr>
            <td>ECON 111 : An introduction to economics</td>
            <td>5</td>
            <td>3</td>
            <td>2.2</td>
            <td>3.4</td>
         </tr>
      </tbody>
   </table>

   -->


<table id="example" class="display nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Seq.</th>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Seq.</th>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>2</td>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>2011/04/25</td>
                <td>$320,800</td>
            </tr>
            <tr>
                <td>22</td>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>2011/07/25</td>
                <td>$170,750</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Ashton Cox</td>
                <td>Junior Technical Author</td>
                <td>San Francisco</td>
                <td>2009/01/12</td>
                <td>$86,000</td>
            </tr>
            <tr>
                <td>41</td>
                <td>Cedric Kelly</td>
                <td>Senior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>2012/03/29</td>
                <td>$433,060</td>
            </tr>
            <tr>
                <td>55</td>
                <td>Airi Satou</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>2008/11/28</td>
                <td>$162,700</td>
            </tr>
            <tr>
                <td>21</td>
                <td>Brielle Williamson</td>
                <td>Integration Specialist</td>
                <td>New York</td>
                <td>2012/12/02</td>
                <td>$372,000</td>
            </tr>
            <tr>
                <td>46</td>
                <td>Herrod Chandler</td>
                <td>Sales Assistant</td>
                <td>San Francisco</td>
                <td>2012/08/06</td>
                <td>$137,500</td>
            </tr>
            <tr>
                <td>50</td>
                <td>Rhona Davidson</td>
                <td>Integration Specialist</td>
                <td>Tokyo</td>
                <td>2010/10/14</td>
                <td>$327,900</td>
            </tr>
            <tr>
                <td>26</td>
                <td>Colleen Hurst</td>
                <td>Javascript Developer</td>
                <td>San Francisco</td>
                <td>2009/09/15</td>
                <td>$205,500</td>
            </tr>
            <tr>
                <td>18</td>
                <td>Sonya Frost</td>
                <td>Software Engineer</td>
                <td>Edinburgh</td>
                <td>2008/12/13</td>
                <td>$103,600</td>
            </tr>
            <tr>
                <td>13</td>
                <td>Jena Gaines</td>
                <td>Office Manager</td>
                <td>London</td>
                <td>2008/12/19</td>
                <td>$90,560</td>
            </tr>
            <tr>
                <td>23</td>
                <td>Quinn Flynn</td>
                <td>Support Lead</td>
                <td>Edinburgh</td>
                <td>2013/03/03</td>
                <td>$342,000</td>
            </tr>
            <tr>
                <td>14</td>
                <td>Charde Marshall</td>
                <td>Regional Director</td>
                <td>San Francisco</td>
                <td>2008/10/16</td>
                <td>$470,600</td>
            </tr>
            <tr>
                <td>12</td>
                <td>Haley Kennedy</td>
                <td>Senior Marketing Designer</td>
                <td>London</td>
                <td>2012/12/18</td>
                <td>$313,500</td>
            </tr>
            <tr>
                <td>54</td>
                <td>Tatyana Fitzpatrick</td>
                <td>Regional Director</td>
                <td>London</td>
                <td>2010/03/17</td>
                <td>$385,750</td>
            </tr>
            <tr>
                <td>37</td>
                <td>Michael Silva</td>
                <td>Marketing Designer</td>
                <td>London</td>
                <td>2012/11/27</td>
                <td>$198,500</td>
            </tr>
            <tr>
                <td>32</td>
                <td>Paul Byrd</td>
                <td>Chief Financial Officer (CFO)</td>
                <td>New York</td>
                <td>2010/06/09</td>
                <td>$725,000</td>
            </tr>
            <tr>
                <td>35</td>
                <td>Gloria Little</td>
                <td>Systems Administrator</td>
                <td>New York</td>
                <td>2009/04/10</td>
                <td>$237,500</td>
            </tr>
            <tr>
                <td>48</td>
                <td>Bradley Greer</td>
                <td>Software Engineer</td>
                <td>London</td>
                <td>2012/10/13</td>
                <td>$132,000</td>
            </tr>
            <tr>
                <td>45</td>
                <td>Dai Rios</td>
                <td>Personnel Lead</td>
                <td>Edinburgh</td>
                <td>2012/09/26</td>
                <td>$217,500</td>
            </tr>
            <tr>
                <td>17</td>
                <td>Jenette Caldwell</td>
                <td>Development Lead</td>
                <td>New York</td>
                <td>2011/09/03</td>
                <td>$345,000</td>
            </tr>
            <tr>
                <td>57</td>
                <td>Yuri Berry</td>
                <td>Chief Marketing Officer (CMO)</td>
                <td>New York</td>
                <td>2009/06/25</td>
                <td>$675,000</td>
            </tr>
            <tr>
                <td>29</td>
                <td>Caesar Vance</td>
                <td>Pre-Sales Support</td>
                <td>New York</td>
                <td>2011/12/12</td>
                <td>$106,450</td>
            </tr>
            <tr>
                <td>56</td>
                <td>Doris Wilder</td>
                <td>Sales Assistant</td>
                <td>Sidney</td>
                <td>2010/09/20</td>
                <td>$85,600</td>
            </tr>
            <tr>
                <td>36</td>
                <td>Angelica Ramos</td>
                <td>Chief Executive Officer (CEO)</td>
                <td>London</td>
                <td>2009/10/09</td>
                <td>$1,200,000</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Gavin Joyce</td>
                <td>Developer</td>
                <td>Edinburgh</td>
                <td>2010/12/22</td>
                <td>$92,575</td>
            </tr>
            <tr>
                <td>51</td>
                <td>Jennifer Chang</td>
                <td>Regional Director</td>
                <td>Singapore</td>
                <td>2010/11/14</td>
                <td>$357,650</td>
            </tr>
            <tr>
                <td>20</td>
                <td>Brenden Wagner</td>
                <td>Software Engineer</td>
                <td>San Francisco</td>
                <td>2011/06/07</td>
                <td>$206,850</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Fiona Green</td>
                <td>Chief Operating Officer (COO)</td>
                <td>San Francisco</td>
                <td>2010/03/11</td>
                <td>$850,000</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Shou Itou</td>
                <td>Regional Marketing</td>
                <td>Tokyo</td>
                <td>2011/08/14</td>
                <td>$163,000</td>
            </tr>
            <tr>
                <td>39</td>
                <td>Michelle House</td>
                <td>Integration Specialist</td>
                <td>Sidney</td>
                <td>2011/06/02</td>
                <td>$95,400</td>
            </tr>
            <tr>
                <td>40</td>
                <td>Suki Burks</td>
                <td>Developer</td>
                <td>London</td>
                <td>2009/10/22</td>
                <td>$114,500</td>
            </tr>
            <tr>
                <td>47</td>
                <td>Prescott Bartlett</td>
                <td>Technical Author</td>
                <td>London</td>
                <td>2011/05/07</td>
                <td>$145,000</td>
            </tr>
            <tr>
                <td>52</td>
                <td>Gavin Cortez</td>
                <td>Team Leader</td>
                <td>San Francisco</td>
                <td>2008/10/26</td>
                <td>$235,500</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Martena Mccray</td>
                <td>Post-Sales support</td>
                <td>Edinburgh</td>
                <td>2011/03/09</td>
                <td>$324,050</td>
            </tr>
            <tr>
                <td>24</td>
                <td>Unity Butler</td>
                <td>Marketing Designer</td>
                <td>San Francisco</td>
                <td>2009/12/09</td>
                <td>$85,675</td>
            </tr>
            <tr>
                <td>38</td>
                <td>Howard Hatfield</td>
                <td>Office Manager</td>
                <td>San Francisco</td>
                <td>2008/12/16</td>
                <td>$164,500</td>
            </tr>
            <tr>
                <td>53</td>
                <td>Hope Fuentes</td>
                <td>Secretary</td>
                <td>San Francisco</td>
                <td>2010/02/12</td>
                <td>$109,850</td>
            </tr>
            <tr>
                <td>30</td>
                <td>Vivian Harrell</td>
                <td>Financial Controller</td>
                <td>San Francisco</td>
                <td>2009/02/14</td>
                <td>$452,500</td>
            </tr>
            <tr>
                <td>28</td>
                <td>Timothy Mooney</td>
                <td>Office Manager</td>
                <td>London</td>
                <td>2008/12/11</td>
                <td>$136,200</td>
            </tr>
            <tr>
                <td>34</td>
                <td>Jackson Bradshaw</td>
                <td>Director</td>
                <td>New York</td>
                <td>2008/09/26</td>
                <td>$645,750</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Olivia Liang</td>
                <td>Support Engineer</td>
                <td>Singapore</td>
                <td>2011/02/03</td>
                <td>$234,500</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Bruno Nash</td>
                <td>Software Engineer</td>
                <td>London</td>
                <td>2011/05/03</td>
                <td>$163,500</td>
            </tr>
            <tr>
                <td>31</td>
                <td>Sakura Yamamoto</td>
                <td>Support Engineer</td>
                <td>Tokyo</td>
                <td>2009/08/19</td>
                <td>$139,575</td>
            </tr>
            <tr>
                <td>11</td>
                <td>Thor Walton</td>
                <td>Developer</td>
                <td>New York</td>
                <td>2013/08/11</td>
                <td>$98,540</td>
            </tr>
            <tr>
                <td>10</td>
                <td>Finn Camacho</td>
                <td>Support Engineer</td>
                <td>San Francisco</td>
                <td>2009/07/07</td>
                <td>$87,500</td>
            </tr>
            <tr>
                <td>44</td>
                <td>Serge Baldwin</td>
                <td>Data Coordinator</td>
                <td>Singapore</td>
                <td>2012/04/09</td>
                <td>$138,575</td>
            </tr>
            <tr>
                <td>42</td>
                <td>Zenaida Frank</td>
                <td>Software Engineer</td>
                <td>New York</td>
                <td>2010/01/04</td>
                <td>$125,250</td>
            </tr>
            <tr>
                <td>27</td>
                <td>Zorita Serrano</td>
                <td>Software Engineer</td>
                <td>San Francisco</td>
                <td>2012/06/01</td>
                <td>$115,000</td>
            </tr>
            <tr>
                <td>49</td>
                <td>Jennifer Acosta</td>
                <td>Junior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>2013/02/01</td>
                <td>$75,650</td>
            </tr>
            <tr>
                <td>15</td>
                <td>Cara Stevens</td>
                <td>Sales Assistant</td>
                <td>New York</td>
                <td>2011/12/06</td>
                <td>$145,600</td>
            </tr>
            <tr>
                <td>9</td>
                <td>Hermione Butler</td>
                <td>Regional Director</td>
                <td>London</td>
                <td>2011/03/21</td>
                <td>$356,250</td>
            </tr>
            <tr>
                <td>25</td>
                <td>Lael Greer</td>
                <td>Systems Administrator</td>
                <td>London</td>
                <td>2009/02/27</td>
                <td>$103,500</td>
            </tr>
            <tr>
                <td>33</td>
                <td>Jonas Alexander</td>
                <td>Developer</td>
                <td>San Francisco</td>
                <td>2010/07/14</td>
                <td>$86,500</td>
            </tr>
            <tr>
                <td>43</td>
                <td>Shad Decker</td>
                <td>Regional Director</td>
                <td>Edinburgh</td>
                <td>2008/11/13</td>
                <td>$183,000</td>
            </tr>
            <tr>
                <td>16</td>
                <td>Michael Bruce</td>
                <td>Javascript Developer</td>
                <td>Singapore</td>
                <td>2011/06/27</td>
                <td>$183,000</td>
            </tr>
            <tr>
                <td>19</td>
                <td>Donna Snider</td>
                <td>Customer Support</td>
                <td>New York</td>
                <td>2011/01/25</td>
                <td>$112,000</td>
            </tr>
        </tbody>
    </table>

   </div>
				</div>

			</div>
		</div>

		
		
		
		
		
	    <!-- jQuery first, then Tether, then Bootstrap JS. -->
	    <script src="/roc2/ect/js/jquery-3.2.1.min.js"></script>
	    <script src="/roc2/ect/js/jquery-ui.min.js"></script>
	    <script src="/roc2/ect/js/datatables.js"></script>
	    <script src="/roc2/ect/js/datatables.bootstrap.js"></script>
	    <script src="/roc2/ect/js/tether.min.js"></script>
	    <script src="/roc2/ect/bootstrap_4/js/bootstrap.min.js"></script>


	    <!-- External JavaScript -->
	    <script src='/roc2/ect/js/search.js'></script>

	    <!-- Page specific custom JS -->
	    <script type="text/javascript">
	    	// get results 
	    	var dep = <?php echo  "'$dep'" ?>;
	    	var courses = <?php echo json_encode($reviews) ?>;
	    	console.log(courses);
	    	console.log(departments);
	    	console.log(dep);

	    	
	    	var depName = dep;
	    	if(departments[dep] != null) {
	    		depName = departments[dep].name;
	    	}
	    	console.log(depName);
	    	$('#departmentName').text(depName);

	    	/*
			$(document).ready(function() {
			    $('#example').DataTable( {
			        responsive: true
			    });
			});
			*/

			$(document).ready(function() {
    var table = $('#example').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(3)'
        },
        responsive: true
    } );
} );


	    </script>

  	</body>
</html>