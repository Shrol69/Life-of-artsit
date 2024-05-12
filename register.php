<?php
include "connect.php";

$username = '';
$email = '';
$firstName = '';
$lastName = '';
$password = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check for POST method (more secure)
  if (isset($_POST['btn_register'])) {
    $username = trim($_POST['inp_username']); // Trim whitespaces
    $email = trim($_POST['inp_email']); // New - Trim email
    $firstName = trim($_POST['inp_first_name']); // New - Trim first name
    $lastName = trim($_POST['inp_last_name']); // New - Trim last name (optional)
    $password = trim($_POST['inp_password']);

    // Improved validation with more informative error messages
    if (empty($username)) {
      $errorMsg = "Please enter a username.";
    } else if (empty($email)) {
      $errorMsg = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Basic email validation
      $errorMsg = "Please enter a valid email address.";
    } else if (empty($password)) {
      $errorMsg = "Please enter a password.";
    } else {
      // Check if username already exists with Prepared Statement (secure)
      $sql = "SELECT * FROM users WHERE username=?";
      $stmt = mysqli_prepare($con, $sql);

      if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
          $errorMsg = "Username already exists. Please choose a different username.";
        } else {
          mysqli_stmt_close($stmt); // Close prepared statement

          // Hash password before storing (recommended)
          $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

          // Insert new user into database with Prepared Statement (secure)
          $sql = "INSERT INTO users (username, email, first_name, last_name, password) VALUES (?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($con, $sql);

          if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $firstName, $lastName, $password);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) === 1) {
              // Registration successful, redirect using header (more secure)
              header("Location: login.php?success=true");
              exit(); // Prevent further code execution after redirect
            } else {
              $errorMsg = "Registration failed. Please try again.";
              // For debugging purposes, consider adding:
              // $errorMsg .= "<br>" . mysqli_error($con);
            }
            mysqli_stmt_close($stmt); // Close prepared statement
          } else {
            $errorMsg = "Error preparing statement: " . mysqli_error($con);
          }
        }
      } else {
        $errorMsg = "Error preparing statement: " . mysqli_error($con);
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_2.css">
  <title>Life Of Artists - Registration</title>
  
</head>
<body>
  <div class="container">
    <div class="info-section">
      <h1>Unleash Your Creativity. Join Our Thriving Art Community.</h1>
      <p>Let's create the canvas of our creative minds.</p>
      <p>
        Welcome to Life of Artists, a platform where artists of all levels can connect, create, and inspire. Register now to showcase your work, discover new talent, and be part of a vibrant artistic community.
      </p>
      <div class="social-media-icons">  <img src="Images/instagram.png" alt="Instagram" />
        <img src="Images/facebook.png" alt="Facebook" />
        <img src="Images/twitter.png" alt="Twitter" />
        <img src="Images/discord.png" alt="Discord" />
        <img src="Images/youtube.png" alt="YouTube" />
      </div>
    </div>
    <div class="form-container">
      <center>
        <h1>REGISTER</h1>
      </center>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <?php if (!empty($errorMsg)) { ?>  <div class="error-message">
          <?php echo $errorMsg; ?>
        </div>
        <?php } ?>
        <div class="input-group">
          <label for="inp_username">Username</label>
          <input type="text" name="inp_username" id="inp_username" placeholder="Enter Username" value="<?php echo $username; ?>">
        </div>
        <div class="input-group">
          <label for="inp_email">Email Address</label>
          <input type="email" name="inp_email" id="inp_email" placeholder="Enter Email Address" value="<?php echo $email; ?>">
        </div>
        <div class="input-group">
          <label for="inp_first_name">First Name</label>
          <input type="text" name="inp_first_name" id="inp_first_name" placeholder="Enter First Name" value="<?php echo $firstName; ?>">
        </div>
        <div class="input-group">
          <label for="inp_last_name">Last Name (Optional)</label>
          <input type="text" name="inp_last_name" id="inp_last_name" placeholder="Enter Last Name" value="<?php echo $lastName; ?>">
        </div>
        <div class="input-group">
          <label for="inp_password">Password</label>
          <input type="password" name="inp_password" id="inp_password" placeholder="Enter Password">
        </div>
        <div class="input-group">
          <input type="submit" name="btn_register" value="Register">
        </div>
        <p>
          Already have an account? <a href="login.php">Login</a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>

