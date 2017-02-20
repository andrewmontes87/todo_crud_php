<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/note.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Delete Note";

confirm_logged_in();

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

$form_title = '';

// if it's a get request with an id,
// we came from update page, not POST
if (isset($_GET["id"])) {
  // use the id to find the note
  $form_id = $_GET["id"];
  $note = Note::find_by_id_and_user_id($form_id, $_SESSION["user_id"]);
  // grab title for the form and title
  $form_title = (isset($note["title"]) ? $note["title"] : null);
  $page_title .= ": " . $form_title;

  if (!$note) {
    // note ID was missing or invalid or 
    // note couldn't be found in database
    redirect_to("dashboard.php");
  }
}

if (isset($_POST['submit'])) {
  // get the note by post id
  $note = Note::find_by_id_and_user_id($_POST["id"], $_SESSION["user_id"]);

  // grab title for the form and title in case error posting
  $form_id =   (isset($note["id"])   ? $note["id"]   : null);
  $form_title = (isset($note["title"]) ? $note["title"] : null);
  $page_title .= ": " . $form_title;

  // validations
  $required_fields = array("delete");
  validate_presences($required_fields);

  if (empty($errors)) {
    // Note class handles db and session messages
    Note::delete_note($_POST["id"], $_SESSION["user_id"]);
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
        <h2><i class="fa fa-trash-o"></i> Delete note</h2>
        <p>Are you sure you want to delete this note?</p>
        <p>User: <strong><?php echo htmlentities($_SESSION["username"]); ?></strong></p>
        <p>Note title: <strong><?php echo htmlentities($form_title); ?></strong></p>
        <form 
          action="delete_note.php" 
          method="post" 
          class="login-form"
        >
          <input 
            type="hidden" 
            name="delete"
            value="true" 
          />
          <input 
            type="hidden" 
            name="name"
            value="<?php echo $form_title; ?>"  
          />
          <input 
            type="hidden" 
            name="id"
            value="<?php echo $form_id; ?>" 
          />
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
          <a href="update_note.php?id=<?php echo $form_id;?>" class="btn btn-primary"><i class="fa fa-ban"></i> Cancel - don't delete this note</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
