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
    $songId = $data['songId'];
    $playlistId = $data['playlistId'];
    $username = getUsernameFromToken($token,$conn);

   if($songId != '' &&  $playlistId != '' && $songId != '')
   {
    if(mysqli_query($conn,"INSERT INTO palylistsongs(songId,playlistId,username) VALUES('$songId','$playlistId','$username')"))
      {
         $response['isCreated'] = true;
      }else{
        $response['isCreated'] = false;
      }
    }else{
      $response['isCreated'] = false;
    }

echo json_encode($response);