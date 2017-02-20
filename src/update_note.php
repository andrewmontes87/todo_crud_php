<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/note.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Update Note";

confirm_logged_in();

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

// if it's a get with an id paramater, 
// we came from new note or edit note buttons

// use the id to find the note
if (isset($_GET["id"])) {
  $note = Note::find_by_id_and_user_id($_GET["id"], $_SESSION["user_id"]);
 
  // get title for form and page title
  $page_title .= ": " . $note["title"];

  // update form if note already has values
  $form_id =       (isset($note["id"])      ? $note["id"]      : null);
  $form_title =    (isset($note["title"])   ? $note["title"]   : null);
  $form_type =     (isset($note["type"])    ? $note["type"]    : null);
  $form_content =  (isset($note["content"]) ? $note["content"] : null);

  if (!$note) {
    // note ID was missing or invalid or 
    // note couldn't be found in database
    redirect_to("dashboard.php");
  }
}

if (isset($_POST['submit'])) {
  // validations
  $required_fields = array("title", "type");
  validate_presences($required_fields);
  
  $fields_with_max_lengths = array("content" => 255);
  validate_max_lengths($fields_with_max_lengths);

  // update form if $_POST already has values
  // so user input is still shown in case of errors
  $form_id =       (isset($_POST["id"])      ? $_POST["id"]      : null);
  $form_title =    (isset($_POST["title"])   ? $_POST["title"]   : null);
  $form_type =     (isset($_POST["type"])    ? $_POST["type"]    : null);
  $form_content =  (isset($_POST["content"]) ? $_POST["content"] : null);

  // use id to look the character up in the database
  $note = Note::find_by_id_and_user_id($_SESSION["user_id"], $form_id);

  // keep page_title reading from db instead of POST
  $page_title .= ": " . $note["title"];

  if (empty($errors)) {
    // Perform Update
    Note::update_note($form_id, 
                      $_SESSION["user_id"], 
                      $form_title,
                      $form_type, 
                      $form_content);
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
        <h2><i class="fa fa-pencil"></i> Update note</h2>
        <form 
          action="update_note.php" 
          method="post"
        >
          <div class="form-group">
            <label for="title">Note title:</label>
            <input 
              type="text" 
              name="title"
              placeholder="Enter a note title"
              class="form-control"
              required 
              value="<?php echo $form_title; ?>" 
            />
          </div>
          <div class="form-group">
            <p><strong>Date created: </strong> <?php echo date('Y-m-d g:i:s', strtotime($note['created_at'])) ; ?> </p>
          </div>
          <div class="form-group">
            <p><strong>Last updated: </strong> <?php echo date('Y-m-d g:i:s', strtotime($note['updated_at'])) ; ?> </p>
          </div>
          <div class="form-group">
            <label for="type">Note type:</label>
            <select 
              name="type" 
              class="form-control" 
              id="note-type-select"
            >
              <option 
                disabled 
                selected
                required>
              Select a note type</option>
              <?php foreach(Note::get_note_type_options() as $option) { ?>
                <option 
                  value="<?php echo $option; ?>" 
                  <?php if ($form_type == $option) echo "selected"; ?> 
                ><?php echo $option; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="content">Note content</label>
            <textarea 
              class="form-control" 
              name="content" 
              rows="3"><?php echo $form_content; ?></textarea>
          </div>
          <input 
            type="hidden"
            name="id" 
            value="<?php echo $form_id; ?>" 
          />
          <div class="form-group">
            <input 
              class="btn btn-primary" 
              type="submit" 
              name="submit" 
              value="Save"
            />
          </div>
          
        </form>
        <div class="form-group">
          <a href="dashboard.php" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Dashboard</a>
        </div>
        <div class="form-group">
          <a href="delete_note.php?id=<?php echo $form_id; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete note</a> 
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
