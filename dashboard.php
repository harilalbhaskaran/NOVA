<?php
// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: index.php");
  exit;
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

    <title>NOVA Dashboard</title>
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
              <li class="nav-item active">
                  <a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="scripts.php">Scripts</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="database.php">Database</a>
              </li>
              <li class="nav-item dropdown">
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
        <h1>NOVA Dashboard</h1>
      </div>
        <p><?php echo $_SESSION['account_name']; ?></p>
        <a href="accounts.php"> User Accounts</a>
    </div>

    <footer class="footer">
      <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
      </div>
    </footer>

   
    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="assets/js/popper.min.js""></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>