/*
Filename: inputSearchForm.js
Purpose: Used to handle error checking and form submission in inputsearch.htm.
Author: Mohamad Khalili
Date: 1-30-2008 
*/

//variables to hold errors that occur on form
var emailError = new Boolean(false);
var leftRangeError = new Boolean(false);
var rightRangeError = new Boolean(false);
var amountReturnedError = new Boolean(false);
var userDropdownError = new Boolean(false);

//function to handle error checking on the form before submitting.
//parameter theForm recieves the form so it could be used to check fields.
function checkform(theForm)
{
	//varible to hold errors before the form is submited to serverside script for logging.  
	//this will be used later to build up error string and disply to the user any errors that occured.
	var errorBuilder = "";
		
	with(theForm)
	{
		//check to see if any users are selected, if not then build error string and set background color of dropdown
		if(userDropdown[userDropdown.selectedIndex].value =="")
		{
				errorBuilder+="Please select a user. ";
				userDropdown.style.backgroundColor="#8B0000";
				theForm.userDropdown.focus();
				userDropdownError = true;
		}
		
		
		//check to see if email textarea is filled out, if not the we build an error string
		//which will be displayed later on and put focus on the textarea for the user. also set error if occured
		if(email.value == "")
		{
				//build error string and marke the background of email textarea to red indicating error and set focus to it.			
				errorBuilder+="Please fill out email being sent to the dba before logging.  ";
				email.style.backgroundColor="#8B0000";
				theForm.email.focus();
				emailError = true;
		}	
		
		
		//check to see if range fields in inputsearch.htm have anything in them. if not then build error string and check what errors occured
		if(rightRange.value.length > 0 && leftRange.value.length > 0)
		{							
				//check if both fields are numeric if not then build error string and highlight fields
				if(isDigit(rightRange.value) && isDigit(leftRange.value))
				{
					//passed we can submit
				}
				else
				{
					errorBuilder+="Range field needs to contain numerical data. ";
					rightRange.style.backgroundColor="#8B0000";
					leftRange.style.backgroundColor="#8B0000";
					theForm.rightRange.focus();
					leftRangeError = true;
					rightRangeError = true;
				}
				
		}
		//since range is required if the above doesnt pass then build error string and alert the user
		else
		{
				errorBuilder+="Please fill out range value IE 20000-30000. ";
				rightRange.style.backgroundColor="#8B0000";
				leftRange.style.backgroundColor="#8B0000";
				theForm.rightRange.focus();
				leftRangeError = true;
				rightRangeError = true;
		}
		//check to see if the left range is greater then right if so then build error string
		if(leftRange.value > rightRange.value)
		{
				errorBuilder+="Check your ranges leftside needs to be less than right side. ";
				rightRange.style.backgroundColor="#8B0000";
				leftRange.style.backgroundColor="#8B0000";
				theForm.leftRange.focus();
				leftRangeError = true;
				rightRangeError = true;
		}
        /*  		
		//check to see if range field is been left blank if so then build error string and make the background red to notify the user
		if(range.value.length == 0)
		{				
				errorBuilder+="Please fill out range correctly before submitting IE 200000-400000.  ";
				range.style.backgroundColor="#8B0000";
				theForm.range.focus();			
		}
		//if range field has not been left blank then we arrive here and do some checks 
		else if(range.value.length > 0)
		{
				//if search returns a -1 we know that the user did not put a - in the range field so we build error string and we turn background red to notify user
				if(range.value.search("-") == -1)
				{
					//alert("range doesnt have a - in it doesnt pass");
					errorBuilder+="Please fill out range correctly before submitting IE 200000-400000.  ";
					range.style.backgroundColor="#8B0000";
					theForm.range.focus();					
				}
				//if search returns anything else we know that a - exists and search returned the index of it
				else
				{
					//get left and righside of range field inputed by user and build new range and add commas to it. 
					//stringObject.slice(start,end) return = to portion of string between start and end
					var leftside  =  range.value.slice(0,range.value.search("-"));
					var rightside =  range.value.slice(range.value.search("-") + 1,range.value.length);
					var myCommaRange = addCommas(leftside) + "-" + addCommas(rightside);
					range.value=myCommaRange;
					//alert("leftside = "+ leftside + "rightside = " + rightside);
					//alert(addCommas(leftside));					
					//alert(myCommaRange);
				}					
		}
		*/
		
		//check to see if amount returned field is filled out in inputsearchform.php.  Since it is required field
		//we want to make sure that its filled out thats why we check to see if lenght of the field is 0 and process from there. 
		if(amountReturned.value.length != 0)
		{
			//check to see if its a digit and process 
			if(isDigit(amountReturned.value))
			{
				//can submit
			}
			else
			{
				errorBuilder+="Amount Returned field needs to contain numerical data. ";
				amountReturned.style.backgroundColor="#8B0000";	
				theForm.amountReturned.focus();
				amountReturnedError = true;
			}
		}
		//if we arrive here we know they didnt fill out the form field
		else
		{
				errorBuilder+="Please fill out Amount Returned field. ";
				amountReturned.style.backgroundColor="#8B0000";	
				theForm.amountReturned.focus();
				amountReturnedError = true;
		}
		
		/* --> uncomment this and delete above check if you want to make amount returned field not required.
		//check to see if amount returned field is filled out.  If not then build error string 
		if(amountReturned.value.length > 0)
		{					
			if(isDigit(amountReturned.value))
			{
				//can submit
			}
			else
			{
				errorBuilder+="Amount returned field must contain numerical data. ";
				amountReturned.style.backgroundColor="#8B0000";	
				theForm.amountReturned.focus();
				amountReturnedError = true;
			}			
		}
		*/		
		
		//check to see if errorBuilder string is present if so then alert the user the errors occured.
		//If errors aren't present then we can submit the form.
		if(errorBuilder.length > 0)
			alert(errorBuilder);
		else
		{
			theForm.submit();
		}
		
	}//end with
	
}// end checkform

