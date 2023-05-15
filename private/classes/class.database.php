<?php 

namespace db;

use mysqli;


require_once ("../private/initialize.php"); 



          
 class MySql {
        
        
    private $last_query;
    public $db;

         
        
 public function __construct(){

    $this->open_connection();

}


private function open_connection() {
    
    //open a connection to the mysqli db    
 $this->db = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME) ;
     
     //check to see if the connection generated an error ,
     //die in that case.
  if ($this->db->connect_errno){
    //
    die("Database connection failed: ".$this->db->connect_error."(".mysqli_connect_errno().")");
    
}else{
       return $this->db;
}

}   

    public function mysql_prep($value){
    $value = mysqli_escape_string($this->db,$value);
    return $value;
}

    public function confirm_query ($result_set){
           //$this->last_query = $query_string;        
        if (!$result_set){
            $output = "Database query failed. ".mysqli_error($this->db) ;
            $output .= "<br><br>";
            $output .= "Last query:  ".$this->last_query;
            die($output);
            }
    }

 public function escape_values($string=""){
  
   
 return mysqli_real_escape_string($this->open_connection(),$string);
 
 } 

    
    public function fetch_array($input){
        $result = mysqli_fetch_array($input);
        return $result;
        
    }
    
  
    
    public function affected_rows(){
        
    return mysqli_affected_rows($this->db);
        
    }
    
    public function num_rows($result_set){
    
    return mysqli_num_rows($result_set);
        
    }
    public function close_connection (){
        // mysqli_free_result($result);
         mysqli_close($this->db);   
                     }
                     
                                 
         }
         
        

       
    ?>
