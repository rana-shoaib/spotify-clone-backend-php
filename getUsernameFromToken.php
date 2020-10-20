<?php
   function getUsernameFromToken($token,$conn)
   {
   	 if($query = mysqli_query($conn,"SELECT userId FROM loginouth WHERE outhToken = '$token' "))
	{
	  return $username = mysqli_fetch_assoc($query)['userId'];
		
	}
   }
   


  ?>