<?
//include functions used to be used in making advanced reports.
include_once('advancedReportFunctions.php');

$var_userDropdown = trim($_POST['userDropdown']);
$var_dateSearch = trim($_POST['dateSearch']);

//if user picked to do a username search in advancedReportForm.php then we execute below.
if(!empty($var_userDropdown))
{
	//Get the user from the dropdown in advancedReportForm.php
	$theUser = $var_userDropdown;
	
	//Build sql string to get the count of emails and alias it into totalSearchDoneByUser so that we can display it later in the report.
	$sql = 'SELECT COUNT(*) as totalSearchesDoneByUser FROM emailLogs WHERE userDropdown LIKE "%'.$theUser.'%"';
	
	//Call run report with the type of query we want to do and the username that was selected in advancedReportForm.php
	runAdvancedReport($sql,"totalUserSearches",$theUser);
	
}

if(!empty($var_dateSearch))
{
	//call dateSearch(param) where param is the date that was selected in advancedReportForm.php and checked by advancedReport.js
	//dateSearch(param) takes the date and adds zeros to month and day if they are single digit.  This is done because 
	//MySql only likes dates in that type of format.  I had to do this so that a query would be possible on the field 
	$dateWithZeros = dateSearch($var_dateSearch);
	
	
	//since dateSearch(param) returns date in format MM-DD-YYYY with appended zeros we have to do another reordering 
	//of the date in order to compare it to date field in db because inputSearchForm.php writes the date of the email logging in 
	//the form YYYY-MM-DD
	$reorderedDate = modifyDate($dateWithZeros);
	
	//generate sql statement to get the total number of emails logged on particular day that the user selects from advancedReportForm.php
	$sqlTotalSearchByDay = "SELECT COUNT(*) as totalSearchByDay FROM emailLogs WHERE date = '".$reorderedDate."'ORDER BY date DESC"; 
	
	//call run report with our sql stmnt and type of search to do and the date for that search so that runReport can generate write type of report.
	runAdvancedReport($sqlTotalSearchByDay,"totalSearchByDay",$reorderedDate);
}

?>