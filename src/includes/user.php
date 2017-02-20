<?php
require_once('database.php');
require_once('functions.php');
require_once('validation_functions.php');

class User {
  
  //
  // form properties
  public static function get_investor_type_options() {
    return ["Individual investor",
            "Institutional investor",
            "Financial advisor",
            "401k administrator",
            "Asset manager",
            "Other"];
  }

  public static function get_investor_assets_options() {
    return ["0 - 10,000 USD",
            "10,000 - 100,000 USD",
            "100,000 - 1,000,000 USD",
            "1,000,000 - 1,000,000,000 USD",
            "1,000,000,000+ USD"];
  }

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
    
    $query  = "INSERT INTO fff_pap_users (";
    $query .= "  username, hashed_password";
    $query .= ") VALUES (";
    $query .= "  '{$username}', '{$hashed_password}'";
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
    return self::find_by_sql("SELECT * FROM fff_pap_users");
  }
  
  public static function find_by_id($id=0) {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM fff_pap_users WHERE id={$id} LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }
  
  public static function find_by_username($username="") {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM fff_pap_users WHERE username='{$username}' LIMIT 1");
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
                                     $investor_type="", 
                                     $investor_assets="") {
    global $db;

    $safe_investor_type = mysql_prep($investor_type);
    $safe_investor_assets = mysql_prep($investor_assets);

    $query  = "UPDATE fff_pap_users SET ";
    $query .= "investor_type = '{$safe_investor_type}', ";
    $query .= "investor_assets = '{$safe_investor_assets}' ";
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
    $query = "DELETE FROM fff_pap_users WHERE id = {$id} LIMIT 1";
    $result = $db->query($query);
    if ($result && $db->affected_rows() == 1) {
      // Success
      $_SESSION["message"] = "User deleted.";
      $_SESSION["user_id"] = null;
      $_SESSION["username"] = null;
      redirect_to("login.php");
    } else {
      // Failure
      $_SESSION["message"] = "User deletion failed.";
      redirect_to("delete_user.php");
    }
  }

}

?>