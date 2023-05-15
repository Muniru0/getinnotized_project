<?php require_once ("class.database.php"); ?>

<?php


use db\MySql;

class DatabaseObject  {
   

  private static $table_name = "";
  private static $db_field_time_stamp = "";
  private static $db_field_firstname = "";
  private static $db_field_lastname = "";
  private static $db_field_phone_number = "";
  private static $id = "";
  public static $db_fields = ['username','password','firstname','lastname'];



   
  public static function search_contacts($search_query = ""){



    try{

      $id = 0;
      $firstname = "";
      $lastname  = "";
      $phone_number = "";
      $timestamp = 0;

  

      // get a connection to the database.
      $db = (new mySql())->db;

      // escape the string for mysql
      $search_query = $db->real_escape_string($search_query);


      // Prepare the SQL statement
      $query = "SELECT * FROM " . static::$table_name . "
      WHERE ". static::$db_field_firstname." LIKE CONCAT('%', ?, '%')
      OR ". static::$db_field_lastname." LIKE CONCAT('%', ?, '%')
      OR ". static::$db_field_phone_number." LIKE CONCAT('%', ?, '%')";
      
      $stmt = $db->prepare($query);
      
      // Bind the search query parameter to the prepared statement
      $stmt->bind_param("sss", $search_query, $search_query, $search_query);
      
      // Execute the prepared statement
      $stmt->execute();
       // get the result from the executed statement
       $stmt->bind_result($id,$firstname, $lastname,$phone_number,$timestamp);
      
       $result = [];
      while ( $stmt->fetch()){
        $result [] = ["firstname"=> $firstname, "lastname"=> $lastname,"timestamp"=> $timestamp,"id"=> $id,"phone_number"=> $phone_number];
      }
      
      // clean the resources
      static::free_resources($stmt ,$db);
      
      
      // return the result
      return ["",$result];
      
      



    }catch(mysqli_sql_exception $e){

      if($e->getCode() === 1146){
        return ["Something unexpectedly happended. Please try again.",null];
      }

      return ["Something unexpectedly happended. Please try again.",null];
    }
 
  }

 
  




  // a generic static method to be used by alot of other subclasses
  // should there be the need. e.g class.phonebook.php for finding all records in the phonebook table.
   //class.users.php for finding all users in the users table,etc.

   public static function find_all ($limit = 10){



    try{
           $id = 0;
           $firstname = "";
           $lastname  = "";
           $phone_number = "";
           $timestamp = 0;

            // get a database connection
            $db = (new MySql())->db;


            // define the database query
            $query = "SELECT * FROM " . static::$table_name . " ORDER BY ".static::$db_field_time_stamp." DESC LIMIT " . $limit;
           
            // prepare the database statement
            $stmt = $db->prepare($query);

            //execute the prepared statement
            $stmt->execute();

            // get the result from the executed statement
             $stmt->bind_result($id,$firstname, $lastname,$phone_number,$timestamp);

             $result = [];
            while ( $stmt->fetch()){
              $result [] = ["firstname"=> $firstname, "lastname"=> $lastname,"timestamp"=> $timestamp,"id"=> $id,"phone_number"=> $phone_number];
            }

            // clean the resources
            static::free_resources($stmt ,$db);
          

            // return the result
            return ["",$result];
    }catch(mysqli_sql_exception $e){
      return ["Please try again.",null]; 
     }
    

  }//find_all();


   // a generic static method to be used by alot of other subclasses
  // should there be the need. e.g class.phonebook.php for finding a specific record in the phonebook table.
   //class.users.php for finding a specific user in the users table,etc.
   public static function find_one($id = 0){

    try{

      $id = intval($id);
      $firstname = "";
      $lastname  = "";
      $phone_number = "";
      $timestamp = 0;
      

       // get a database connection
       $db = (new MySql())->db;


       // define the database query
       $query = "SELECT * FROM " . static::$table_name." WHERE id= ? LIMIT 1" ;
      
       // prepare the database statement
       $stmt = $db->prepare($query);



        // prepare the database statement
        $stmt->bind_param("i",$id);

       //execute the prepared statement
       $stmt->execute();

       // get the result from the executed statement
        $stmt->bind_result($id,$firstname,$lastname);

        $result = [];
       while ( $stmt->fetch()){
         $result [] = ["firstname"=> $firstname, "lastname"=> $lastname,"timestamp"=> $timestamp,"id"=> $id,"phone_number"=> $phone_number];
       }

       // clean the resources
       static::free_resources($stmt ,$db);
     

       // return the result
       return ["",$result];
}catch(PDOException $e){
 return ["Please try again.",null]; 
}

}// find_one();



// for freeing the system resources.
public static function free_resources($stmt ,$db ){

  //free the result
  $stmt->free_result();


// close the statement
$stmt->close();


// close the db connection
$db->close();
   
}


