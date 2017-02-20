<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php
// Pass values to header
global $page_title;
$page_title = "Login";

confirm_not_logged_in();

$username = "";

if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("username", "password");
  validate_presences($required_fields);
  
  if (empty($errors)) {
    // Attempt Login

    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $found_user = attempt_login($username, $password);

    if ($found_user) {
      // Success
      // Mark user as logged in
      $_SESSION["user_id"] = $found_user["id"];
      $_SESSION["username"] = $found_user["username"];
      redirect_to("dashboard.php");
    } else {
      // Failure
      $_SESSION["message"] = "Username/password not found.";
    }
  }
} else {
  // This is probably a GET request
  
} // end: if (isset($_POST['submit']))

?>
<?php include("./includes/layouts/header.php"); ?>
<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <?php echo form_errors($errors); ?>
        <h2><i class="fa fa-sign-in"></i> Login</h2>
        <form 
          action="login.php" 
          method="post" 
          class="login-form"
        >
          <div class="form-group">
            <label for="username">Username</label>
            <input 
              type="text" 
              name="username" 
              value="<?php echo htmlentities($username); ?>" 
            />
            <p><a href="">Forgot your username?</a></p>  
            <br/>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input 
              type="password" 
              name="password" 
              value="" 
            />
            <p><a href="">Forgot your password?</a></p>  
            <br/>
          </div>
          <input 
            class="btn btn-primary" 
            type="submit" 
            name="submit" 
            value="Submit" 
          />
        </form>
        <h3>New? Create a new user</h3>
        <a href="new_user.php" class="btn btn-warning"><i class="fa fa-user-plus"></i> Create new user</a>

      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
