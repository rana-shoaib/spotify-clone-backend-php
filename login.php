<?php 
    header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: *");

	require 'conn.php';
	require 'getUniqId.php';

   $data =  file_get_contents('php://input');
   $error = '';
   $wasLoginSucess = false;
   $response = [];
   $data = json_decode($data);
   $data = objectToArray($data);

   function objectToArray ($object) {
    if(!is_object($object) && !is_array($object)){
    	return $object;
    }
    return array_map('objectToArray', (array) $object);
   }
    $data = $data['loginData'];
    $usernameOrEmail = $data['usernameOrEmail'];
    $pwd = md5($data['password']);
    $token = getUniqId();
    // checking emty filds

    if($usernameOrEmail == '' || $pwd == '')
    {
      $error = "Plz Complete all fields";
    }else{
      $query = mysqli_query($conn, 
"SELECT username FROM users WHERE username = '$usernameOrEmail' AND pwd = '$pwd' OR email='$usernameOrEmail' AND pwd='$pwd' ");

      if(mysqli_num_rows($query)==1)
      {
        $wasLoginSucess = true;
        $username = mysqli_fetch_assoc($query)['username'];
        mysqli_query($conn,"UPDATE loginouth SET outhToken = '$token' WHERE userId = '$username' ");


      }else{
        $error = "username/emal or password was wrong ";
      }
    }

    $response['sucess'] = $wasLoginSucess;

    if($wasLoginSucess){
      $response['token'] = $token;
    }else{
      $response['error'] = $error;
    }
    
    echo json_encode($response);
    
 ?>