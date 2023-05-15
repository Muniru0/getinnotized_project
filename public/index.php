<?php


    //NOTE: I am fetching the contacts on page load
    // because of number of contacts and the project at
    // hand. In a real world scenario we may need to load the
    // bare html for fast response, then use an AJAX request 
    // to load the contacts.

   // require the site initialization script
   require_once('../private/initialize.php');
   
   // for security reasons we need to perform mandatory checks
   before_every_protected_page();
   $lang = ["lang" => "en"];



    if(!request_is_get() && !request_is_post()) {
	
      echo "Something happended unexpectedly,Please refresh the page. same domain";

      return;
    }


    $err_msg = "";
    $recent_contacts = "";
    $query_response = PhoneBook::find_all();

    if(empty(trim($query_response[0]))){
      $num_of_results =  count($query_response[1]);
      $recent_contacts = json_encode($query_response[1]);
     
    }else{
      $err_msg =  json_encode($query_response[0]);
    }

   
 

     if(request_is_same_domain() && request_is_post() && csrf_token_is_valid() && csrf_token_is_recent()){

      print(json_encode($query_response));

      return;
     }


 




?>


<!DOCTYPE html>
<html lang="<?php echo $lang['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phonebook</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/<?php echo trim(pathinfo(basename($_SERVER['PHP_SELF']),PATHINFO_FILENAME)); ?>.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <script language="javascript" type="module" src="assets/js/jquery.min.js"></script>
    <script language="javascript" type="module" src="assets/js/index.js"></script>
  
    <script>

const reformatPhoneNumber = phoneNumber =>{ 
    console.log(phoneNumber);
  return phoneNumber.slice(0, 3) + "-" + phoneNumber.slice(3, 6) + "-" + phoneNumber.slice(6);
}




 const monthAndDay = (seconds) =>{


  // Create a new Date object using the provided seconds
  const date = new Date(seconds * 1000);

  // Extract the month and day from the date object
  const month = date.toLocaleString('default', { month: 'short' });
  const day = date.getDate();

  // Format the result as desired
  return month + ' ' + day;

 
    }

    document.addEventListener('DOMContentLoaded', ()=> {
    

    const dbErrMsg = "<?php echo $err_msg; ?>" ;  
    

    if(dbErrMsg.length === 0){
       
      if(<?php echo $num_of_results; ?> !== 0){
      
        const recentContacts = <?php echo  $recent_contacts; ?>;
      

        let recentContactsHtml = "";



         for(const key in recentContacts){
           
        const {id,firstname,lastname,phone_number,timestamp} =  recentContacts[key];

          console.log(reformatPhoneNumber(phone_number))
          recentContactsHtml += `<div  class="contacts_item">
            <div id="${id}" class="details_parent">
                    <div class="phone_icon  material-symbols-outlined">
                        <i class=" icon material-symbols-outlined center_icon">call</i>
                    </div>
                    <div class="contact_details">
                        <b class="contact_name">${firstname} ${lastname}</b>
                        <p class="phone_number"><span class="number"> ${reformatPhoneNumber(phone_number)} since </span><b class="timestamp">${monthAndDay(timestamp)}</b></p>
                    </div>
            </div>
        <div id=d-${id} class="delete_option_wrapper"><i  class=" icon material-symbols-outlined delete">delete</i></div>
                </div>`;
         }
       

         $('#contactsItemsParent').prepend(recentContactsHtml);


      }
  
      
   


  }

    }, false);
    </script>

</head>
<body>
<div class="overlay"></div>
   <main>
   <div class="first background-ball"></div>
    <div class="second background-ball"></div>
   <section>

   <div class="contacts">
  <div class="phonebook-title">
      <h2 class="title">PhoneBook <span class="highlighted-heading">App</span></h2>
  </div>

   <div class="search-outer-wrapper">
       <div class="search-wrapper">
           <i class="my-search-icon"></i>
          <div class="input_container search_contact">
      <label for="contact_search" class="input_label"></label>
      <div class="search_input_wrapper"><div class="input_parent"><input id="contact_search" class="search_input_field search_contact" type="text" name="input-firstname" title="Inpit title" placeholder="Enter name or phoneNumber">
      <span class="search_icon material-symbols-outlined">search</span>
 </div><div id="addContact" class="add_contact"> + Add Contact</div>

