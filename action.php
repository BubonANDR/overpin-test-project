<?php



$connect = new PDO("mysql:host=localhost;dbname=pic_comments_db", "sqluser", "password");

$received_data = json_decode(file_get_contents("php://input"));
$data = array();
if($received_data->action == 'fetchall')
{
 $query = "
 SELECT * FROM comments
 ORDER BY comment_date DESC
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row;
 }
 echo json_encode($data);
}
if($received_data->action == 'insert')
{
 $data = array(
  ':user_name' => $received_data->userName,
  ':user_comment' => $received_data->userComment
 );

 $query = "
 
 INSERT INTO comments
 (id,user_name, user_comment,comment_date,pic_id) 
 VALUES (NULL,:user_name, :user_comment,CURRENT_TIMESTAMP,'pic-1')
 ";

 $statement = $connect->prepare($query);

 $statement->execute($data);

 $output = array(
  'message' => 'Комментарий добавлен'
 );

 echo json_encode($output);
}



if($received_data->action == 'delete')
{
 $query = "
 DELETE FROM comments
 WHERE id = '".$received_data->id."'
 ";

 $statement = $connect->prepare($query);

 $statement->execute();

 $output = array(
  'message' => 'Комментарий удален!'
 );

 echo json_encode($output);
}

?>