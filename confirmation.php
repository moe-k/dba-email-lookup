<html>
<head>
<title>Confirmation</title>
<script type="text/javascript" src="js/inputSearchForm.js"></script>
<script language="javascript">
//function recieves what button user pressed and directs them to the corresponding page
function init(what)
{
	switch(what)
	{
		case 1:
				window.location = "inputSearchForm.php";
				break;
		case 2:
				window.location = "report.php";			
				break;
	}
}
</script>
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
		color:#B22222;
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
<center><h1>Confirmation</h1></center>
	<br />
	<div id="container">
		<center><p>Your Email was successfully logged on <?echo date("F j, Y, g:i a");?></p></center>
		<br />
		<center>
			<input type="button" value="Run a Report" onclick="init(2)">
			<input type="button" value="Input a New Email" onclick="init(1)">
		</center>
		
		
	</div>
</body>
</html>