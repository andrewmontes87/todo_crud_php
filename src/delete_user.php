<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/user.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Delete User";

confirm_logged_in();

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

if (isset($_POST['submit'])) {

  // validations
  $required_fields = array("delete");
  validate_presences($required_fields);

  if (empty($errors)) {
    // User class handles db and session messages
    User::delete_user($_SESSION["user_id"]);
  } 
} else {
  // This is probably a GET request
  
}
?>
<?php include("./includes/layouts/header.php"); ?>
<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <h2><i class="fa fa-trash-o"></i> Delete user account</h2>
        <p>Are you sure you want to delete your account?
          <p>Username: <strong><?php echo htmlentities($_SESSION["username"]); ?></strong></p>
        <form 
          action="delete_user.php" 
          method="post" 
          class="login-form"
        >
          <input 
            type="hidden" 
            value="true" 
            name="delete"
          >
          <div class="form-group">
            <input 
             class="btn btn-danger" 
             type="submit" 
             name="submit" 
             value="Delete" 
            />
          </div>
        </form>
        <div class="form-group">
          <a href="update_user.php" class="btn btn-primary"><i class="fa fa-ban"></i> Cancel - don't delete my account</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
