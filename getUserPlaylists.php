<?php 
  header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: *");
	header('Content-Type: application/json');
	require 'conn.php';
    require 'getUsernameFromToken.php';

    $token = $_GET['token'];
    $username = getUsernameFromToken($token,$conn);
    $respnse = [];
    $respnse["playlists"] = [];
    
    $query = mysqli_query($conn,"SELECT * FROM playlists WHERE owner = '$username' ");

    while ($row = mysqli_fetch_assoc($query))
    {
        array_push($respnse['playlists'], [
            'id'=>$row['id'],
            'title'=>$row['title']

        ]);
    }

    echo json_encode($respnse);