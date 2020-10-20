<?php

  function getUniqId()
  {
    $random = rand(9999,99999999999);
    $random =  md5($random);
    $random = password_hash($random, PASSWORD_DEFAULT);
    $random = password_hash($random, PASSWORD_DEFAULT);
    $random2 = password_hash(rand(9999,99999999999)."akjiaojah9u abhgyagyaiab aagy8ag8g8a a8yvbaf aba8gag8a ", PASSWORD_DEFAULT);
    $random = $random.$random2;
    $milliseconds = round(microtime(true) * 1000);
    $random2 = password_hash($milliseconds, PASSWORD_DEFAULT);
    $random = $random.$random2;
    return $random;


  }

  // echo getUniqId();

// $unid = getUniqId();
// 		$response = [];
		
// 		   $response['sucess'] = $wasSignupSucess;
// 		// $response['credientials'] = [
// 		// 	'name'=>$name,
// 		// 	'username'=>$username,
// 		// 	'email'=>$email,
// 		// 	'loginToken'=>uniqid(),
// 		// ];
// 		   $response['loginToken'] = $unid;
		


	
//     echo json_encode($response);
  ?>

