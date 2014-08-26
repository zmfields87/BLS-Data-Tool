<?php
	
if($_POST['submit']){	
	
if(empty($_POST['jobtitles'])) echo "<strong>Error: You have not entered a Job Title, please go back and enter one.</strong>";

else echo "<strong>Your documents are processing and being exported.</strong>";

}
?>

<head>
	
	
	
</head>

<body>
<center><strong><u>BLS Data Processor and Exporter</u></strong></center>

<form method="post" action="handler.php">

<p>Enter all of the desired jobs, each separated by a new line. Note: All jobs must be spelled exactly as contained in the BLS data</p>

<textarea name="jobtitles" rows="10" cols="50">
</textarea>

<p>Select the vertical this data is for</p>

<select name="vertical" value="">
	<option value="bus">Business</option>
	<option value="edu">Education</option>
	<option value="nurse">Nursing</option>
</select>

<p>Select the tables you wish to be exported</p>

<input type ="checkbox" name="tabletype[]" value="local">Local

<input type ="checkbox" name="tabletype[]" value="msa">MSA

<input type ="checkbox" name="tabletype[]" value="nmsa">NMSA

<input type ="checkbox" name="tabletype[]" value="state">State

<input type ="checkbox" name="tabletype[]" value="nat">National


<p>Upon clicking submit, your tables will be generated and exported as a CSV file</p>

<input type="submit" value="Submit and Export">
	
</form>

</body>