</div>
    </div>
      </div>


    <div class="contacts_search_results">
        <div class="results_wrapper">
        <div class="results_number">
            Found <strong class="number contacts_count"><?php echo $num_of_results; ?></strong>  results
        </div>

        <div id="contactsItemsParent" class="contacts_items_parent">
           
        <div class="empty_box_wrapper"  style="<?php echo $num_of_results > 0 ? "display:none;" :""; ?>">
          <img class="empty_box" src="<?php echo BASE_URL . "/public/assets/images/empty_box.png";?>" alt="no contacts"  >
          <span class="no_contacts_added">No Contacts Yet.<span class="add_contacts_text">You can add some above.</span></span>
        </div>
       
          </div>
      
    
          </div>
    </div>

    
   </div>  
   </div>
 
  
   



  <div id="addContactModal" class="modal" style="display:none;">

   <div class="left-arrow"></div>
   <span class="material-symbols-outlined close_contact_modal_create">close</span>



   <form id="addContactForm" method="POST" class="form">

  <div class="payment--options">
    <h2 class="add-contact-heading">+ Add Contact </h2>
</div>
  <div class="separator">
    <hr class="line">
    <p>add a contact below</p>
    <hr class="line">
  </div>
  <d class="credit-card-info--form">
    <div class="input_container">
      <label for="password_field" class="input_label">Firstname</label>
      <input id="firstname" class="input_field" type="text" name="firstname" title="Inpit title" placeholder="Enter your firstname">
    </div>
    <div class="input_container">
      <label for="password_field" class="input_label">Lastname</label>
      <input id="lastname" class="input_field" type="text" name="lastname" title="Inpit title" placeholder="Enter your lastname">
    </div>
    <div class="input_container">
      <label for="password_field" class="input_label">PhoneNumber</label>
      <input id="phone" class="input_field" type="number" name="phone_number" title="Expiry Date" placeholder="000-000-0000">
    </div>
  
  <?php echo csrf_token_tag(); ?>
    <button id="addContactBtn" class="add_contact_btn">Add Contact</button>
  
  </form>
</div>


<div id="updateContactModal" class="modal" style="display:none;">

   <div class="left-arrow"></div>
   <span class="material-symbols-outlined close_contact_modal_update">close</span>

   <form id="updateContactForm" method="POST" class="form">

  <div class="payment--options">
    <h2 class="add-contact-heading">Update Contact </h2>
  </div>
 

  <div class="separator">
    <hr class="line">
    <p>Update Contact</p>
    <hr class="line">
  </div>
  <div class="credit-card-info--form">
    <div class="input_container">
      <label for="password_field" class="input_label">Firstname</label>
      <input id="updateFirstname" class="input_field" type="text" name="firstname" title="Inpit title" placeholder="Enter your firstname">
    </div>
    <div class="input_container">
      <label for="password_field" class="input_label">Lastname</label>
      <input id="updateLastname" class="input_field" type="text" name="lastname" title="Inpit title" placeholder="Enter your lastname">
    </div>
    <div class="input_container">
      <label for="password_field" class="input_label">PhoneNumber</label>
      <input id="updatePhone" class="input_field" type="number" name="phone_number" title="Expiry Date" placeholder="000-000-0000">
       </div>
  </div>

   <input type="hidden" name="id" id="updateContactID" value="0">
  <?php echo get_stored_csrf_token(); ?>
  
    <button id="updateContactBtn" class="add_contact_btn">Update Contact</button>
  

  </form>
  </div>


<div id="error_dialog" class="card">
  <div class="header">
    <div class="image"><svg aria-hidden="true" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" fill="none">
                <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linejoin="round" stroke-linecap="round"></path>
              </svg></div>
    <div class="content">
       <span class="title">Ooops!! Error</span>
       <p class="message">Are you sure you want to deactivate your account? All of your data will be permanently removed. This action cannot be undone.</p>
    </div>
     <div class="actions">
       <button id="cancel_error" class="cancel" type="button">Cancel</button>
    </div>
  </div>
  </div>

   </section>

   </main>





</body>

</html>