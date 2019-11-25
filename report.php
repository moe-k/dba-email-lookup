<?
//This snippet is used to fill up select XHTML drop down on the form so that the user who logged the email to DBA is logged as well

	//connect to db
	$myCon = mysql_connect("abuseteam.mscorp.com","moey0601","moho0601");
	//$myCon = mysql_connect("localhost","root","");
	
	//check if connection was successful
	if (!$myCon)
	{
	  include("reportTemplateErrorTop.php");
	  die('Could not connect: ' . mysql_error());
	  include("reportTemplateErrorBottom.php");
	}
	
	//select database to read records out of
	if(!mysql_select_db("email_logger", $myCon))
	{
		include("reportTemplateErrorTop.php");
		die ("couldnt select DB".mysql_error());
		include("reportTemplateErrorBottom.php");
	}
	
	//build sql string to query the database
	$sql ="select * from users";
	//variable that will hold concatenated select drop down list that is populated from the database
	$selectoptions="";
	
	//try to execute the query, if problem than write out error 
	if(!mysql_query($sql))
	{
			include("reportTemplateErrorTop.php");
			die ("couldn't execute query to DB.".mysql_error());
			include("reportTemplateErrorBottom.php");
	}
	//if above successful then we can process query to read users from db
	else
	{			
			//Debug: echo $sql
			//execute select query and strore the results 
			$results = mysql_query($sql);
			//loop through all records returned and concatenate options drop down by filling up $options variable
			while ($row=mysql_fetch_array($results)) 
			{			    
			    $username=$row["username"];			   
				$options.="<option value=\"$username\">".$username."</options>";
			}				
	}	
	//close connection to db
	if(!mysql_close($myCon)) 
	{
		include("reportTemplateErrorTop.php");
		die("Could't close connection to DB.".mysql_error());	
		include("reportTemplateErrorBottom.php");
	}
?>
<html>
<head>
<title>Run Report</title>
<script language="javascript" type="text/javascript" src="js/datetimepicker.js"></script>
<script type="text/javascript" src="js/report.js"></script>
<style>
	#container
	{
		position:absolute;
		left:5%;
		right:5%
	}
	p
	{
		display:inline;	
		font-family:arial,, helvetica, sans-serif;
		color:#0000CD;
	}
	body
	{
		background-color:#FFF5EE;
	}
	h1
	{
		display:block;
		color:#000080;
	}
	table tr td
	{
		padding:0.5cm;
	}
	table 
	{
		border-style:dotted;
	}
	p.red
	{
		color:red;
	}
</style>
</head>
<body onload="resetForm()">
<center><h1>Run Report</h1></center>
	<br />
	<div id="container">	
	<center>	
		<input type="button" value="Go Home" onclick="javascript:window.location='index.htm'">
		&nbsp;
		<input type="button" value="Input Search" onclick="javascript:window.location='inputSearchForm.php'">
		&nbsp;
		<input type="button" value="Advanced Reports" onclick="javascript:window.location='advancedReportForm.php'">
	    <table border="3" bordercolor="#191970">
		<tr>
		    <td>			
			<br />
			<br />
			    <form name="reportForm" method="POST" action="makereport.php">
					<p>Run a report username.</p>
					<fieldset>					
						<legend>Username:</legend>					
						<select name="reportinguserDropdown" onclick="frmDisableOtherFields(this.name)">
								 <option value =""></option>
								 <?=$options?> 
						</select>
						<a href="" onclick="javascript:window.open('help/userSearchHelp.htm','_blank','width=300,height=300');return false;">?</a>
						<input type="button" value="Search" onclick="runReport('userDropFromReportphppage')">
					</fieldset>
					<br />
					<p>Run a report by picking date from calendar.</p>
					<fieldset>					
						<legend>Date Search:</legend>					
						<input type="Text" id="calendar" maxlength="25" size="25" name="dateSearch" onclick="frmDisableOtherFields(this.name)"><a href="javascript:NewCal('calendar','MMDDYYYY',false,24)"><img src="images/cal.gif" width="18" height="18" border="0" alt="Pick a date"></a>
						<a href="" onclick="javascript:window.open('help/singleDateSearchHelp.htm','_blank','width=300,height=300');return false;">?</a>
						<input type="button" value="Search" onclick="runReport('fromCalendarSearch')">
					</fieldset>
					<br />
					<p>Run a report by picking date range.</p>
					<fieldset>					
						<legend>Date Range Search:</legend>					
						<input type="Text" id="drangecalendar1" maxlength="25" size="25" name="dateRangeLeft" onclick="frmDisableOtherFields(this.name)"><a href="javascript:NewCal('drangecalendar1','MMddyyyy',false,24)"><img src="images/cal.gif" width="18" height="18" border="0" alt="Pick a date"></a> -
						<input type="Text" id="drangecalendar2" maxlength="25" size="25" name="dateRangeRight" onclick="frmDisableOtherFields(this.name)"><a href="javascript:NewCal('drangecalendar2','MMddyyyy',false,24)"><img src="images/cal.gif" width="18" height="18" border="0" alt="Pick a date"></a> 
						<a href="" onclick="javascript:window.open('help/dateRangeSearchHelp.htm','_blank','width=300,height=300');return false;">?</a>
						<input type="button" value="Search" onclick="runReport('dateRangeSearch')">
					</fieldset>
					<br />		
					<p>Run a report by keywords</>
					<fieldset>					
						<legend>Keyword</legend>
						<textarea rows="20" cols="55" name="keywordSearch" onclick="frmDisableOtherFields(this.name)"></textarea>
						<a href="" onclick="javascript:window.open('help/keyWordSearchHelp.htm','_blank','width=300,height=300');return false;">?</a>
						<br /><br />						
						<input type="radio" name="keywordsearch" value="Email"><p>Email</p>
						
						<input type="radio" name="keywordsearch" value="Headline"><p>Headline</p>
						
						<input type="radio" name="keywordsearch" value="About Me"><p>About Me</p>
						
						<input type="radio" name="keywordsearch" value="Like To Meet"><p>Like To Meet</p>
						
						<input type="radio" name="keywordsearch" value="Music"><p>Music</p>
						
						<input type="radio" name="keywordsearch" value="Books"><p>Books</p>
						
						<input type="radio" name="keywordsearch" value="Movies"><p>Movies</p>
						
						<input type="radio" name="keywordsearch" value="General"><p>General</p>
						<br />
						<input type="button" value="Search" onclick="runReport('keywordSearch')">
					</fieldset>
					<br />
					<input type="reset" value="Clear Form" onclick="resetForm()">					
				</form>
		    </td>
		</tr>		
	      </table>
	      </center>
	</div>
	
</body>
</html>