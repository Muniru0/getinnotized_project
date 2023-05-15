<?php


// NOTE: For simplicity, we are just copying the code from the add contact
// file.


 // require the site initialization script
 require_once('../private/initialize.php');
   
 // for security reasons we need to perform mandatory checks
 before_every_protected_page();

 // declare and initialize a variable for errors
 $err_msgs_array = [];


 // perform basic security checks. 
 if(!request_is_post() || !request_is_same_domain() || 
    !csrf_token_is_valid() || !csrf_token_is_recent()) {
	
    $err_msgs_array [] =  "Something unexpectedly happended, Please refresh the page.";
  }else{

        
        if(isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["phone_number"]) && isset($_POST["id"])){

            

            if(containsOnlyLetters($_POST["firstname"]) && containsOnlyLetters($_POST["lastname"]) && is_phone(intval($_POST["phone_number"])) && intval($_POST["id"]) > 0){
             
               

                $result = PhoneBook::update(trim($_POST["firstname"]), trim($_POST["lastname"]),trim($_POST["phone_number"]),intval($_POST["id"]) );

                if(gettype($result) === "string"){
                    $err_msgs_array [] = $result;
                }else{
                  print(json_encode([$result]));
                }

            }else{
                $err_msgs_array [] = "Sorry some of the data were invalid.";
            }
        
        }else{
            $err_msgs_array [] = "Please fill in all the fields";
        }

  }


  
 
  if(!empty($err_msgs_array)){
    print(json_encode($err_msgs_array));

  }





?>