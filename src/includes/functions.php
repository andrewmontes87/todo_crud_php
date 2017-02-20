<?php
require_once("database.php");
require_once("user.php");

  function redirect_to( $location = NULL ) {
    if ($location != NULL) {
      header("Location: {$location}");
      exit;
    }
  }


  function attempt_login($username, $password) {
    $user = User::find_by_username($username);
    if ($user) {
      // found user, now check password
      if (password_check($password, $user["hashed_password"])) {
        // password matches
        return $user;
      } else {
        // password does not match
        return false;
      }
    } else {
      // user not found
      return false;
    }
  }


  function password_check($password, $existing_hash) {
    // existing hash contains format and salt at start
    $hash = crypt($password, $existing_hash);
    if ($hash === $existing_hash) {
      return true;
    } else {
      return false;
    }
  }

  function form_errors($errors=array()) {
    $output = "";
    if (!empty($errors)) {
      $output .= "<div class=\"alert alert-danger error\">";
      $output .= "Please fix the following errors:";
      $output .= "<ul>";
      foreach ($errors as $key => $error) {
        $output .= "<li>";
        $output .= htmlentities($error);
        $output .= "</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
  }

  function logged_in() {
    return isset($_SESSION['user_id']);
  }
  
  function confirm_logged_in() {
    if (!logged_in()) {
      redirect_to("login.php");
    }
  }

  function confirm_not_logged_in() {
    if (logged_in()) {
      $_SESSION["message"] = "You must be logged out to do that.";     
      redirect_to("logout.php");
    }
  }

  function mysql_prep($string) {
    global $db;
    return $db->escape_value($string);
  }

  function strip_zeros_from_date( $marked_string="" ) {
    // first remove the marked zeros
    $no_zeros = str_replace('*0', '', $marked_string);
    // then remove any remaining marks
    $cleaned_string = str_replace('*', '', $no_zeros);
    return $cleaned_string;
  }

  function output_message($message="") {
    if (!empty($message)) { 
      return "<p class=\"message\">{$message}</p>";
    } else {
      return "";
    }
  }



?>