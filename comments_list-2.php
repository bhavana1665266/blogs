<?php
include("connection.php");

$sql = "SELECT * FROM tbl_comments2 ORDER BY id asc ";

$result = mysqli_query($connection, $sql);
$record_set = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($record_set, $row);
}
mysqli_free_result($result);

mysqli_close($connection);
echo json_encode($record_set);
?>