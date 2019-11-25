<?
/*
Filename: inputSearchForm.php
Purpose: To populate dropdown in this form 
Author: Mohamad Khalili
Date: 2-5-2008 
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
<title>Input Search</title>
<script type="text/javascript" src="js/inputSearchForm.js"></script>
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
<body>
<center><h1>Input Search To DBA</h1></center>
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
					<form name="myInputForm" method="POST" action="inputSearch.php">
					    <p>Input username before logging</p>
						<fieldset>
							<legend>Name (Required):</legend>
							<select name="userDropdown" onclick="clearTextArea('userDropdown')">
								 <option value =""></option>
								 <?=$options?> 
							</select>
							<a href="" onclick="javascript:window.open('help/usernameHelp.htm','_blank','width=300,height=300');return false;">?</a>
						</fieldset>
						<br /> 
						<p>Please log the e-mail you are sending to DBA for lookup.</p>
						<fieldset>
							<legend>E-mail to DBA (Required):</legend>
							<textarea rows="20" cols="55" name="email" onclick="clearTextArea('email')"></textarea>
							<a href="" onclick="javascript:window.open('help/emailHelp.htm','_blank','width=300,height=300');return false;">?</a>
						</fieldset>
						<br />
						<p>Please log the any notes here.</p>
						<fieldset>
							<legend>Notes:</legend>
							<textarea rows="12" cols="55" name="notes" onclick="clearTextArea('notes')"></textarea>							
						</fieldset>
						<br />
						<p>Please input the lowest id returned from your search in the left hand box and highest id returned in the right hand box .  
						</p>						
						<fieldset>								
							<legend>Range (Required):</legend>
							<input type="text" size="20" name="leftRange" onclick="clearTextArea('leftRange')"> -
							<input type="text" size="20" name="rightRange" onclick="clearTextArea('rightRange')">
							<a href="" onclick="javascript:window.open('help/rangeHelp.htm','_blank','width=300,height=300');return false;">?</a>
						</fieldset>
						
						<br />
						<p>Please input the amount of ID's that were returned 
						   from your search. If none returned or not applicable just leave it blank.
						</p>
						<fieldset>								
							<legend>Amount of ID's Returned (Required):</legend>
							<input type="text" size="20" name="amountReturned" onclick="clearTextArea('amount')">
							<a href="" onclick="javascript:window.open('help/amountReturnedHelp.htm','_blank','width=300,height=300');return false;">?</a>
						</fieldset>
						<br />						
						<p>If your are submitting a request to the DBA which requires a search that contains
						text or code from the headline section of profiles please log it here. If not applicable 
						just leave it blank.
						</p>
						<fieldset>								
							<legend>Headline:</legend>
							<input type="text" size="40" name="headline">
						</fieldset>
						<br />									
						<p>If you are submitting a request to the DBA which requires a search that contains text or
						code that was in "AboutMe" section of profiles please log it here. If not applicable 
						just leave it blank.						   
						</p>
						<fieldset>								
							<legend>About Me:</legend>
							<textarea rows="10" cols="55" name="aboutMe"></textarea>
						</fieldset>
						<br/>
						
						<p>If you are submitting a request to the DBA which requires a search that contains
						text or code that was in "LikeToMeet" section of profiles please log it here. If not applicable 
						just leave it blank.						   
						</p>
						<fieldset>								
							<legend>Like To Meet:</legend>
							<textarea rows="10" cols="55" name="likeToMeet"></textarea>
						</fieldset>
						<br />
						
						<p>If you are submitting a request to the DBA which requires a search that contains
						text or code that was in "Music" section of profiles please log it here. If not applicable 
						just leave it blank.						   
						</p>
						<fieldset>								
							<legend>Music:</legend>
							<textarea rows="10" cols="55" name="music"></textarea>
						</fieldset>
						<br />
						
						<p>If you are submitting a request to the DBA which requires a search that contains
						text or code that was in "Books" section of profiles please log it here. If not applicable 
						just leave it blank.						   
						</p>
						<fieldset>								
							<legend>Books:</legend>
							<textarea rows="10" cols="55" name="books"></textarea>
						</fieldset>
						<br/>
						
						<p>If you are submitting a request to the DBA which requires a search that contains
						text or code that was in "Movies" section of profiles please log it here. If not applicable 
						just leave it blank.						   
						</p>
						<fieldset>								
							<legend>Movies:</legend>
							<textarea rows="10" cols="55" name="movies"></textarea>
						</fieldset>
						<br/>
						
						<p>If you are submitting a request to the DBA which requires a search that contains
						text or code that was in "General" section of profiles please log it here. If not applicable 
						just leave it blank.						   
						</p>
						<fieldset>								
							<legend>General:</legend>
							<textarea rows="10" cols="55" name="general"></textarea>
						</fieldset>
						<br/>						
						<input type="button" value="Logit" onclick="checkform(document.myInputForm)" >
						<input type="reset" value="Clear Form">
					</form>									
				</td> 
			</tr>
		</table>
	</center>
	</div>
	
</body>
</html>