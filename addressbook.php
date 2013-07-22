<?php

require_once('TwitterAPIExchange.php');

$con = mysql_connect("localhost","jessgycy_jess","pass123") or die("Could not connect: " . mysql_error()); 
mysql_select_db("jessgycy_addressbook");

/** 
* Function to get follower count for twitter handle
* v1 is deprecated, this uses v1.1 which requires authentication
* This utilizes a wrapper: TwitterAPIExchange
* @param <string> $twitterHandle: a users twitter handle
* @return <int>: a user's follower count
*/
function getFollowerCount($twitterHandle){
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
	'oauth_access_token' => "154227302-YzuGl2IN8XFKdCrcMQqER5kjlVxD7cGVW9YpIT0S",
	'oauth_access_token_secret' => "njuLYHBs5PZID1x78FkvuRdVsghMAYGKoRQsBVM",
	'consumer_key' => "VVTh3Kk3x0nHVeFCND5NQ",
	'consumer_secret' => "b4zJ17QViGr7KB4Yi7zEHmYiQYUSau2K7qwPJ0K8rM"
	);


	$ta_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?screen_name='.$twitterHandle;
	$requestMethod = 'GET';
	$twitter = new TwitterAPIExchange($settings);
	$follow_count=$twitter->setGetfield($getfield)
		->buildOauth($ta_url, $requestMethod)
		->performRequest();
	$data = json_decode($follow_count, true);
	$followers_count=$data[0]['user']['followers_count'];
	return $followers_count;
}

/**
 * Series of functions to validate query parameters
 * @param [name,phone number, twitter handle, follower count, id]
 * @return <boolean>: true or false depending on a matching regex or not
 * /
function validateName($name){
	return preg_match('/\D/', $name);
}
function validatePhone($phone){
	return preg_match('/[0-9]{7,11}/', $phone);
}
function validateTwitter($twitter){
	return preg_match('/^[A-Za-z0-9_]{1,15}$/', $twitter);
}
function validateCount($count){
	return preg_match('/[0-9]+/', $count);
}
function validateID($id){
	return preg_match('/[0-9]+/', $id);
}

/** 
* Function to save new contact 
* @param <string> $name: name of the contact 
* @param <string> $phone: phone number of the contact 
* @param <string> $twitter: twitter handle of contact
*/ 
function saveContact($name,$phone,$twitter){ 
              $count = (int)getFollowerCount($twitter);
              $name = mysql_real_escape_string($name);
              $twitter = mysql_real_escape_string($twitter);
              $phone = mysql_real_escape_string($phone);
              $count = mysql_real_escape_string($count);

	      if(validateName($name) && validatePhone($phone) && validateTwitter($twitter) && validateCount($count)){
              		$sql="INSERT INTO address (name, phone, twitter, followercount) VALUES 
              		('$name',
              		'$phone',
              		'$twitter',
              		'$count')";
              		$result=mysql_query($sql)or die(mysql_error()); 
              }else{
              		die("invalid entries");
              }
} 

/** 
* Function to delete contact
* @param <int> id //the contact id in database we wish to delete 
*/ 
function deleteContact($id){ 
	      $id = mysql_real_escape_string($id);
	      if(validateID($id)){	
              		$sql='DELETE FROM address WHERE id="'.$id.'"';
              		$result=mysql_query($sql) or die(mysql_error());  
              }else{
              		die("invalid entry");
              }
} 

/**
* Function to retrieve list of contacts 
* @return array of contacts
*/
function getContacts(){  
              $sql="SELECT * FROM address ORDER BY name ASC"; 
              $result=mysql_query($sql) or die(mysql_error()); 
              $contacts=array(); 
              while($record=mysql_fetch_object($result)){ 
                        array_push($contacts,$record); 
              } 
              return $contacts; 
} 


/**
* Function to update follower count of all contacts
*/
function updateFollowerCount(){
	
	$sql="SELECT * FROM address";
	$result = mysql_query($sql) or die(mysql_error()); 
	$contacts = array();
	while($record=mysql_fetch_array($result)){
		$contacts[] = $record;
	}
	foreach($contacts as $val){
		$count = (int)getFollowerCount($val["twitter"]);
                $twitter = mysql_real_escape_string($val["twitter"]);
                $count = mysql_real_escape_string($count);
                if(validateTwitter($twitter) && validateCount($count)){	
			$query = "UPDATE address SET followercount=".$count." WHERE twitter='" . $twitter ."'";
			$data = mysql_query($query) or die(mysql_error());
		}
	}
}


// AJAX calls
$action=$_POST['action']; 
if($action=="add"){ 
              $name=$_POST['name']; 
              $phone=$_POST['phone'];
              $twitter=$_POST['twitter'];
              saveContact($name,$phone,$twitter); 
              $output['msg']=$name." has been saved successfully"; 
              $output['contacts']=getContacts(); 
              echo json_encode($output); 
}else if($action=="delete"){  
              $id=$_POST['id']; 
              deleteContact($id); 
              $output['msg']="one entry has been deleted successfully"; 
              $output['contacts']=getContacts(); 
              echo json_encode($output); 
}else if($action=="update"){
	     updateFollowerCount();
	     $output['msg']="Follower count updated successfully";
	     $output['contacts']=getContacts();
	     echo json_encode($output);	
}else{ 
              $output['contacts']=getContacts(); 
              $output['msg']="list of all contacts"; 
              echo json_encode($output); 
} 

mysql_close($con);
?>
