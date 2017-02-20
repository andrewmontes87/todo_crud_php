<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/note.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Dashboard";

confirm_logged_in(); 

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

$note_set = Note::find_by_user_id($_SESSION["user_id"]);

?>
<?php include("./includes/layouts/header.php"); ?>

<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <h1>Dashboard</h1>
        <h3><i class="fa fa-list"></i> Your notes</h3>
        <a href="new_note.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add a new note</a>
        <ul class="list-group"  id="dashboard-list">
        <?php if ($db->num_rows($note_set)) { ?>
          <?php while($note = $db->fetch_assoc($note_set)) { ?>
            <li class="list-group-item">
              <h4><?php echo htmlentities($note["title"]); ?></h4> 
              <p><?php echo ucfirst(htmlentities($note["type"])); ?></p>
              <blockquote><?php echo htmlentities($note["content"]); ?></blockquote>
              <a href="update_note.php?id=<?php echo $note["id"]; ?>"><i class="fa fa-pencil"></i> Edit</a>
            </li>
          <?php } ?>
        <?php } else { ?>
          <li class="list-group-item">You don't have any notes yet, get started adding notes!</li>
        <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?> 