//function used to handle clearing of textarea when a user makes an error.  Parameter whatArea is used as an
//indicator which signals what form field to clear.  Check inputsearchForm.php form onclick event handler for dispatches to here
function clearTextArea(whatArea)
{
	switch(whatArea)
	{
			//if email error is true we know that error did occur so we clear that field. this has to be done or else 
			//everytime user presses on the field it will turn blank making the form annoying.  same goes for rest of case checks.
			case "email":     if(emailError == true)
				    { 
						document.myInputForm.email.style.backgroundColor = "#FFFFFF";
						document.myInputForm.email.value = "";	
						emailError = false;
					}
					else
					{ }
					break;			   
			//clear leftrange field in inputSearchForm.php   
			case "leftRange":  if(leftRangeError == true)
								{						  
									document.myInputForm.leftRange.style.backgroundColor="#FFFFFF"; 						  
									document.myInputForm.rightRange.style.backgroundColor="#FFFFFF"; 						  
									document.myInputForm.rightRange.value="";									
									document.myInputForm.leftRange.value="";									
									leftRangeError = false;
									rightRangeError = false;									
								}
								else
								{ }							
								break;
		//clear rightrange field in inputSearchForm.php			   
		case "rightRange":    if(rightRangeError == true)
							  {
									document.myInputForm.leftRange.style.backgroundColor="#FFFFFF"; 						  
									document.myInputForm.rightRange.style.backgroundColor="#FFFFFF"; 						  
									document.myInputForm.rightRange.value="";									
									document.myInputForm.leftRange.value="";									
									leftRangeError = false;
									rightRangeError = false;
							  }
							  else
							  { }
							  break;
		//clear amount returned field in inputSearchForm.php 			   
		case "amount":   if(amountReturnedError == true)
				  {
						document.myInputForm.amountReturned.style.backgroundColor="#FFFFFF";
						document.myInputForm.amountReturned.value = "";	
						amountReturnedError = false;
				  }
				  else
				  { }
				  break;
		//clear userdropdown field in inputSearchForm.php 
		case "userDropdown":   if(userDropdownError == true)
				  {
						document.myInputForm.userDropdown.style.backgroundColor="#FFFFFF";
						document.myInputForm.userDropdown.value = "";	
						userDropdownError = false;
				  }
				  else
				  { }
				  break;
	}
}

function isDigit(myString) 
{
	return /^ *[0-9]+ *$/.test(myString);
}

