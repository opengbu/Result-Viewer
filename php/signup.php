<?php

 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_GET['cid']) && isset($_GET['rno']) && isset($_GET['pass']) && $_GET['cid']!="" && $_GET['rno']!="" && $_GET['pass']!="") {
 
    $cid = $_GET['cid'];
    $rno = $_GET['rno'];
    $pass = $_GET['pass'];
	
	$conn=new PDO('mysql:host=localhost;dbname=result','root' ,'');
	$p=$conn->query("select * from users where Roll_No='$rno';");
	
	if($p->rowcount()>0)
	{
		 $response["success"] = 0;
         $response["message"] = "User Already Exists!";
		 echo json_encode($response);
	}
	else
	{
	//encrypted password
	
	$password = password_hash($_GET['pass'],PASSWORD_DEFAULT);
	
	//$conn=new PDO('mysql:host=localhost;dbname=result','root' ,'');
	$q=$conn->query("INSERT INTO users (College_Id,Roll_No,Password)VALUES('$cid','$rno','$password');");
 
    // check if row inserted or not
    if ($q->rowcount()>0) {
        // successfully inserted into database
		
        $response["success"] = 1;
        $response["message"] = "Product successfully created.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
 
        // echoing JSON response
        echo json_encode($response);
    }
}} else {
    // required field is missing
	
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}

?>
