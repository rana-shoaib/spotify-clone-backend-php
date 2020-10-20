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
    $username = getUsernameFromToken($token,$conn);

    $sql = mysqli_query($conn, 
                              "SELECT uername FROM likedsongs WHERE uername= '$username' AND songId = '$songId'  ");
             if(mysqli_num_rows($sql)==1)
             {
                   $query = mysqli_query($conn,"DELETE  FROM likedsongs WHERE uername= '$username' AND songId = '$songId' ");
                   $response['isLikedSong'] = false;
                   
             }else{
               if($query = mysqli_query($conn,"INSERT INTO likedsongs(uername,songId) VALUES('$username','$songId')"))
               {
                 $response['isLikedSong'] = true;
               }else{
                $response['isLikedSong'] = 'lll';
               }

             }

echo json_encode($response);
