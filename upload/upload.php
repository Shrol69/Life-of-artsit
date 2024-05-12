<?php


// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "life-of-artist";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Define target directory for uploaded artwork
$target_dir = "uploads/";

// Check if the artwork file was uploaded
if (isset($_FILES["artwork"]) && !empty($_FILES["artwork"]["name"])) {
  $artwork = $_FILES["artwork"];

  // Validate the uploaded file
  $check = getimagesize($artwork["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  // Check if file already exists (optional)
  if ($uploadOk == 1 && file_exists($target_dir . basename($artwork["name"]))) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }

  // Check file size (optional)
  if ($uploadOk == 1 && $artwork["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  // Move the uploaded file and insert data if all validations pass
  if ($uploadOk == 1) {
    if (move_uploaded_file($artwork["tmp_name"], $target_dir . basename($artwork["name"]))) {
      echo "The file " . basename($artwork["name"]) . " has been uploaded.";  // Use double quotes for string concatenation
  
      // Prepare SQL statement (sanitize user input)
      $title = mysqli_real_escape_string($conn, $_POST["title"]);
      $description = mysqli_real_escape_string($conn, $_POST["description"]);
      $sql = "INSERT INTO artworks (filename, title, description, upload_date)
                  VALUES ('" . basename($artwork["name"]) . "', '$title', '$description', NOW())";
  
      // Execute the query
      if (mysqli_query($conn, $sql)) {
        echo "Artwork information successfully added to database. 
        <script> 
        alert('YOUR artwork titled \'" . $title . "\' was added sucessfully');  // Escape single quotes within the alert message
        window.location.href='collection page copy/Collection.html'; 
        </script>";  // Add semicolon after closing script tag
      } else {
        echo "Error adding artwork details: " . mysqli_error($conn);
      }
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
  

} else {
  echo "Error: No artwork file uploaded.";
}

mysqli_close($conn);

?>
