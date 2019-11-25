<?
/*
This flie is an include used in advancedReport.php
This file contains functions that are used by advancedReport.php.
Author: Mohamad Khalili 
Date: 3-3-2008
*/

//fuunction runReport in context of advanced report is used to construct reports. Param1 $sql is used to pass sql clause to run report to be 
//executed.  Param2 $type is the type of report to generate, I do this because we need to signal runReport() as to what type of report to generate for us. That is
//the reason why third parameter exists to pass it data for the type of report.  So if I am doing a report on total user searches I pass it the username that was
//selected in advancedReportForm.php so that it can build a report and get total emails logged by that user in the report.
function runAdvancedReport($sql,$type,$input)
{	
	if(!empty($sql) && $type == "totalUserSearches" && !empty($input)) 
    {	
		//put user into variable so its easier to read
		$theUser = $input;
		
		//build up sql string to get the total deletes user had done based on emails sent to dba.
		$sqlTotalUsers = 'SELECT SUM(amountReturned) as totalDeletesByUser FROM emailLogs WHERE userDropdown LIKE "%'.$theUser.'%"';
		
		//call function getTotalUserDeletesForUser(parm) with parm being username in order to 
		//get the total deletes done by that user.  In our case since were in the above if statment we know
		//the call was made to runAdvancedReport() to do a query to get total user searches.		
		$totalDeletesByUser = getTotalUserDeletesForUser($sqlTotalUsers);
		
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
			//echoh $sql;
			//run query and store records returned into $result 
			$result = mysql_query($sql);
		    
			//check to see if result returned empty query 
			if(mysql_num_rows($result) != Null)
			{
				//Get results from query and build up string to display report.									
				include("reportTemplateTop.php");				
					$row = mysql_fetch_array($result);				
					echo "<p>User ".$input." has logged ".$row['totalSearchesDoneByUser']." emails.</p>";		
					echo "<br />";
					echo "<br />";
					echo "<p>User ".$input." has deleted a total of ".number_format($totalDeletesByUser)." profiles based on emails sent out to DBA.</p>";
				include("reportTemplateBottom.php");

				//free recordset to memory
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
	}
	
	if(!empty($sql) && $type == "totalSearchByDay" && !empty($input))
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
				//Get results from query and build up string to display report.									
				include("reportTemplateTop.php");				
					$row = mysql_fetch_array($result);
					echo "<p> Total searches done for date: ".$input." is ".$row['totalSearchByDay'];																
				include("reportTemplateBottom.php");

				//free recordset to memory
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
	}
}//end runReport

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
} // <-- end dateSearch 

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
}

//function to get the total numbe of deletes user has done.  
//Called from runAdvancedReport() first if statement
function getTotalUserDeletesForUser($sql)
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
				$row = mysql_fetch_array($result);				
				
				/*
				//Get results from query and build up string to display report.									
				include("reportTemplateTop.php");				
					$row = mysql_fetch_array($result);					
					//echo "<p> Total searches done for date: ".$input." is ".$row['totalSearchByDay'];																
				include("reportTemplateBottom.php");
				*/

				//free recordset to memory
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
				return $row['totalDeletesByUser'];
				//exit;
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
}
?>
