<?php require_once ("../private/initialize.php");?>

<?php
/**
 *Every thing about the user should be in the user class(sign ups , logins,updatin info,etc)
 *
 *
 */



class PhoneBook extends DatabaseObject{
    
    
    public static $table_name    = "phonebook";

    public static $db_field_id          = "id";
    public static $db_field_firstname   = "firstname";

    public static $db_field_lastname    = "lastname";

    public static $db_field_phone_number= "phone_number";

    public static $db_field_time_stamp    = "timestamp";

    public static $db_fields = ["firstname","lastname","phone_number","timestamp"]; 
    
   


    public static $id                = "" ;
    public static $firstname         = "" ; 
    public static $lastname          = "" ;
    public static $phone_number      = "";
   
    public static $time_stamp         = 0;


    


}

   
  

 


?>
   