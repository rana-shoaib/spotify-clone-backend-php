<?php
   

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");
	header('Content-Type: application/json');
	

	$data = [
              "albums"=>[],
              "artists"=>[]
       ];
	require 'conn.php';

	if(isset($_GET['query']))
	{
       if(!$conn)
       {
       	 $data['error'] = 'There is a problem in our side plz try agian in few momments';
       }else{
       	  $id = $_GET['query'];
       	  if(!$result = mysqli_query($conn,"SELECT * FROM albums WHERE title LIKE'%$id%'"))
       	  {
            $data['error'] = 'There is a problem in our side plz try agian in few momments';
       	  }else{
       	  	if(mysqli_num_rows($result)<1)
       	  	{
       	  		$data['warning'] = 'Nothing match with your parameters';
       	  	}else{
       	  		while($row=mysqli_fetch_assoc($result))
       	  		{
       	  		   	array_push($data['albums'], [

                                       'id'=>$row['id'],
                                       'title'=>$row['title'],
                                       'artist'=>$row['artist'],
                                       'discription'=>$row['discription'],
                                       'artPath'=>$row['artPath'],
                                       'likes'=>$row['likes'],
                                   ]);

       	  		}

              $sql = mysqli_query($conn,"SELECT * FROM artists WHERE name LIKE '%$id%' ");
              while($r=mysqli_fetch_assoc($sql))
              {
                array_push($data['artists'], [
                  'id'=>$r['id'],
                   'name'=>$r['name'],
                   "img"=>$r['imageUrl']
                ]);
              }
       	  	}
       	  }
       }
	}else{
		$data['error'] = 'You must provide album id to fetch album data. Is it not abvious?';
	}

	echo json_encode($data);
  ?>