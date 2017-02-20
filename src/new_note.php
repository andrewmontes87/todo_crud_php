<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/note.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Add a new note";

confirm_logged_in(); 

$user = User::find_by_id($_SESSION["user_id"]);


if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

// prep empty form variables
$form_title = '';

if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("title");
  validate_presences($required_fields);
  
  $fields_with_max_lengths = array("title" => 30);
  validate_max_lengths($fields_with_max_lengths);

  // validate_unique_note_name($_POST["title"], $_SESSION["user_id"]);

  // update form variables if they've been posted
  // so if error, form still shows values that were just submitted
  $form_title = (isset($_POST["title"]) ? $_POST["title"] : "");

  // validate_asset_manager_required($form_asset_manager, $form_other_asset_manager);

  if (empty($errors)) {
    // Note class handles db and session messages
    Note::insert_note($_SESSION["user_id"], $_POST["title"]);
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
        <h2><i class="fa fa-plus"></i> Add a new note</h2>
        <form 
          action="new_note.php" 
          method="post"
        >
          <div class="form-group">
            <label for="title">Note title</label>
            <input 
              type="text" 
              class="form-control" 
              name="title" 
              value="<?php echo $form_title; ?>" 
              placeholder="Enter a note title"
            />
          </div>
          <div class="form-group">
            <input 
              class="btn btn-primary" 
              type="submit" 
              name="submit" 
              value="Add note" 
            />
          </div>
        </form>
        <div class="form-group">
          <a class="btn btn-warning" href="dashboard.php"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
