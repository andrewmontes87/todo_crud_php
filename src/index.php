<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php include("./includes/layouts/header.php"); ?>
<?php $loggedin = logged_in(); ?>
<div class="main container-wrap">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1>TODO List: PHP</h1>

        <p class="lead">A simple TODO list CRUD app built with PHP. Make an account, create notes and save them to your profile.</p>
        <?php if ($loggedin) { ?>
        <h3>You are signed in <small><?php echo $_SESSION["username"]; ?></small></h3>
        <div class="form-group">
          <a href="dashboard.php" class="btn btn-primary"><i class="fa fa-list"></i> Go to dashboard</a>
        </div>
        <div class="form-group">
          <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</a>
        </div>
        <?php } else { ?>
        <h3>New? Create a new user</h3>
        <a href="new_user.php" class="btn btn-primary"><i class="fa fa-user-plus"></i> Register a new user account</a>
        <h3>Already have a username? Login</h3>
        <a href="login.php" class="btn btn-warning"><i class="fa fa-sign-in"></i> Login</a>
        <?php } ?>
        <h4>Features:</h4>
        <ul>
          <li>Account creation + login/logout</li>
          <li>Update or delete your account</li>
          <li>Create, update, delete notes</li>
        </ul>
        <h4>Stack:</h4>
        <ul>
          <li><a href="https://secure.php.net/" title="php" target="_blank">PHP</a></li>
          <li><a href="https://www.mysql.com/" title="MySQL" target="_blank">MySQL</a></li>
        </ul>
      </div>
    </div>
  </div>  
</div>

<?php include("./includes/layouts/footer.php"); ?>
