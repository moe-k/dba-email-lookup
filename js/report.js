/*
Filename: report.js
Purpose: used to handle reporting from report.htm. Primarily used to check if report types are valid before passing over to report.php
Author: Mohamad Khalili
Date: 1-31-2008 
*/
//function to handle reporting from report.htm form.  Param whatType is the type of the report that the user wants to pull
function runReport(whatType)
{
	//bools needed for checks
	var myBool1 = new Boolean(false);
	var myBool2 = new Boolean(false);
	
	if(whatType == "userDropFromReportphppage")
	{
		//store username that user picked in report.php in myDropdown and run a check to see if 
		//it contains a username. 
		var userDropDown = document.reportForm.reportinguserDropdown.value;
		if(userDropDown != "")
		{
			document.reportForm.submit();
		}
		else
		{
			alert("Need to pick a username before running a report");
		}
	}
	
	//execute if user picks the first report type in report.htm which is just plain date search
	if(whatType == "fromCalendarSearch")
	{
			//calll isDate(value) to check if its valid date
			myBool1 = isDate(document.reportForm.calendar.value);
			//it date is filled out and it is a valid date sthen submit page to serverside script makereport.php to process report
			if(myBool1)
			{ 
				//alert("Valid date");
				document.reportForm.submit();				
				//set to false for next check to use
				myBool1 = false;
			}
			//if not then put focus on that field and alert the user
			else
			{
				document.reportForm.calendar.focus();
				alert("Need to pick a valid date");
			}
	}
	//execute if user picks the date range search which is the second option in report.htm.
	if(whatType == "dateRangeSearch")
	{
			//check to see if both boxes for date range are filled out correctly
			myBool1 = isDate(document.reportForm.drangecalendar1.value);
			myBool2 = isDate(document.reportForm.drangecalendar2.value); 
			//if both pass then we can execute search if not then branch and alert user.  In both cases i set mybool vars back to false
			//so that if used later they dont contain garbage. 
			if(myBool1 == true && myBool2 == true)
			{
				//alert("valid date range");
				document.reportForm.submit();
				myBool1 = false;
				myBool2 = false;
			}
			else
			{
				alert("Need to pick a valid date range");
				myBool1 = false;
				myBool2 = false;
			}
	}
	/*
	//disabled for now later will implement later on
	if(whatType == "-7")
	{
		alert("In Production still check back later");
	}
	*/
	//disabled for now will implement later on
	//execute search based on range which is the third option in report.htm
	if(whatType == "rangeSearch")
	{		
	    //store the left and right range for checking.
		var left = document.reportForm.leftRange.value;
		var right = document.reportForm.rightRange.value;
		
		//check to see if both are filled out.
		if(left !="" && right !="")
		{
			//if they are then add commas to both of them.
			var commadLeft = addCommas(left);
			var commadRight = addCommas(right);	
			//Debig: alert("Leftbox = "+commadLeft+" Rightbox = "+commadRight); 	
			//write them back to the form with commas so that upon submit they are submitted to report.php
			document.reportForm.leftRange.value = commadLeft;
			document.reportForm.rightRange.value = commadRight;
		}
		//if above not passes then we alert the user to fill out range correctly
		else
		{
			alert("Need to fill out a valid id range to do a search");
		}
	}	
	//execute keyword search. which is the fourth search in report.php.
	if(whatType == "keywordSearch")
	{
		//store textarea value for check
		var myTextArea = document.reportForm.keywordSearch.value;
		//if not left blank then execute
		if(myTextArea != "" && checkRadios())
		{
			document.reportForm.submit();
			//alert("run search based on keyword");
		}
		//alert user they need to fillout keyword
		else
		{
			alert("Need to fill out keyword and pick a column to search on");
		}
		
	}
}
//function to check if date is valid. I know this is lazy way of check but it works.  I will modify this later.
//isDate(param) frmVal is the value of the field to check in report.htm
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

//function courtesy of mredkj.com 
//this function will add commas to nStr. I use it to add commas to range fields
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
//function frmDisableOtherFields(param) where param is the field that the user has put his mouse in in order to fillout
//and run a report.  That field we keep enabled and all other fields we disable so that the user is not able to select and run
//multiple queries to the db.  If multiple queries are sent to makereport.php then you will see overlapped report thats why i disabled this 
//from happening.
function frmDisableOtherFields(whatField)
{
	if(whatField == 'reportinguserDropdown')
	{
		document.reportForm.dateSearch.disabled = true;
		document.reportForm.drangecalendar1.disabled = true;
		document.reportForm.drangecalendar2.disabled = true;
		document.reportForm.keywordSearch.disabled = true;	
		document.reportForm.keywordSearch.value= "";	
		document.reportForm.drangecalendar1.value="";
		document.reportForm.drangecalendar2.value="";
		document.reportForm.dateSearch.value="";
	}
	if(whatField == 'dateSearch')
	{		
		document.reportForm.reportinguserDropdown.disabled = true;
		document.reportForm.reportinguserDropdown.selectedIndex = 0;		
		document.reportForm.drangecalendar1.disabled = true;
		document.reportForm.drangecalendar2.disabled = true;
		document.reportForm.keywordSearch.disabled = true;
		document.reportForm.keywordSearch.value= "";	
		document.reportForm.drangecalendar1.value="";
		document.reportForm.drangecalendar2.value="";
	}
	if(whatField == 'dateRangeLeft' || whatField == 'dateRangeRight')
	{
		document.reportForm.reportinguserDropdown.selectedIndex = 0;
		document.reportForm.reportinguserDropdown.disabled = true;
		document.reportForm.dateSearch.disabled = true;
		document.reportForm.dateSearch.disabled = true;
		document.reportForm.keywordSearch.disabled = true;
		document.reportForm.keywordSearch.value= "";
		document.reportForm.dateSearch.value="";
		
	}
	if(whatField == 'keywordSearch')
	{	
		document.reportForm.reportinguserDropdown.selectedIndex = 0;
		document.reportForm.reportinguserDropdown.disabled = true;
		document.reportForm.drangecalendar1.disabled = true;
		document.reportForm.drangecalendar2.disabled = true;
		document.reportForm.dateSearch.disabled = true;	
		document.reportForm.drangecalendar1.value="";
		document.reportForm.drangecalendar2.value="";
		document.reportForm.dateSearch.value="";
	}
}

//this function resets the form so that any disabled fields are enabled.
function resetForm()
{
		document.reportForm.reportinguserDropdown.disabled = false;
		document.reportForm.drangecalendar1.disabled = false;
		document.reportForm.drangecalendar2.disabled = false;
		document.reportForm.dateSearch.disabled = false;
		document.reportForm.keywordSearch.disabled = false;
}
//function to check radio buttons so that keywordsearch isnt prematurely pressed.
function checkRadios()
{
	if(document.reportForm.keywordsearch[0].checked == true ||
	   document.reportForm.keywordsearch[1].checked == true ||
	   document.reportForm.keywordsearch[2].checked == true ||
	   document.reportForm.keywordsearch[3].checked == true ||
	   document.reportForm.keywordsearch[4].checked == true ||
	   document.reportForm.keywordsearch[5].checked == true ||
	   document.reportForm.keywordsearch[6].checked == true ||
	   document.reportForm.keywordsearch[7].checked == true)
	   return true;
	else
		return false;
}