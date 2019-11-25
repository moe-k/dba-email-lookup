<?
/*
Author: Mohamad Khalili
Date: 2-4-2008
Purpose: This script logs agents emails sent to DBA's to database.
*/


//array that will hold any filled out fields that come from inputsearch.htm 
$inputFormArr = array();

//cycle through $_POST array and check to see what form fields are filled out.  
foreach($_POST as $key => $value)
{
	//check to see if the form field values are not left blank, if not then store them 
	if($value !="")
	{
		//htmlentities(param) is used to escape out any html tags.  Done for security reasons.
		$inputFormArr["$key"] = trim(htmlentities($value));
	}	 
	else
	{
		//if the form field has not been filled out store a N/A, so that later when running reports on those fields they are not left blank.		
		$inputFormArr["$key"] = "N/A";
	}
} 


	//make conneciton to db
	$myCon = mysql_connect("abuseteam.mscorp.com","moey0601","moho0601");
	//$myCon = mysql_connect("localhost","root","");
	//check if connection is successful
	if (!$myCon)
	{
	    include("reportTemplateErrorTop.php");
	    die('Could not connect: ' . mysql_error());
	    include("reportTemplateErrorBottom.php");
	}
	
	//select database
	if(!mysql_select_db("email_logger", $myCon))
	{
		include("reportTemplateErrorTop.php");
		die ("couldnt select DB".mysql_error());
		 include("reportTemplateErrorBottom.php");
	}
	else
	{
		//get the date so that we can log the date the user logged the email
		$date = date("Y-m-d");
		
		//build sql string to insert data from inputsearch.htm form to the database
		/*$sql = "INSERT INTO EmailLogs
		(userDropdown,email,leftRange,rightRange,amountReturned,headline,aboutMe,likeToMeet,music,books,movies,general,date) 
		VALUES(' ".$inputFormArr['userDropdown'].
		" ',' ".$inputFormArr['email'].
		" ',' ".$inputFormArr['leftRange'].
		" ',' ".$inputFormArr['rightRange'].
		" ',' ".$inputFormArr['amountReturned'].
		" ',' ".$inputFormArr['headline'].
		" ',' ".$inputFormArr['aboutMe'].
		" ',' ".$inputFormArr['likeToMeet'].
		" ',' ".$inputFormArr['music'].
		" ',' ".$inputFormArr['books'].
		" ',' ".$inputFormArr['movies'].
		" ',' ".$inputFormArr['general'].
		" ',' ".$date." ') ";*/
		
		//build up sql string for insert email into database
		$sql = "INSERT INTO emailLogs
		(userDropdown,email,notes,leftRange,rightRange,amountReturned,headline,aboutMe,likeToMeet,music,books,movies,general,date) 
		VALUES(' ".$inputFormArr['userDropdown'].
		" ',' ".$inputFormArr['email'].
		" ',' ".$inputFormArr['notes'].
		" ',' ".$inputFormArr['leftRange'].
		" ',' ".$inputFormArr['rightRange'].
		" ',' ".$inputFormArr['amountReturned'].
		" ',' ".$inputFormArr['headline'].
		" ',' ".$inputFormArr['aboutMe'].
		" ',' ".$inputFormArr['likeToMeet'].
		" ',' ".$inputFormArr['music'].
		" ',' ".$inputFormArr['books'].
		" ',' ".$inputFormArr['movies'].
		" ',' ".$inputFormArr['general'].
		" ',' ".$date." ') ";
		//redirect user to confirmation page
		header('Location: confirmation.php');
		//execute query 
		if(!mysql_query($sql))
		{
			include("reportTemplateErrorTop.php");
			die ("couldn't execute query to DB.".mysql_error());
			include("reportTemplateErrorBottom.php");
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