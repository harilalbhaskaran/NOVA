<?php
// Include config file
require_once 'db_config.php';
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter your username !';
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT * FROM nova_accounts WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $accountname = $row['fullname'];
                        $role = $row['role'];
                        $status = $row['status'];
                        $last_login = $row['last_login'];
                        $hashed_password = $row['userpassword'];
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
                            $_SESSION['account_name'] = $accountname;
                            $_SESSION['role'] = $role;
                            $_SESSION['last_login'] = $last_login;
                            header("location: dashboard.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Optional css -->
    <link rel="stylesheet" href="assets/css/nova_style.css">
    <style type="text/css">

    </style>

    <title>NOVA</title>
  </head>
 
  <body>
          <!-- Fixed navbar -->
      <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
          <a class="navbar-brand" href="dashboard.php">
                <img src="/assets/img/nova_icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
                NOVA
              </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            </ul>
            <form class="form-inline mt-2 mt-md-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input class="form-control mr-sm-2" type="text" name="username" placeholder="Username" aria-label="Username">
                <input class="form-control mr-sm-2" type="password" name="password" placeholder="Password" aria-label="Password">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="login">Login</button>
            </form>
          </div>
        </nav>

    <!-- Begin page content -->
    <div class="container">
      <div class="mt-3">
        <h1>NOVA</h1>
      </div>
        <a href="accounts.php"> User Accounts</a>
     <span id="error_message" class="text-danger"></span>  
     <span id="success_message" class="text-success"></span>
        <span class="help-block"><?php echo $username_err; ?></span>
        <span class="help-block"><?php echo $password_err; ?></span>
        <span class="help-block"><?php echo $username; ?></span>
    </div>
    <footer class="footer">
      <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
      </div>
    </footer>

   
    <!-- Optional JavaScript -->
    


    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery-3.2.1.slim.min.js"></script> 
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>