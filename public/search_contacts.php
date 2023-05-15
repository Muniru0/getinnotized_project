<?php
   // require the site initialization script
   require_once('../private/initialize.php');
   
   // for security reasons we need to perform mandatory checks
   before_every_protected_page();
   
  

   if(!request_is_post() || !request_is_same_domain() || 
   !csrf_token_is_valid() || !csrf_token_is_recent()) {


    echo json_encode("Something unexpectedly happended, Please refresh the page.");
    return;
 }


    if(isset($_POST["search_query"])){

      print(json_encode(PhoneBook::search_contacts($_POST["search_query"])));

    }
      



 




?>
