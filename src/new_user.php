<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php
// Pass values to header
global $page_title;
$page_title = "New User";

// do the not_logged_in check
confirm_not_logged_in();

if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("username", "password", "repeat-password");
  validate_presences($required_fields);
  
  $fields_with_max_lengths = array("username" => 30);
  validate_max_lengths($fields_with_max_lengths);

  $fields_with_min_lengths = array("username" => 8, "password" => 8, "repeat-password" => 8);
  validate_min_lengths($fields_with_min_lengths);

  validate_unique_username($_POST["username"]);
  
  validate_password_repeated($_POST["password"], $_POST["repeat-password"]);
  
  if (empty($errors)) {
    // User class handles db and session messages
    User::insert_user($_POST["username"], $_POST["password"]);
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
        <h2><i class="fa fa-user-plus"></i> Create a new user</h2>
        <form 
          action="new_user.php" 
          method="post"
        >
          <div class="form-group">
            <label for="username">Username</label>
            <input 
              type="text" 
              name="username" 
              class="form-control"
              value="" 
            />
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input 
              type="password" 
              name="password" 
              class="form-control"
              value="" 
            />
          </div>
          <div class="form-group">
            <label for="repeat-password">Repeat password:</label>
            <input 
              type="password" 
              name="repeat-password" 
              class="form-control"
              value="" 
            />
          </div>
          
          <input 
            class="btn btn-primary" 
            type="submit" 
            name="submit" 
            value="Create user" 
          />
        </form>
        <br />
        <h3>Already have a username? Login</h3>
        <a href="login.php" class="btn btn-warning"><i class="fa fa-sign-in"></i> Login</a>  
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