 // a generic static method to be used by alot of other subclasses
  // should there be the need. e.g class.phonebook.php for creating records in the phonebook table.
   //class.users.php for creating users in the users table,etc.

  public static function create(...$values){




        if(empty($values) || empty(static::$db_fields)){
          return 0;
        }


        try{

    // get a connection to the database.
    $db = (new MySql())->db;


    // as a defence in-depth strategy, escape the all the strings
    foreach($values as $index => $value){
      $values[$index] = $db->real_escape_string($value);

    }
    

    // define the query
    $query = "  INSERT INTO ".static::$table_name."  (".  implode (", ", array_values(static::$db_fields)).") VALUES ( ?, ?, ?, ?)";
    
    
    
    
     // prepare the query/statement
    $stmt = $db->prepare($query);

    // bind the necessary parameters
    $stmt->bind_param("sssi", $values[0], $values[1],$values[2],$values[3]);
    

    // execute the prepared statement
    if (!$stmt->execute()) {
      // release the resources
      static::free_resources($stmt ,$db);
     

      return "Contact addition failed, please try again.";
    }
    
    $inset_id =  $db->insert_id;
   // clean the resources
   static::free_resources($stmt ,$db);
     

   // return the insert_id to be used for contact identity
    return  $inset_id;
  }catch(mysqli_sql_exception $e){
        if ($e->getCode() === 1062) {
          
          return "Please this phone number already exists.";
      }else{
        return "Unknown error occured, please try again.";
      }
  }
  
  }



   // a generic static method to be used by alot of other subclasses
  // should there be the need. e.g class.phonebook.php for updating records in the phonebook table.
   //class.users.php for updateting users in the users table,etc.

  public static function update($firstname = "",$lastname = "",$phone_number = "",$id = 0){

    //NOTE: We are intentionally not updating the timestamp field,
    
    try{


      
    // get a connection to the database
    $db = (new MySql())->db;


    // define the query
    $query = "UPDATE ".static::$table_name." SET ".static::$db_field_firstname." = ?, ".static::$db_field_lastname." = ?, ".static::$db_field_phone_number."= ?  WHERE id = ?";


    // prepare the query string
    $stmt = $db->prepare($query);

    // Bind the values
    $stmt->bind_param('sssi', $firstname, $lastname, $phone_number,$id);
    
    // Execute the statement
    $stmt->execute();

    $result =  ($stmt->affected_rows > 0);

    // release the system resources.
   static::free_resources($stmt ,$db);


  return  $result;
      

    
    }catch(mysqli_sql_exception  $d){
      
      return "Unknown error occured, please try again.".$d;
    }


  }




   // a generic static method to be used by alot of other subclasses
  // should there be the need. e.g class.phonebook.php for deleting records in the phonebook table.
   //class.users.php for deleting users in the users table,etc.

  public static function delete($id = 0){
    
    try{

    
      // get a connection to the database.
      $db = (new MySql())->db;

      // define and prepare the query string
      $stmt = $db->prepare("DELETE FROM ".static::$table_name."  WHERE id = ?");
  
      // Bind the values
      $stmt->bind_param('i',$id);
      
      // Execute the statement
      $stmt->execute();
  
      // get the result from the executed statement
      $result = ($stmt->affected_rows > 0);


      // release system resources.
      static::free_resources($stmt ,$db);
      
      // whether or not the delete occured.
      return   $result;
        
  
      
      }catch(mysqli_sql_exception  $d){
        
        return "Unknown error occured, please try again.".$d;
      }
  
  }

}

 
 


?>