<?php
    header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: *");
	header('Content-Type: application/json');
	$loginToken = $_GET['loginToken'];
	$userInfo = [];
	
	require 'conn.php';
	if($query = mysqli_query($conn,"SELECT userId FROM loginouth WHERE outhToken = '$loginToken' "))
	{
		$username = mysqli_fetch_assoc($query)['userId'];
		if($query = mysqli_query($conn,"SELECT * FROM users WHERE username = '$username' "))
		{
			if(mysqli_num_rows($query)==1)
			{
				$result = mysqli_fetch_assoc($query);
				$userInfo['name'] = $result['name'];
				$userInfo['email'] = $result['email'];
				$userInfo['avatarPath'] = $result['avatarPath'];
				$userInfo['isProMember'] = $result['isProMember'];
			}
		}
	}

	echo json_encode($userInfo);
  ?>