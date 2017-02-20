<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php include("./includes/layouts/header.php"); ?>
<?php $loggedin = logged_in(); ?>
<div class="main container-wrap">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h2>Home</h2>
        <p>A simple CRUD app built with PHP. Make an account, then create a portfolio and save it to your profile.</p>
        <p>Features so far:</p>
        <ul>
          <li>Account creation</li>
          <li>Login and logout</li>
          <li>Update your profile</li>
          <li>Delete your account</li>
          <li>Portfolio creation</li>
          <li>Update investment profiles</li>
          <li>Portfolio deletion</li>
        </ul>
        <?php if ($loggedin) { ?>
        <h3>You are signed in <small><?php echo $_SESSION["username"]; ?></small></h3>
        <a href="dashboard.php" class="btn btn-primary"><i class="fa fa-list"></i> Go to dashboard</a>
        <br/>
        <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</a>
        <?php } else { ?>
        <h3>New? Create a new user</h3>
        <a href="new_user.php" class="btn btn-primary"><i class="fa fa-user-plus"></i> Register a new user account</a>
        <h3>Already have a username? Login</h3>
        <a href="login.php" class="btn btn-warning"><i class="fa fa-sign-in"></i> Login</a>
        <?php } ?>
      </div>
    </div>
  </div>  
</div>

<?php include("./includes/layouts/footer.php"); ?>
