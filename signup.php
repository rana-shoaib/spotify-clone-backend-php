<?php 
    header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: *");
	header('Content-Type: application/json');
	require 'conn.php';
	require 'getUniqId.php';

   $data =  file_get_contents('php://input');
   $errorArray = [];
   $wasSignupSucess = false;
   $response = [];
   $data = json_decode($data);
   $data = objectToArray($data);

   function objectToArray ($object) {
    if(!is_object($object) && !is_array($object)){
    	return $object;
    }
    return array_map('objectToArray', (array) $object);
   }
    $data = $data['signupData'];
    // checking emty filds
    foreach ($data as $key => $value) {
    	if($value=='')
    	{
    		$errorArray['filesNotFilled'] = "plz Cmplete all fields";
    	}
    }
    // validating Name
    if(trim($data['name'])=='')
    {
    	$errorArray['name'] = "Name is required";
    }else{

    	if(!preg_match("/^[a-zA-Z ]*$/",$data['name'])) {
		if($data['name']!='')
		{
			$errorArray['name'] = "Name can only conatins alphabets";
		}
	}

    }
   //  validating email
    if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
    {
    	if($data['email']!=='')
    	{
    		$errorArray['email'] = "Invalid Email";
    	}else{
    		$errorArray['email'] = "Email is required";
    	}
    }
    // validating username

    if(trim($data['username'])=='')
    {
    	$errorArray['username'] = "Username is required";
    }else{

    	if(!preg_match("/^[a-zA-Z0-9]*$/",$data['username'])) {
		if($data['username']!='')
		{
			$errorArray['username'] = "Username can only consist of number and alphabets";
		}
	 }else{
			// Making sure username does not already exist
		   $username = $data['username'];
		   $query = mysqli_query($conn,"SELECT username FROM users WHERE username='$username'");
		   if(mysqli_num_rows($query)!=0)
		   {
		   	 $errorArray['username'] = "This username is taken";
		   }
		}

    }

    
      

	// veryfying two passwords matches

	if($data['password']=='')
	{
		$errorArray['password'] = "Password is required";
	}else{
		if($data["password"]!=$data['passwordAgain'])
		{
			$errorArray['password'] = "Two password does not matches";
		}else{
			if(strlen($data['password'])<8)
			{
				$errorArray['password'] = "Password must be 8 character long";
			}
		}
	}

	 $name = $data['name'];
   	 $email = $data['email'];
   	 $username = $data['username'];
   	 $password = $data['password'];
   	 $avatarPath = "https://ui-avatars.com/api/?background=1DB954&color=fff&name=".$name;
   if(empty($errorArray))
   {
   	 
   	 $hashPassword = md5($password);
   	 if($query = mysqli_query($conn, 
   	 "INSERT INTO users(name,email,username,pwd,avatarPath ,isProMember) 
   	 VALUES('$name','$email','$username','$hashPassword','$avatarPath','0')"))
   	 {
   	 	$wasSignupSucess = true;
   	 }
   }
	if(!$wasSignupSucess)
	{
	  $response['sucess'] = $wasSignupSucess;
	  $response['errors'] = $errorArray;
	}else{
		$unid = getUniqId();
		$query = mysqli_query($conn, "INSERT INTO loginouth(userId,outhToken)  VALUES ('$username','$unid')");
		
		   $response['sucess'] = $wasSignupSucess;
		// $response['credientials'] = [
		// 	'name'=>$name,
		// 	'username'=>$username,
		// 	'email'=>$email,
		// 	'loginToken'=>uniqid(),
		// ];
		   $response['loginToken'] = $unid;
		


	}
    echo json_encode($response);
    
 ?>