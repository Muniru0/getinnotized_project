# Getinnotized_project
A test project from getInnotized



# SETUP
 1. You need a server that runs the latest versions of Apache, Php , Mysql (xampp server recommended).
 2. You may need to go to # /getinnotized_project/setup/exported_getinnotized_db.sql and use run this sql file to create the sample database.
 3. You may also need to create and allocate the privileges to the Database User #getinnotized_admin identified by #getinnotizedpassword.
    You can view the entire configuration of the database by going to the file: /getinnotized_project/private/functions/config.php.
    Below is the configuration of the database,edit it as per your preference.
     DB_SERVER : localhost
     DB_USER:getinnotized_admin
     DB_NAME:getinnotized_db
     DB_PASS:getinnotizedpassword
     
 
 # PORTS SETUP
 1. Depending on your prefered port you can uncomment/comment line 11 or 14 of # /getinnotized_project/private/functions/request_forgery_functions.php of the #request_is_same_domain function
    and make the neccesary adjustments.
 
 2.  Depending on your prefered port you can uncomment/comment line 30 to 32 of # /getinnotized_project/private/functions/initialize.php and make the neccesary adjustment to match with your preferred port.

 3. Depending on your prefered port you can uncomment/comment line 8 of # /getinnotized_project/public/assets/js/utils.js and make the neccesary adjustment to match with your preferred port.
 
   
   
  
