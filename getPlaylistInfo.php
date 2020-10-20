<?php 
  header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET, POST');
      header("Access-Control-Allow-Headers: *");
      header('Content-Type: application/json');
      require 'conn.php';
      require 'getUsernameFromToken.php';

    $token = $_GET['token'];
    $username = getUsernameFromToken($token,$conn);
    $playlistId = $_GET['playlistId'];
    $respnse = [];
    $respnse["songs"] = [];

    $sql = mysqli_query($conn,"SELECT title FROM playlists WHERE id = '$playlistId'");
    $title = mysqli_fetch_assoc($sql)['title'];
    $id = mysqli_fetch_assoc($sql)['id'];
    $respnse['title'] = $title;
    $respnse['id'] = $id;

    $sql = mysqli_query($conn,"SELECT songId FROM palylistsongs WHERE playlistId ='$playlistId' ");

    while($row = mysqli_fetch_assoc($sql))
    {
        $songId = $row['songId'];
      if($result = mysqli_query($conn,"SELECT * FROM songs WHERE id = '$songId' "))
                        {
                              while($row2=mysqli_fetch_assoc($result)){

                                    // get song artist
                                  $artistId = $row2['artist'];
                                    $result2 = mysqli_query($conn,"SELECT name FROM artists WHERE id='$artistId'");
                                    $artist = mysqli_fetch_assoc($result2)['name'];
                        
                        //get song album
                        $albumId = $row2['album'];
                        $query = mysqli_query($conn,"SELECT title FROM albums WHERE id = '$albumId' ");
                        $album = mysqli_fetch_assoc($query)['title'];

                        //like by user or not

                        $isLiked = false;
                                         
                                                 $sql2 = mysqli_query($conn, 
                                          "SELECT uername FROM likedsongs WHERE uername= '$username' AND songId = '$songId'  ");
                                                 if(mysqli_num_rows($sql2)==1)
                                                 {
                                                        $isLiked = true;
                                                 }

                                    array_push($respnse["songs"], [
                                          'id'=>$row2['id'],
                                          'title'=>$row2['title'],
                                          'artist'=>$artist,
                                          'artistId'=>$artistId,
                                          'albumId'=>$row2['album'],
                                          "album"=> $album,
                                          'songUrl'=>$row2['songUrl'],
                                          'artPath'=>$row2['artPath'],
                                          'duration'=>$row2['duration'],
                                          'genre'=>$row2['genre'],
                                          'likes'=>$row2['likes'],
                                          'isLikedByUser'=>$isLiked, 
                                    ]);
                              }
                        }
    }

    echo json_encode($respnse);
 ?>