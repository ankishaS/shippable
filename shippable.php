<!--HTML form to take user input -->
<html>
<body>
<form name="issueForm" action="shippable.php" method="POST">
<input type="text" name="inputUrl" placeholder="Please provide input url" size="50">
<input type="submit" name="button" size="25">
</form>
</body>
</html>

<?php
if(isset($_POST['button']))// Will be set IFF 'submit' button is clicked
{
	//Step 1: Take URL Example: https://github.com/Shippable/support/issues
	$url = $_POST['inputUrl'];
	
	//Step 2:  Parsing the input url
	$pieces =  parse_url($url);
	
	//Step 3:Check for valid url
	if($pieces['scheme'] != "https" || empty($pieces['host']) || empty($pieces['path']))
	{
		die("ERROR: Url is not valid. Missing protocol");
	}
	
	
	//Step 4: preparing gitHub api url from input url, url structure : https://api.github.com/repos/organisation/repository/
	$pathArr = explode('/',$pieces['path']);
	$finalUrl = "https://api.github.com/repos/".$pathArr[1]."/".$pathArr[2];
	
	//Step 5: Fetching data for total open issues
	$data = execCurl($finalUrl); //calling function to get result in an array
	$open_issues_count = $data["open_issues_count"];//Total open issues
	
	//Step 6: Fetching data for open issues in last 24 hours
	$dateLast24hr  = date('Y-m-d', strtotime($date .' -1 day'));// Date and time 1 day ago in ISO format
	//Url for calling gitHub api and passing new param since=date that returns only issues updated at or after this date/time
	$finalUrl = "https://api.github.com/repos/".$pathArr[1]."/".$pathArr[2]."/".$pathArr[3]."?since=".$dateLast24hr;     
	$data = execCurl($finalUrl); //Calling function to get result in an array
	$issues_last24hr_count = count($data);  //Count of open issues in last 24 hours
	
	//Step 7: Fetching data for open issues  less than 7 days
	$dateLast7days  = date('Y-m-d', strtotime($date .' -7 day'));// Date and time 7 days ago in ISO format
	//Url for calling gitHub api and passing new param since=date that returns only issues updated at or after this date/time
	$finalUrl = "https://api.github.com/repos/".$pathArr[1]."/".$pathArr[2]."/".$pathArr[3]."?since=".$dateLast7days;     
	$data = execCurl($finalUrl); //Calling function to get result in an array
	$issues_last7day_count = count($data);  //Count of open issues in last 7 days
	
	//Step 8: Table To display data
	echo '<html>';
	echo '<table border="1" style="width:50%, border-color:"black""  >';
	echo '<tbody>';
	echo '<tr>';
	echo '<th bgcolor="#99bbff">Track Open Issues</th><th bgcolor="#99bbff">Count</th>';
	echo '</tr>';
	echo '<tr style="font-weight:bold"><td>Total number of open issues</td><td>'.$open_issues_count.'</td></tr>';
	echo '<tr><td>Number of open issues that were opened in the last 24 hours</td><td>'.$issues_last24hr_count.'</td></tr>';
	echo '<tr><td>Number of open issues that were opened more than 24 hours ago but less than 7 days ago</td><td>'.($issues_last7day_count-$issues_last24hr_count).'</td></tr>';
	echo '<tr><td>Number of open issues that were opened more than 7 days ago </td><td>'.($open_issues_count-$issues_last7day_count).'</td></tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</html>';
}       

//API to execute curl command and return response
function execCurl($url){
	$data= '';
	
	// Setting up the curl options in an array
	$options = array(
		CURLOPT_URL            => $url,
		CURLOPT_USERAGENT      => user,
		CURLOPT_HTTPHEADER     => array( 'Accept: application/json'),
		CURLOPT_RETURNTRANSFER => true
	);
	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$data = json_decode(curl_exec($ch),true); //Decode the json in array
	curl_close($ch);
	return $data;
}

?>