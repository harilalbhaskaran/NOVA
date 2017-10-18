<?php
// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: index.php");
  exit;
}
?>
<?php
// Include config file
require_once 'db_config.php';
// Define variables and initialize with empty values
$name = $username = $password = $confirm_password = $role = "";
$user_status=0;
$acc_name_err = $username_err = $password_err = $confirm_password_err = $role_err = $state_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(empty(trim($_POST["accountname"]))){
        $acc_name_err = "Please enter a account name.";
    } else {
        $accountname = trim($_POST['accountname']);     
    }
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM nova_accounts WHERE username = :username";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else {
                  $username = trim($_POST["username"]);  
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        unset($stmt);
    }
        // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }
    
        // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $user_status = trim($_POST['state']);
        $role = trim($_POST['role']) ;
        $passwordhash = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        $sql = "INSERT INTO nova_accounts (fullname, username, userpassword, role, status, last_login) VALUES ('$accountname','$username','$passwordhash','$role','$user_status','No login Attempt')";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters

            if($stmt->execute()){
                // Redirect to login page
                header("location: index.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

        }

    }
 else {
       echo "Something went wrong. Please try again later.";
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
              <li class="nav-item">
                  <a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="scripts.php">Scripts</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="database.php">Database</a>
              </li>
              <li class="nav-item dropdown active">
                  <a class="nav-link dropdown-toggle" href="settings.php" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Settings
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="settings.php">System Settings</a>
                  <a class="dropdown-item" href="accounts.php">User Accounts</a>
                  <a class="dropdown-item" href="sysinfo.php">System Information</a>
                  <a class="dropdown-item" href="#">About</a>
                </div>
              </li>
            </ul>
                <span class="navbar-text">
                    Welcome&nbsp;<?php echo $_SESSION['account_name']; ?>&nbsp;
                </span>
              <form class="form-inline mt-2 mt-md-0" action="logout.php" method="post">
                <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" name="logout">Logout</button>
              </form>
          </div>
        </nav>


    <!-- Begin page content -->
    <div class="container">
      <div class="mt-3">
        <h1>Account Registration</h1>
        <span class="help-block"><?php echo $acc_name_err; ?></span>
        <span class="help-block"><?php echo $username_err; ?></span>
        <span class="help-block"><?php echo $password_err; ?></span>
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
      </div>
        <hr><p></p>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group row">
      <label for="inputName" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-6">
          <input type="text" name="accountname" class="form-control" placeholder="Account Name">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputUserID" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-6">
        <input type="text" name="username" class="form-control" placeholder="Username">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputPassword1" class="col-sm-2 col-form-label">Password</label>
      <div class="col-sm-6">
        <input type="password" name="password" class="form-control" placeholder="Password">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputPassword2" class="col-sm-2 col-form-label">Confirm Password</label>
      <div class="col-sm-6">
        <input type="password" name="confirm_password" class="form-control" placeholder="Password">
      </div>
    </div>
    <div class="form-group row">
      <label for="inputRole" class="col-sm-2 col-form-label">Role</label>
      <div class="col-sm-6">
          <select name="role" class="form-control">
              <option value="Administrator">Administrator</option>
              <option value="Config Manager">Config Manager</option>
              <option value="NOC Engineer">NOC Engineer</option>
              <option value="Read only"selected>Read only</option>
          </select>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-2">Account Status</div>
      <div class="col-sm-6">
        <div class="form-check">
          <label class="form-check-label">
              <input type="checkbox" name="state" class="form-check-input"> Enable
          </label>
        </div>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-6">
        <button type="submit" class="btn btn-primary">Register</button>
        <button type="reset" class="btn btn-primary">Reset</button>
      </div>
    </div>
  </form>
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