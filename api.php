<?php

 require('conn.php');

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
	$alisha = [
		'albums'=>[],
    'artists'=>[]
	];
     

    $result = mysqli_query($conn,"SELECT * FROM albums ORDER BY RAND()");

    while($row = mysqli_fetch_assoc($result))
    {
    	array_push($alisha['albums'], [
           'id'=>$row['id'],
           'title'=>$row['title'],
           'artist'=>$row['artist'],
           'discription'=>$row['discription'],
           'artPath'=>$row['artPath'],
           'likes'=>$row['likes'],
    	]);
    }

    $query = mysqli_query($conn,"SELECT * FROM artists ORDER BY RAND()");
     while($r=mysqli_fetch_assoc($query))
              {
                array_push($alisha['artists'], [
                  'id'=>$r['id'],
                   'name'=>$r['name'],
                   "img"=>$r['imageUrl']
                ]);
              }
     header('Content-Type: application/json');
	echo json_encode($alisha,JSON_PRETTY_PRINT);

?>

