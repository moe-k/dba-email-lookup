<?
/* 
Author: Mohamad Khalili
Date: 2-5-2008
Purpose: This script is used pull reports from the databse 
*/

//Get all the differet form variables and then decide what report to run
$var_dateSearch = trim($_POST['dateSearch']);

$var_dateRangeLeft = $_POST['dateRangeLeft'];

$var_dateRangeRight = $_POST['dateRangeRight'];

$var_keywordSearch  = trim($_POST['keywordSearch']);

$var_userDropdown  = trim($_POST['reportinguserDropdown']);

if(!empty($var_userDropdown))
{
	//store the username the user selected in report.php into $theUser so we can build query.
	$theUser = $var_userDropdown;
	//build up query and concatenate the user into it
	$sql = 'SELECT * FROM emailLogs WHERE userDropdown LIKE "%'.$var_userDropdown.'%"ORDER BY date DESC';
	
	//echo $sql;
	runReport($sql);
}

//check if it is a datesearch this would be single datesearch the first option from report.html
if(!empty($var_dateSearch))
{
	//call dateSearch(param) where param is the date that was selected on the form and checked by report.js
	//dateSearch(param) takes the date and adds zeros to month and day if they are single digit.  This is done because 
	//MySql only likes dates in that type of format.  I had to do this so that a query would be possible on the field 
	$dateWithZeros = dateSearch($var_dateSearch);
	
	//since dateSearch(param) returns date in format MM-DD-YYYY with appended zeros we have to do another reordering 
	//of the date in order to compare it to date field in db because inputSearchForm.php writes the date of the email logging in 
	//the form YYYY-MM-DD
	$reorderedDate = modifyDate($dateWithZeros);
	
	//mysql_query("INSERT INTO foo SET name = '".$_POST['name']."'");
	//$sql = "select * from emaillogs where date = '".$reorderedDate."'";  // <--home
	//$sql = "select * from emailLogs where date = '".$reorderedDate."'";  // <--work
	//generate sql statement by appending users date that was picked in report.htm
	$sql = "SELECT * FROM emailLogs WHERE date = '".$reorderedDate."'ORDER BY date DESC";  // <--work query
	
	//call runReport(param) where param is the query to db
	runReport($sql);	
}

//check to see if left range and right range have been filled out in the form, if not we dont process 
if(!empty($var_dateRangeLeft) && !empty($var_dateRangeRight))
{
	//make call to dateSearch(param) to get the dates padded with zeros if they 
	//have a 1 digit month or day value
	$leftRangeWithZeros = dateSearch($var_dateRangeLeft);
	$rightRangeWithZeros = dateSearch($var_dateRangeRight);
	
	//make call to modifyDate(param) to get the dates reorganized now in a format MySQL likes 
	//MySQL likes date format of YYYY-MM-DD
	$reorderedLeftRange = modifyDate($leftRangeWithZeros);
	$reorderedRightRange = modifyDate($rightRangeWithZeros);
	
	//$sql = "SELECT * from emaillogs WHERE date BETWEEN "." '".$reorderedLeftRange."' "." AND "." '".$reorderedRightRange. "' ";  //<--home
	$sql = "SELECT * FROM emailLogs WHERE date BETWEEN "." '".$reorderedLeftRange."' "." AND "." '".$reorderedRightRange. "'ORDER BY date DESC";
	//run the report.
	runReport($sql);
}

//check to see if its a keyword search which is the third option in report.htm
if(!empty($var_keywordSearch))
{
	//now check to see what type of keyword search and execute according to which coloumn the user wants to search.  According to the search build sql string and execute report.
	//The reason why i use htmlentities() is so that if the user wants to search for thml in that coloumn the string they put in would match whats in the db.  This is because in inputSearch.php 
	//the columns that were filled out in the form were escaped and put into the db with the htmlenteties function.  If we didnt build our searchstring without using htmlentities()  user will not get
	//valid results when looking for html in the columns.
	$selected_radio = $_POST['keywordsearch'];
	if(!empty($selected_radio))
	{
		switch($selected_radio)
		{
			case 'Email': 
				$sql = 'SELECT * FROM emailLogs WHERE email LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
			
			case 'Headline':
				$sql = 'SELECT * FROM emailLogs WHERE headline LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
				
			case 'About Me':
				$sql = 'SELECT * FROM emailLogs WHERE aboutMe LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
			
			case 'Like To Meet':
				$sql = 'SELECT * FROM emailLogs WHERE likeToMeet LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
			
			case 'Music':
				$sql = 'SELECT * FROM emailLogs WHERE music LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
			
			case 'Books':
				$sql = 'SELECT * FROM emailLogs WHERE books LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
			
			case 'Movies':
				$sql = 'SELECT * FROM emailLogs WHERE movies LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
			
			case 'General':
				$sql = 'SELECT * FROM emailLogs WHERE general LIKE "%'.htmlentities($var_keywordSearch).'%"ORDER BY date DESC';
				runReport($sql);
				break;
		}	
	}
}



