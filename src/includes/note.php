<?php
require_once('database.php');
require_once('functions.php');
require_once('validation_functions.php');

class Note {

  // form properties
  public static function get_note_type_options() {
    return ["TODO",
            "Reminder",
            "Misc"];
  }

  //
  // CRUD - database handlers
  //

  // C
  // create
  public static function insert_note($user_id=0, $title="") {
    global $db;

    $safe_title = mysql_prep($title);

    $created_at = gmdate("Y-m-d\TH:i:s\Z");
    $updated_at = $created_at;

    $query  = "INSERT INTO todo_crud_php_notes (";
    $query .= "  user_id, created_at, updated_at, title, type, content";
    $query .= ") VALUES (";
    $query .= "  {$user_id}, '{$created_at}', '{$updated_at}', '{$safe_title}', '', ''";
    $query .= ")";
    $result = $db->query($query);

    if ($result) {
      // Success
      $id = $db->last_inserted_id();
      $found_inserted_note = self::find_by_id_and_user_id($id, $_SESSION["user_id"]);

      if ($found_inserted_note) {
        $_SESSION["message"] = "Note added!";
        $output_url  = "update_note.php?id=";
        $output_url .= urlencode($id);
        redirect_to($output_url);         
      } else {
        // Failure
        $_SESSION["message"] = "Failed to find the new note.";
      }
    } else {
      // Failure
      $_SESSION["message"] = "Failed to add the note.";
    }
  }


  // R
  // read
  public static function find_all() {
    return self::find_by_sql("SELECT * FROM todo_crud_php_notes");
  }
  
  public static function find_by_id_and_user_id($id=0, $user_id=0) {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM todo_crud_php_notes WHERE id={$id} AND user_id={$user_id} LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }
  
  public static function find_by_title_and_user_id($title="", $user_id=0) {
    global $db;
    $safe_title = $db->escape_value($title);
    $result_set = self::find_by_sql("SELECT * FROM todo_crud_php_notes WHERE title='{$safe_title}' AND user_id={$user_id} LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }

  public static function find_by_user_id($user_id=0) {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM todo_crud_php_notes WHERE user_id={$user_id}");
    return $result_set;
  }

  public static function find_by_sql($sql="") {
    global $db;
    $result_set = $db->query($sql);
    return $result_set;
  }

  // U
  // update
  public static function update_note($id=0, 
                                     $user_id=0,
                                     $title="",
                                     $note_type="",
                                     $content="") {
    global $db;

    $safe_title = mysql_prep($title);
    $safe_type = mysql_prep($note_type);
    $safe_content = mysql_prep($content);
    $updated_at = gmdate("Y-m-d\TH:i:s\Z");

    $query  = "UPDATE todo_crud_php_notes SET ";
    $query .= "title = '{$safe_title}', ";
    $query .= "type = '{$safe_type}', ";
    $query .= "content = '{$safe_content}', ";
    $query .= "updated_at = '{$updated_at}' ";
    $query .= "WHERE id = {$id} ";
    $query .= "AND user_id = {$user_id} ";
    $query .= "LIMIT 1";
    $result = $db->query($query);

    if ($result && $db->affected_rows() == 1) {
      // Success
      $_SESSION["message"] = "Note updated!";
      // redirect_to("dashboard.php");
    } else {
      // Failure
      $_SESSION["message"] = "Nothing updated.";
    }

  }


  // D
  // delete
  public static function delete_note($id, $user_id){
    global $db;
    $query = "DELETE FROM todo_crud_php_notes WHERE id = {$id} AND user_id = {$user_id} LIMIT 1";
    $result = $db->query($query);
    if ($result && $db->affected_rows() == 1) {
      // Success
      $_SESSION["message"] = "Note deleted.";
      redirect_to("dashboard.php");
    } else {
      // Failure
      $_SESSION["message"] = "Note deletion failed.";
      $output_url  = "update_note.php?id=";
      $output_url .= $id;
      redirect_to($output_url);
    }
  }

  public static function delete_all_user_notes($user_id){
    global $db;
    $query = "DELETE FROM todo_crud_php_notes WHERE user_id = {$user_id}";
    $result = $db->query($query);
    if ($result) {
      // Success
      $_SESSION["message"] = "Notes deleted.";
      return True;
    } else {
      // Failure
      $_SESSION["message"] = "Note deletion failed.";
      return False;
    }
  }





}