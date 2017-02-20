<?php require_once("./includes/functions.php"); ?>
<?php $loggedin = logged_in(); ?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./">todo_crud_php</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav navbar-right">
        <p class="navbar-text">
          <?php if ($loggedin) {
            echo "Signed in as <strong>" . $_SESSION["username"] . "</strong>";
          } else {
            echo "Sign in or create an account to get started";
          } ?>
        </p>
        <?php if ($loggedin) { ?>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="update_user.php"><i class="fa fa-user"></i> Update your profile</a></li>
          <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
        <?php } else { ?>
          <li><a href="new_user.php"><i class="fa fa-user-plus"></i> Register a new user account</a></li>
          <li><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
        <?php } ?>        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>      