//function to put zeros on day and month part of date if the user picked a date which is single
//digit in month or day field.
function dateSearch($date)
{
	//get the position of the slahses in the date that was submitted
	$firstslash=strpos($date,"-");
	$lastslash=strripos($date,"-");
	
	//get the length of the month part of the date that user selected 
	$monthLength = strlen(substr($date,0,$firstslash));
	
	//if the month part of the date is of length 1 then we need to add a 0 on the front
	if($monthLength == 1)
	{
		//echo $date;
		//echo "<br />";
		//get the month part of the date and store it 
		$oldMonthPart = substr($date,0,$firstslash);
		
		//make new month part by appending a 0 on the front of the old one
		$newMonthPart = "0".$oldMonthPart;
		
		//echo "The old month part was ".$oldMonthPart;
		//echo "<br />";
		//echo "The new month part is now ".$newMonthPart;	
		//echo "<br/>";
		
		//get the daypart of the date
		$oldDayPart = substr($date,$firstslash+1,$lastslash-2); 
		
		//check to see if the day part is 1 digit or 2 digit, if 1 then we need to add a zero on the front of it
		//and then make a whole new date string 
		if(strlen($oldDayPart) == 1)
		{
			$newDayPart = "0".$oldDayPart;
			//echo "The old day part was ".$oldDayPart;
			//echo "<br />";
			//echo "The new month part is now ".$newDayPart;
			//echo "<br />";
			
			//get the year part of the date 			
			$yearPart = substr($date,$lastslash + 1,strlen($date));
			
			//now build new date string with appended 0 on the day and month part of the date
			$newDateString = $newMonthPart."-".$newDayPart."-".$yearPart;
			//echo "The new date string is now ".$newDateString;
			//echo "<br />";
			return $newDateString;
		}
		else 
		{
			//if we arrive here we know that the month part of the date  was already taken care of.
			//we aslo know that the daypart of the date was alright since it passe the above check, so we can use new month part and old
			//day part of the date to generate new date string 
			
			//chop off the year part of the date to append onto new date string we make
			$yearPart = substr($date,$lastslash + 1,strlen($date));
			$newDateString = $newMonthPart."-".$oldDayPart."-".$yearPart;
			//echo "The new date string is now ".$newDateString;
			return $newDateString;
		}			
	}  
	//if we get here we know that month length is > 1 so its a 2 digit month. if its a 2 digit month then we dont have to make
	//a new month part instead we just have to check the day part and make new day part if  its a 1 digit day part.
	else
	{

		//chop off the daypart and monthpart of the date
		$oldDayPart = substr($date,$firstslash+1,$lastslash-3);
		$oldMonthPart = substr($date,0,firstslash-1);
		
		//check to see day part of the date is 1 digit or 2 digit
		if(strlen($oldDayPart) == 1)
		{			
			//make new day part by appending 0 on the front of the old one
			$newDayPart = "0".$oldDayPart;	
			//get the year part 
			$yearPart = substr($date,$lastslash + 1,strlen($date));
			//get 
			$monthPart = substr($date,0,$firstslash);
			
			//echo "$date";
			//echo "<br />";
			//echo "new day part ".$newDayPart;
			//echo "<br />";
			//echo "new year part ".$yearPart;
			//echo "<br />";
			//echo "new month part ".$monthpart;
			$newDateString = $monthPart."-".$newDayPart."-".$yearPart;
			//echo $newDateString;
			return $newDateString;
		}
		//if we get to this condition we know that it has a 2 digt daypart and 2 digit month part so the date is ok so pass it back
		else
		{
				//echo "The date is ok ".$date;
				return $date;
		}		
	}
}

//function to modify date the way mysql likes it, I made a mistake picking howto store dates
//the form that inserts the email log to the db stores dates in this format MM-DD-YYYY where 
//MySQL likes it in the format YYYY-MM-DD. So this function reorders the date around 
function modifyDate($date)
{
	//get first slahs in the date and last slash
	$firstslash=strpos($date,"-");
	$lastslash=strripos($date,"-");
	
	//get year part
	$year = substr($date,$lastslash +1,strlen($date));
	//get month part
	$month = substr($date,0,$firstslash);
	//get day part
	$day = substr($date,$firstslash+1,$lastslash-3);
	
	//return new string with date in format YYYY-MM-DD
	return $year."-".$month."-".$day;
	/* Debug:
	echo "original date: ".$date;
	echo "<br />";
	echo "year: ".$year;
	echo "<br />";
	echo "month: ".$month;
	echo "<br />";
	echo "day: ".$day;
	*/
}


