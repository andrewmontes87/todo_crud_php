<?php
require_once('database.php');
require_once('functions.php');
require_once('validation_functions.php');
require_once('note.php');

class User {
  

  //
  // authentication functions
  public static function password_encrypt($password) {
    $hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
    $salt_length = 22;          // Blowfish salts should be 22-characters or more
    $salt = self::generate_salt($salt_length);
    $format_and_salt = $hash_format . $salt;
    $hash = crypt($password, $format_and_salt);
    return $hash;
  }

  public static function generate_salt($length) {
    // Not 100% unique, not 100% random, but good enough for a salt
    // MD5 returns 32 characters
    $unique_random_string = md5(uniqid(mt_rand(), true));
    
    // Valid characters for a salt are [a-zA-Z0-9./]
    $base64_string = base64_encode($unique_random_string);
    
    // But not '+' which is valid in base64 encoding
    $modified_base64_string = str_replace('+', '.', $base64_string);
    
    // Truncate string to the correct length
    $salt = substr($modified_base64_string, 0, $length);
    
    return $salt;
  }

  //
  // CRUD - database handlers
  //

  // C
  // create
  public static function insert_user($username, $password) {
    global $db;
    $username = mysql_prep($username);
    $hashed_password = self::password_encrypt($password);
    $created_at = gmdate("Y-m-d\TH:i:s\Z");
    $updated_at = $created_at;
    $query  = "INSERT INTO todo_crud_php_users (";
    $query .= "  username, hashed_password,";
    $query .= "  created_at, updated_at, email";
    $query .= ") VALUES (";
    $query .= "  '{$username}', '{$hashed_password}',";
    $query .= "  '{$created_at}', '{$updated_at}', ''";
    $query .= ")";
    $result = $db->query($query);

    if ($result) {
      // Success
      $found_user = attempt_login($username, $password);

      if ($found_user) {
        $_SESSION["message"] = "User created!";
        $_SESSION["user_id"] = $found_user["id"];
        $_SESSION["username"] = $found_user["username"];
        redirect_to("update_user.php");   
      } else {
        // Failure
        $_SESSION["message"] = "User creation failed here.";     
      }
    } else {
      // Failure
      $_SESSION["message"] = "User creation failed there.";
    }
  }

  // R
  // read
  public static function find_all() {
    return self::find_by_sql("SELECT * FROM todo_crud_php_users");
  }
  
  public static function find_by_id($id=0) {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM todo_crud_php_users WHERE id={$id} LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }
  
  public static function find_by_username($username="") {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM todo_crud_php_users WHERE username='{$username}' LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }

  public static function find_by_email($email="") {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM todo_crud_php_users WHERE email='{$email}' LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }


  public static function find_by_sql($sql="") {
    global $db;
    $result_set = $db->query($sql);
    return $result_set;
  }

  // U
  // update
  public static function update_user($id=0, 
                                     $email="") {
    global $db;

    $safe_email = mysql_prep($email);

    $updated_at = gmdate("Y-m-d\TH:i:s\Z");
    $query  = "UPDATE todo_crud_php_users SET ";
    $query .= "email = '{$safe_email}', ";
    $query .= "updated_at = '{$updated_at}' ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $result = $db->query($query);
    if ($result && $db->affected_rows() == 1) {
      // Success
      $_SESSION["message"] = "User updated!";
      redirect_to("dashboard.php");
    } else {
      // Failure
      $_SESSION["message"] = "Nothing updated.";
    }
  } 

  // D
  // delete
  public static function delete_user($id=0){
    global $db;

    $query = "DELETE FROM todo_crud_php_users WHERE id = {$id} LIMIT 1";
    $result = $db->query($query);
    if ($result && $db->affected_rows() == 1) {
      // Success

      $deletions = Note::delete_all_user_notes($_SESSION["user_id"]);

      if ($deletions) {
        $_SESSION["message"] = "User deleted.";
        $_SESSION["user_id"] = null;
        $_SESSION["username"] = null;
        redirect_to("login.php");
      } else {
        redirect_to("dashboard.php");
      }

    } else {
      // Failure
      $_SESSION["message"] = "User deletion failed.";
      redirect_to("delete_user.php");
    }
  }

}

?>