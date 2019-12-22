<?php

  $fileExtension = $_POST['file_extension'];

  // START CONNECT
  $mysqli = new mysqli("DB_HOST", "DB_USER", "DB_PASSWORD", "personal");
  if($mysqli->connect_error) {
    exit('Error connecting to database');
  }
  $mysqli->set_charset("utf8mb4");
  // END CONNECT

  $arr = [];
  // Change the query based on whether there was a POST request with a file extension or not
  if(!empty($fileExtension)) {
    $stmt = $mysqli->prepare("SELECT * FROM files WHERE file_extension = ?");
    $stmt->bind_param("s", $fileExtension);
  } else {
    $stmt = $mysqli->prepare("SELECT * FROM files");
  }

  // Display the data in JSON format or show a message to the user
  $stmt->execute();
  $result = $stmt->get_result();
  while($row = $result->fetch_row()) {
    $arr[] = $row;
  }
  if(!$arr) {
    exit("No files match the file extension $fileExtension");
  }
  var_export($arr);
  $stmt->close();
?>
