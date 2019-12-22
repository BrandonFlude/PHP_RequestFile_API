<?php
  $requestedFile = $_POST['file_name'];
  if(!empty($requestedFile)) {
    // START CONNECT
    $mysqli = new mysqli("DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME");
    if($mysqli->connect_error) {
      exit('Error connecting to database');
    }
    $mysqli->set_charset("utf8mb4");
    // END CONNECT
  
    // Build the query, in this case joining FILE table to the MIMES table to get Content Type data to display correctly
    $stmt = $mysqli->prepare("SELECT a.file_location, b.mime_type FROM files AS a JOIN mimes AS b on a.file_extension = b.file_extension WHERE file_name = ? LIMIT 1");
    $stmt->bind_param("s", $requestedFile);
    $stmt->execute();
  
    $stmt->store_result();
    if($stmt->num_rows === 0) {
      exit('File not found.');
    } else {
      $stmt->bind_result($fileLocation, $mimeType);
      $stmt->fetch();
      
      // Set the headers correctly to display the file in browser
      header("Content-type: $mimeType");
      header("Content-Disposition: inline; filename=$fileLocation");
      @readfile("$fileLocation");
    }
    $stmt->close();
  } else {
    exit('No file name provided.');
  }
?>