//fucntion that runs report.  $sql parameter is the sql query to use on the DB.
function runReport($sql)
{	
		//make connection to db
		//$myCon = mysql_connect("localhost","root",""); //<--home
		$myCon = mysql_connect("abuseteam.mscorp.com","moey0601","moho0601");
		
		//check if connection is successful
		if (!$myCon)
		{
			include("reportTemplateErrorTop.php");
			die('Could not connect to MySQL DB ' . mysql_error());
			include("reportTemplateErrorBottom.php");
		}
				
		//check if selecting database is successful 
		if(mysql_select_db("email_logger", $myCon)) 
		{
			//run query and store records returned into $result 
			$result = mysql_query($sql);
		    
			//check to see if result returned empty query 
			if(mysql_num_rows($result) != Null)
			{
				/*
				include("reportTemplateTop.php");
				echo"<tr><th><p>Username</p></th><th><p>Email to DBA</p></th><th><p>ID Range</p></th><th><p>ID's Returned</p></th><th><p>Headline</p></th><th><p>AboutMe</p></th><th><p>LikeToMeet</p></th><th><p>Music</p></th><th><p>books</p></th><th><p>Movies</p></th><th><p>General</p></th><th><p>Date Inputed</p></th></tr>";
				while($row = mysql_fetch_array($result))
				{						
					echo "<tr>"; 
						echo "<td>".$row['userDropdown']."</td>";
						echo "<td WIDTH='20%'>".nl2br($row['email'])."</td>";
						echo "<td>".$row['leftRange']."-".$row['rightRange']."</td>";					
						echo "<td>".$row['amountReturned']."</td>";
						echo "<td>".$row['headline']."</td>";
						echo "<td>".$row['aboutMe']."</td>";
						echo "<td>".$row['likeToMeet']."</td>";
						echo "<td>".$row['music']."</td>";
						echo "<td>".$row['books']."</td>";
						echo "<td>".$row['movies']."</td>";
						echo "<td>".$row['general']."</td>";
						echo "<td>".$row['date']."</td>";					
					echo "</tr>";						
				}			
				include("reportTemplateBottom.php");											
				//free returned handle from query
				*/
				
				
				
				include("reportTemplateTop.php");				
				while($row = mysql_fetch_array($result))
				{						
						echo "<table>";
						echo "<tr>";
						echo "<td>";
						echo "<b>Username:</b> ".$row['userDropdown'];
						echo "<br />";
						echo "<br />";
						echo "<b>Date Logged:</b> ".$row['date'];
						echo "<br />";
						echo "<br />";
						echo "<b>Email:</b> ".nl2br($row['email']);
						echo "<br />";
						echo "<br />";
						echo "<b>Notes:</b> ".nl2br($row['notes']);
						echo "<br />";
						echo "<br />";
						echo "<b>ID Range:</b> ".$row['leftRange']."-".$row['rightRange'];		
						echo "<br />";	
						echo "<br />";						
						echo "<b>Amount ID's Returned:</b> ".$row['amountReturned'];
						echo "<br />";
						echo "<br />";
						echo "<b>Headline:</b> ".$row['headline'];
						echo "<br />";
						echo "<br />";
						echo "<b>About Me:</b> ".$row['aboutMe'];
						echo "<br />";
						echo "<br />";
						echo "<b>Like To Meet:</b> ".$row['likeToMeet'];
						echo "<br />";
						echo "<br />";
						echo "<b>Muscic:</b> ".$row['music'];
						echo "<br />";
						echo "<br />";
						echo "<b>Books:</b> ".$row['books'];
						echo "<br />";
						echo "<br />";
						echo "<b>Movies:</b> ".$row['movies'];
						echo "<br />";
						echo "<br />";
						echo "<b>General:</b> ".$row['general'];				
						echo "</td>";
						echo "</tr>";						
						echo "<hr>";						
				}			
				include("reportTemplateBottom.php");	
				
				
				if(!mysql_free_result($result))
				{
					include("reportTemplateErrorTop.php");
					die("couldn't free recordset to memory".mysql_error());
					include("reportTemplateErrorBottom.php");
				}
				//close connection to db
				if(!mysql_close($myCon))
				{
					include("reportTemplateErrorTop.php");
					die("couldnt close connection to DB <br />".mysql_error());
					include("reportTemplateErrorBottom.php");
				}
			}
			//if an empty query was returned then build up page that will tell the user no results were returned.
			else
			{				
				    //the top part of the page is stored in reportTemplateTop.php since we want to build up the page we have to 
					//do it this way.  The respective bottom part of the page is stored in reportTemplateBottom.php
					include("reportTemplateErrorTop.php");
					echo "<p>Query returned no results </p><br />".mysql_error();
					include("reportTemplateErrorBottom.php");
					
			}
		}
		//if above does not pass then we know that there was a prob in selecting the db. so we tell the user and show MySql generated error message
		else
		{
			include("reportTemplateErrorTop.php");
			die("Couldn't select database <br />".mysql_error());
			include("reportTemplateErrorBottom.php");
		}
	
}//end runReport
?>