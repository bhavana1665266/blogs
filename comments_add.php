<?php
include("connection.php");
$regex="/^[a-zA-Z]+[a-zA-Z0-9\-_]*@(([a-zA-Z_\-])+\.)+[a-zA-Z]{2,4}$/";

$commentId = $_POST['comment_id'];
$comment = $_POST['comment'];
$commentMail = $_POST['mail'];
$commentSenderName = $_POST['name'];
$date = date('Y-m-d H:i:s');

if(!empty($comment) AND !empty($commentSenderName) AND !empty($commentMail) AND preg_match($regex,$commentMail)){
  $sql = "INSERT INTO tbl_comments(id_reply,comment,name,comment_date,mail) VALUES ('" . $commentId . "','" . $comment . "','" . $commentSenderName . "','" . $date . "','" . $commentMail . "')";
    $result = mysqli_query($connection, $sql);

if (! $result) {
    $result = mysqli_error($connection);
}
echo $result;
}
?>