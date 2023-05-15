<?php
// Put all of your general functions in this file

// header redirection often requires output buffering 
// to be turned on in php.ini.
function redirect_to($new_location) {
  header("Location: " . $new_location);
  exit;
}

function containsOnlyLetters($str) {
  $regex = '/^[a-zA-Z]{3,}(?:\s*[a-zA-Z]*)$/';
  return preg_match($regex, $str);
}

function isNumber($input) {
  // Remove leading and trailing whitespace
  $input = trim($input);

  // Remove the "+" character
  $input = $input[0] === "+" ? substr($input, 1) : $input;

  // Check if the remaining input consists of 10-12 digits
  $regex = '/^\d{10,12}$/';
  return preg_match($regex, $input);
}


function log_action($action = "", $message = "")
{
    $file = PRIVATE_PATH . DS . "logs" . DS . "log.txt";

    if (!is_writable($file)) {
        $errorMessage = "Please make sure the file is writable: " . $file;
        error_log($errorMessage);
        return;
    }

    $timestamp = date("m/d/Y H:i:s");
    $logContent = $timestamp . " : " . $action . " | " . $message . "\n\n\n";

    file_put_contents($file, $logContent, FILE_APPEND | LOCK_EX);
}



// * validate value has presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	$trimmed_value = trim($value);
	
  return isset($trimmed_value) && $trimmed_value !== "";
}

// * validate value has string length
// leading and trailing spaces will count
// options: exact, max, min
// has_length($first_name, ['exact' => 20])
// has_length($first_name, ['min' => 5, 'max' => 100])
function has_length($value, $options=[]) {
	if(isset($options['max']) && (strlen($_POST[$value]) > (int)$options['max'])) {
		echo ucfirst($value)." must be atleast 8 characters.";
		return false;
	}
	if(isset($options['min']) && (strlen($_POST[$value]) < (int)$options['min'])) {
	    echo ucfirst($value)." must be atleast 8 characters.";
		return false;
	}
	if(isset($options['exact']) && (strlen($_POST[$value]) != (int)$options['exact'])) {
		return false;
	}
	return true;
}

// * validate value has a format matching a regular expression
// Be sure to use anchor expressions to match start and end of string.
// (Use \A and \Z, not ^ and $ which allow line returns.) 
// 
// Example:
// has_format_matching('1234', '/\d{4}/') is true
// has_format_matching('12345', '/\d{4}/') is also true
// has_format_matching('12345', '/\A\d{4}\Z/') is false
function has_format_matching($value, $regex='//') {
	return preg_match($regex, $value);
}

// * validate value is a number
// submitted values are strings, so use is_numeric instead of is_int
// options: max, min
// has_number($items_to_order, ['min' => 1, 'max' => 5])
function has_number($value, $options=[]) {
	if(!is_numeric($value)) {
		return false;
	}
	if(isset($options['max']) && ($value > (int)$options['max'])) {
		return false;
	}
	if(isset($options['min']) && ($value < (int)$options['min'])) {
		return false;
	}
	return true;
}


// * validate value is inclused in a set
function has_inclusion_in($value, $set=[]) {
  return in_array($value, $set);
}

// * validate value is excluded from a set
function has_exclusion_from($value, $set=[]) {
  return !in_array($value, $set);
}


function is_phone($number = 0,$startsWithZero = true){
 
  
 if(isset($number) && !empty(trim($number))){

  
 return $startsWithZero ?  preg_match('/^[0-9]{9}+$/',$number): preg_match('/^[0-9]{10}+$/',$number);

 
 }
}


// redirect to the login page
function log_in_page(){

header("Location: ".PUBLIC_PATH."/login.php");
exit;
}

// display a page not found
function page_not_found(){
header("HTTP/1.1 404 Not Found");
exit;
}


function upload_time($time = 0){


$time_seconds = ceil(time() - $time);

$time_minutes = ceil($time_seconds / 60);
$time_hours   = ceil($time_minutes / 60);
$time_days    = ceil($time_hours   / 24);
$time_weeks   = ceil($time_days   / 7 );
// handles all the time less than or equal 
// to a minute.
// if($time_seconds <= 60){
   
//    if($time_seconds == 60){

//   return "a minute ago";
//    }
//   return "moments ago";

// // handles all the time less or equal to 
// // an hour   
// }elseif($time_minutes <= 60){
     
//       if($time_minutes == 60){
//       return "1hr ago";
//       }
//   return $time_minutes." mins ago";

// }elseif($time_hours <= 24){
  
//    if($time_hours == 24){
   
//      return "1day ago";
//    }

//    return $time_hours."hrs ago";
// }elseif($time_days <= 7){

//   if($time_days == 1 ){
//       return "since yesterday";
//     }
//       return $time_days." days ago";
    
// }elseif($time_weeks <= 4 ){

//   if($time_weeks == 1){

//   return "a week ago";
//   }
//     return $time_weeks." weeks ago";
// }elseif($time_){
//   return "out of range";
// }

return strftime("%a, %dth %b %g");

//    else{
//   $time_minutes = $time_minutes / 60;
//     return "about ".ceil($time_minutes)." munites ago...";
  
//   }
    
//     if($time_hours == 1){

// return "about an hour ago";
//     }
//     if($time_hours > 1 && $time_hours <= 4){

// return "some couple of hours ago ({$time_hours})";
//     }else{

//       return "about {$time_hours} ago."; 
//     }

//   }

}



   


// * validate uniqueness
// A common validation, but not an easy one to write generically.
// Requires going to the database to check if value is already present.
// Implementation depends on your database set-up.
// Instead, here is a mock-up of the concept.
// Be sure to escape the user-provided value before sending it to the database.
// Table and column will be provided by us and escaping them is optional.
// Also consider whether you want to trim whitespace, or make the query 
// case-sentitive or not.
//
// function has_uniqueness($value, $table, $column) {
//   $escaped_value = mysql_escape($value);
//   sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = '{$escaped_value}';"
//   if count > 0 then value is already present and not unique
// }


/**
****************
* FILE UPLOADS *             
****************

**/


      
?>
