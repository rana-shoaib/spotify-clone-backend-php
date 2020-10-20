<?php
   

       header('Access-Control-Allow-Origin: *');
       header('Access-Control-Allow-Methods: GET, POST');
       header("Access-Control-Allow-Headers: X-Requested-With");
       header('Content-Type: application/json');
       require 'conn.php';
       require 'getUsernameFromToken.php';

       $data = [];
       $token = $_GET['token'];
       $username = getUsernameFromToken($token,$conn);
       

       if(isset($_GET['id']))
       {
       if(!$conn)
       {
               $data['error'] = 'There is a problem in our side plz try agian in few momments';
       }else{
                $id = $_GET['id'];
                if(!$result = mysqli_query($conn,"SELECT * FROM artists WHERE id = '$id'"))
                {
            $data['error'] = 'There is a problem in our side plz try agian in few momments';
                }else{
                     if(mysqli_num_rows($result)<1)
                     {
                            $data['warning'] = 'Nothing match with your parameters';
                     }else{
                            while($row=mysqli_fetch_assoc($result))
                            {
                                // $artistId = $row['artist'];
                                // $query = mysqli_query($conn,"SELECT name FROM artists WHERE id = '$artistId' ");
                                // $artist = mysqli_fetch_assoc($query)['name'];
                                $data['artist'] = [];
                                $data['artist']['id']=$row['id'];
                                $data['artist']['name']=$row['name'];
                                // $data['img']['artist']=$artist;
                                $data['artist']['img']=$row['imageUrl'];
                                // $data['album']['artPath']=$row['artPath'];
                                // $data['album']['likes']=$row['likes'];
                            }

                            if($result = mysqli_query($conn,"SELECT * FROM songs WHERE artist = '$id'"))
                            {
                                   $data['songs'] = [];
                                   while($row=mysqli_fetch_assoc($result)){
                                          $artistId = $row['artist'];
                                          $result2 = mysqli_query($conn,"SELECT name FROM artists WHERE id='$artistId'");
                                          $artist = mysqli_fetch_assoc($result2)['name'];

                                          //is liked by user or not
                                          $songId = $row['id'];
                                          $isLiked = false;
                                         
                                                 $sql2 = mysqli_query($conn, 
                                          "SELECT uername FROM likedsongs WHERE uername= '$username' AND songId = '$songId'  ");
                                                 if(mysqli_num_rows($sql2)==1)
                                                 {
                                                        $isLiked = true;
                                                 }
                                          array_push($data['songs'], [
                                                 'id'=>$row['id'],
                                                 'title'=>$row['title'],
                                                 'artist'=>$artist,
                                                  'artistId'=>$artistId,
                                                 'album'=>$row['album'],
                                                 'songUrl'=>$row['songUrl'],
                                                 'artPath'=>$row['artPath'],
                                                 'duration'=>$row['duration'],
                                                 'genre'=>$row['genre'],
                                                 'likes'=>$row['likes'],
                                                 'isLikedByUser'=>$isLiked,
                                          ]);
                                   }
                            }

                            $data['albums'] = [];
                            if($result = mysqli_query($conn,"SELECT * FROM albums WHERE artist = '$id'"))
                            {
                                   
                                   while($row=mysqli_fetch_assoc($result)){
                                          $artistId = $row['artist'];
                                          $result2 = mysqli_query($conn,"SELECT name FROM artists WHERE id='$artistId'");
                                          $artist = mysqli_fetch_assoc($result2)['name'];
                                          array_push($data['albums'], [
                                                 'id'=>$row['id'],
                                                 'title'=>$row['title'],
                                                 'artist'=>$artist,
                                                 'discription'=>$row['discription'],
                                                 'artPath'=>$row['artPath'],
                                                 'likes'=>$row['likes'],
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