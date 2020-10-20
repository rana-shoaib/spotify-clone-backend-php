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
    $albumId = $data['albumId'];
    $username = getUsernameFromToken($token,$conn);

    $sql = mysqli_query($conn, 
                              "SELECT id FROM albumlikers WHERE username= '$username' AND albumId = '$albumId'");
             if(mysqli_num_rows($sql)==1)
             {
                   $query = mysqli_query($conn,"DELETE  FROM albumlikers WHERE username= '$username' AND albumId = '$albumId' ");
                   mysqli_query($conn,"UPDATE albums SET likes = likes - 1 WHERE id = '$albumId' ");
                   $response['isLikedAlbum'] = false;
                   
             }else{
               if($query = mysqli_query($conn,"INSERT INTO albumlikers(albumId,username) VALUES('$albumId','$username')"))
               {
                 mysqli_query($conn,"UPDATE albums SET likes = likes + 1 WHERE id = '$albumId' ");
                 $response['isLikedAlbum'] = true;
               }else{
                $response['isLikedAlbum'] = false;
               }

             }

echo json_encode($response);