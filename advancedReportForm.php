<?
/*
Filename: inputSearchForm.php
Purpose: To populate dropdown in this form 
Author: Mohamad Khalili
Date: 3-3-2008
*/
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
	$sql ="SELECT * FROM users";
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
<title>Advanced Reports</title>
<script language="javascript" type="text/javascript" src="js/datetimepicker.js"></script>
<script type="text/javascript" src="js/advancedReport.js"></script>
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
</style>

</head>
<body onload="resetForm()">
<center><h1>Advanced Reports</h1></center>
	<br />
	<div id="container">
	<center>
	<input type="button" value="Go Home" onclick="javascript:window.location='index.htm'">
	&nbsp; 
	<input type="button" value="Run Report" onclick="javascript:window.location='report.php'">
	</center>
	<center>
		<table border="3" bordercolor="#191970">			
			<tr>
				<td> 						
					<form name="myAdvancedReportForm" method="POST" action="advancedReport.php">
						<p>Get total # of emails logged by a particular user</p>
						<fieldset>						
							<legend>Username:</legend>
							<select name="userDropdown" onclick="disableOtherFields('userDropdown')">
								 <option value =""></option>
								 <?=$options?> 
							</select>
							<input type="button" value="Search" onclick="runAdvancedReport('userDropDownFromAdvancedReportFormPage')">
						</fieldset>		
						<br />
						<p>Get total # emails sent out on particular date.</p>
						<fieldset>					
							<legend>Date Search:</legend>					
							<input type="Text" id="calendar" maxlength="25" size="25" name="dateSearch" onclick="disableOtherFields('dateSearch')">
							<a href="javascript:NewCal('calendar','MMDDYYYY',false,24)"><img src="images/cal.gif" width="18" height="18" border="0" alt="Pick a date" onclick="disableOtherFields('dateSearch')"></a>											</a>
							<input type="button" value="Search" onclick="runAdvancedReport('fromCalendarSearch')">
						</fieldset>
						<br />
						<input type="button" onclick="resetForm()" value="Clear Form">
						<br />
					</form>		
					
				</td> 
			</tr>
		</table>
	</center>
	</div>
	
</body>
</html>