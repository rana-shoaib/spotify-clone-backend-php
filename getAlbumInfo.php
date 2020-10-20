<?php
   

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");
	header('Content-Type: application/json');
	

	$data = [];
       $album = '';
	require 'conn.php';
       require 'getUsernameFromToken.php';

	if(isset($_GET['id']))
	{
       if(!$conn)
       {
       	 $data['error'] = 'There is a problem in our side plz try agian in few momments';
       }else{
       	  $id = $_GET['id'];
       	  if(!$result = mysqli_query($conn,"SELECT * FROM albums WHERE id = '$id'"))
       	  {
            $data['error'] = 'There is a problem in our side plz try agian in few momments';
       	  }else{
       	  	if(mysqli_num_rows($result)<1)
       	  	{
       	  		$data['warning'] = 'Nothing match with your parameters';
       	  	}else{
       	  		while($row=mysqli_fetch_assoc($result))
       	  		{
                              //check weather album liked by user or not
                               $isAlbumLiked = false;
                               $albumId = $row['id'];
                               if($_GET['token']!="undefined")
                               {
                                   $token = $_GET['token'];
                                   $username  =  getUsernameFromToken($token,$conn);
              $sql = mysqli_query($conn,"SELECT id FROM albumlikers WHERE username = '$username' AND albumId = '$albumId'  ");
                                              if(mysqli_num_rows($sql)==1)
                                                 {
                                                        $isAlbumLiked = true;
                                                 }

                               }
	  			   $artistId = $row['artist'];
	  			   $query = mysqli_query($conn,"SELECT name FROM artists WHERE id = '$artistId' ");
	  			   $artist = mysqli_fetch_assoc($query)['name'];
                               $album = $row['title'];
	  			   $data['album'] = [];
	  			    $data['album']['id']=$row['id'];
			           $data['album']['title']=$row['title'];
			           $data['album']['artist']=$artist;
			           $data['album']['discription']=$row['discription'];
			           $data['album']['artPath']=$row['artPath'];
			           $data['album']['likes']=$row['likes'];
                                $data['album']['albumColor']=$row['artColor'];
                                $data['album']['isLikedByUser']=$isAlbumLiked;

       	  		}

       	  		if($result = mysqli_query($conn,"SELECT * FROM songs WHERE album = '$id'"))
       	  		{
       	  			$data['songs'] = [];
       	  			while($row=mysqli_fetch_assoc($result)){
       	  				$artistId = $row['artist'];
       	  				$result2 = mysqli_query($conn,"SELECT name FROM artists WHERE id='$artistId'");
       	  				$artist = mysqli_fetch_assoc($result2)['name'];
                                          
                                          $isLiked = false;
                                          if($_GET['token']!="undefined")
                                          {      $token = $_GET['token'];
                                                 $username  =  getUsernameFromToken($token,$conn);
                                                 $songId = $row['id'];
                                                 $sql = mysqli_query($conn, 
                                          "SELECT uername FROM likedsongs WHERE uername= '$username' AND songId = '$songId'  ");
                                                 if(mysqli_num_rows($sql)==1)
                                                 {
                                                        $isLiked = true;
                                                 }
                                          }

       	  				array_push($data['songs'], [
       	  					'id'=>$row['id'],
       	  					'title'=>$row['title'],
       	  					'artist'=>$artist,
       	  					'artistId'=>$artistId,
       	  					'albumId'=>$row['album'],
                                                 "album"=>$album,
       	  					'songUrl'=>$row['songUrl'],
       	  					'artPath'=>$row['artPath'],
       	  					'duration'=>$row['duration'],
       	  					'genre'=>$row['genre'],
       	  					'likes'=>$row['likes'],
                                                 'isLikedByUser'=>$isLiked
       	  				]);
       	  			}
       	  		}
                


       	  	}
       	  }
       }
	}else{
		$data['error'] = 'You must provide album id to fetch album data. Is it not abvious?';
	}

	echo json_encode($data);
     
  ?>