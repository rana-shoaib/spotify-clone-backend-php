<?php 
  
   header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: *");
	header('Content-Type: application/json');
    require 'conn.php';
    require 'getUsernameFromToken.php';

   $data =  file_get_contents('php://input');
   $response = [];
   $data = json_decode($data);
   $data = objectToArray($data);

   function objectToArray ($object) {
    if(!is_object($object) && !is_array($object)){
    	return $object;
    }
    return array_map('objectToArray', (array) $object);
   }
    $data = $data['data'];
    $token = $data['token'];
    $playlistName = $data['playlistName'];
    $username = getUsernameFromToken($token,$conn);
    $id = uniqid();

    if($username==""|| $playlistName == "")
    {
    	$response['isCreated']=false;
    }else{
    	 if(mysqli_query($conn,"INSERT INTO playlists(id,title,owner) VALUES('$id','$playlistName','$username')"))
	    {
	      $response['isCreated']=true;
        $response['title']=$playlistName;
        $response['id']=$id;
	    }else{
	    	$response['isCreated']=false;
	    }

    }

   
    echo json_encode($response);
 ?>