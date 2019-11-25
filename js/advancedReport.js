/*
Filename: advancedReport.js
Purpose: used to handle checks in advancedReportForm.php.  
Author: Mohamad Khalili
Date: 3-3-2008
*/
//function to handle the form located in advancedReportForm.php.  Param whatType is the type of the report that the user wants to pull 
function runAdvancedReport(whatType)
{
	//bools needed for checks
	var myBool1 = new Boolean(false);
	var myBool2 = new Boolean(false);
	
	if(whatType == "userDropDownFromAdvancedReportFormPage")
	{
		//store username that user picked in advancedReportForm.php in userDropDown and run a check to see if 
		//it contains a username. 
		var userDropDown = document.myAdvancedReportForm.userDropdown.value;
		if(userDropDown != "")
		{
			document.myAdvancedReportForm.submit();
		}
		else
		{
			alert("Need to pick a username before running a report");
		}
	}
		
	//execute if user picks to get total searches for a particulare date which is the second option in advancedReportForm.php
	if(whatType == "fromCalendarSearch")
	{
			//calll isDate(value) to check if its valid date
			myBool1 = isDate(document.myAdvancedReportForm.calendar.value);
			//it date is filled out and it is a valid date then submit page to serverside script advancedReport.php to process report
			if(myBool1)
			{ 
				//alert("Valid date");
				document.myAdvancedReportForm.submit();				
				//set to false for next check to use
				myBool1 = false;
			}
			//if not then put focus on that field and alert the user
			else
			{
				document.myAdvancedReportForm.calendar.focus();
				alert("Need to pick a valid date");
			}
	}	
}
//function to check if date is valid.  
//isDate(param) frmVal is the value of the field to check in advancedReportForm.php
function isDate(frmVal)
{
	//if it contains a - then return true else false
	if(frmVal.indexOf("-") > -1)
	{
		return true;
	}
	else
	{		
		return false;
	}
}

//function that will disable other fields in the form so that the user doesnt
//submit queries on top of each other and gets back jumbled results.  Param whatFields is the field that 
//will not be disabled it is the field that the user has clicked on, so we want to disable all other fields other than 
//whatField.
function disableOtherFields(whatField)
{
	if(whatField == "userDropdown")
	{				
		document.myAdvancedReportForm.dateSearch.disabled = true;
		document.myAdvancedReportForm.dateSearch.value = "";
	}
	if(whatField == "dateSearch")
	{
		document.myAdvancedReportForm.userDropdown.disabled = true;
		document.myAdvancedReportForm.userDropdown.selectedIndex = 0;
	}
}

function resetForm()
{
		document.myAdvancedReportForm.userDropdown.disabled = false;
		document.myAdvancedReportForm.userDropdown.selectedIndex = 0;
		document.myAdvancedReportForm.dateSearch.disabled = false;
		document.myAdvancedReportForm.dateSearch.value = "";

}



