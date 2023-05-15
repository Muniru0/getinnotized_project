
import { ADD_CONTACT_URL,UPDATE_CONTACT_URL,DELETE_CONTACT_URL,SEARCH_CONTACT_URL,MAIN_CONTACTS_URL } from "./utils/constants.js";
import {contactWidget,reformatPhoneNumber} from "./utils/ui_utils.js";





let selectedContactID = 0;



const fetch_recent_contacts = () =>{
    $.ajax({
        url: MAIN_CONTACTS_URL,
        type: "POST",
        data: {"csrf_token":$("#csrf").val().trim()},
        success: (response,)=>{
            response = JSON.parse(response);
             console.log(response);
           
            if(response[0].length === 0){
             let recentContactsHtml = "";


             $(".contacts_count").text(response[1].length)
             for(const key in response[1]){
               
             recentContactsHtml += contactWidget(response[1][key]);
             }
           
             if((response[1].length === 0) && !($('.empty_box_wrapper').is(':visible'))){
                 console.log('here');
                 $('.empty_box_wrapper').show();
             }
             
         

              if(response[1].length > 0 && ( $('.empty_box_wrapper').is(':visible'))){
                 $('.empty_box_wrapper').hide();
              }
              $(".contacts_item").remove();
             $('#contactsItemsParent').prepend(recentContactsHtml);

            }else{

           
             if(response[0] === false){
              $('.message').text('');
              $(".message").text(response[0]);
             }else{
  
              response.foreach((item)=>{
                  $(".message").append(item + "<br>");
                          });
  
             }
             $('#error_dialog').show();
            }
            
 
     
            
 
        
         }
   });
}

// Will be using this single js file just for simplicity
$(()=>{


    
      
    // delegate a call to the cancel error dialog button
    $("#contact_search").on('keyup', function(){
       
        const searchQuery = $(this).val().trim();
        
        if(searchQuery.length === 0){
            fetch_recent_contacts();
            return;
        } 


        $.ajax({
            url: SEARCH_CONTACT_URL,
            type: "POST",
            data: {"search_query": searchQuery,"csrf_token":$("#csrf").val().trim()},
            success: (response,)=>{
               response = JSON.parse(response);
                console.log(response);
              
               if(response[0].length === 0){
                let recentContactsHtml = "";


                $(".contacts_count").text(response[1].length)
                for(const key in response[1]){
                  
                recentContactsHtml += contactWidget(response[1][key]);
                }
              
                if((response[1].length === 0) && !($('.empty_box_wrapper').is(':visible'))){
                    console.log('here');
                    $('.empty_box_wrapper').show();
                }
                
            

                 if(response[1].length > 0 && ( $('.empty_box_wrapper').is(':visible'))){
                    $('.empty_box_wrapper').hide();
                 }
                 $(".contacts_item").remove();
                $('#contactsItemsParent').prepend(recentContactsHtml);

               }else{

              
                if(response[0] === false){
                 $('.message').text('');
                 $(".message").text(response[0]);
                }else{
     
                 response.foreach((item)=>{
                     $(".message").append(item + "<br>");
                             });
     
                }
                $('#error_dialog').show();
               }
               
    
        
               
    
           
            }
       });
    

    });

     

    // delegate a call to the cancel error dialog button
    $("#cancel_error").on('click',()=>{
        $(".overlay").hide();
        $("#error_dialog").hide();
    });


    // close the create or update contact modal dialog
    $(".close_contact_modal_create, .close_contact_modal_update").on('click',function(){
        console.log(this);        
        $(this).parentsUntil("section").toggle();
    });



   // delegate a call to the add contact button 
   $("#addContact").on("click", () =>{


      // show the add Contacts modal
        $("#addContactModal").toggle();
        if($("#updateContactModal").is(":visible")){
            $("#updateContactModal").toggle();
        }


   });

   
   // delegate a call to the add contact button 
   $("#contactsItemsParent").on("click",".delete_option_wrapper", function() {

    console.log($("#csrf").val().trim());
    const deleteID = $(this).attr("id").split("-")[1];


    $.ajax({
        url: DELETE_CONTACT_URL,
        type: "POST",
        data: {"id": deleteID,"csrf_token":$("#csrf").val().trim()},
        success: (response,)=>{
            console.log(response);
           response = JSON.parse(response);
           if(response[0] === true){
            $(this).parent().remove(); 
            const currentContactsCount =  parseInt($('.contacts_count').text());
            $('.contacts_count').text(currentContactsCount - 1);
            
            if((currentContactsCount - 1 === 0) && !($('.empty_box_wrapper').is(':visible'))){
                
                $('.empty_box_wrapper').show();
            }
            
            return;
           }else if(response[0] === false){
            $('.message').clear();
            $(".message").text(response[0]);
           }else{

            response.foreach((item)=>{
                $(".message").append(item + "<br>");
                        });

           }

       $('#error_dialog').show();
           

       
        }
   });


   });


     

   $("#contactsItemsParent").on("click",".details_parent", function(){

     
      // show the add Contacts modal
      $("#updateContactModal").show();
      if($("#addContactModal").is(":visible")){
          $("#addContactModal").toggle();
      }


       // fetch and update the update form with the selected contact details


         // set the id
        selectedContactID = $(this).attr("id");
       
       $("#updateContactID").val(selectedContactID);

       // set firstname
      const fullname =  $(this).find(".contact_name").text().split(" ");
      $("#updateFirstname").val(fullname[0]) ;


      // set lastname
      fullname.shift();
       $("#updateLastname").val(fullname.join(" "));

        // set phone number
       let phoneNumber = $(this).find(".number").text()
      $("#updatePhone").val(phoneNumber.trim().replaceAll("-","").split(" ")[0]) ;    



   });



  
   $(".input_field").on("keyup", function(){

      console.log(this);
     // get the user input
     const userInput = $(this).val();
    
     // if the user input is empty then return
     if (userInput.length === 0){
        if($(this).hasClass("invalid_input")){
           $(this).removeClass("invalid_input");
        }

       
     }


     // declare a switch variable to toggle the valid state text fields
     let isInputValid = false;
    
     // set the isInputValid flag whether the user input is valid
    ($(this).attr("type") === "text") && (isInputValid =  containsOnlyLetters(userInput));

    
     ($(this).attr("type") === "number") && (isInputValid = (isNumber(userInput) && (userInput.length === 10)))


    // show a red border
    isInputValid ? $(this).removeClass("invalid_input") : $(this).addClass("invalid_input") 

    
   });


   $("#addContactBtn").on("click", (e)=>{


    // prevent the default action
    e.preventDefault();
    


    // verify that the information provided is valid
    if(!areAllFieldsValid()) return 

    
        // get the form data
        let formData = $("#addContactForm").serialize();
    
        console.log(formData);

        // send the form data to the server
        $.ajax({
             url: ADD_CONTACT_URL,
             type: "POST",
             data: formData,
             success: (response,)=>{
                console.log(response);
                 response = JSON.parse(response);
            if(!isNaN(response[0])){
                
                $("#addContactModal").toggle();
                const currentContactsCount =  parseInt($('.contacts_count').text());
                $('.contacts_count').text(currentContactsCount + 1);
                
                if( $('.empty_box_wrapper').is(':visible')){
                    
                    $('.empty_box_wrapper').hide();
                }
               

                
                $("#contactsItemsParent").prepend(contactWidget({"id":response[0],"firstname":$("#firstname").val(),"lastname": $("#lastname").val(),"phone_number":$("#phone").val().trim(),"timestamp":Date.now() / 1000}));
                $("#firstname").val("");
                $("#lastname").val("");
                $("#phone").val("");
                return;


            }

            if(typeof response[0] === 'string'){
                $(".message").val("");
                if(response.length === 1) {
                    ($(".message").text(response[0]));
                 
                }else{
                    response.foreach((item)=>{
                        $(".message").append(item + "<br>");
                    });
                }


                $(".overlay").show();
                $(" #error_dialog").show();
                
               
               

            }
            console.log(response)
                if(response){
                //  location.reload();
                }
             }
        });



   });



   $("#updateContactBtn").on("click", (e)=>{


    // prevent the default action
    e.preventDefault();
    
    
  
    // verify that the information provided is valid
    if(!areAllFieldsValid("updateFirstname","updateLastname","updatePhone")) return 

  
     
    
        // get the form data
        let formData = $("#updateContactForm").serialize();
    
        console.log(formData);


        // send the form data to the server
        $.ajax({
             url: UPDATE_CONTACT_URL,
             type: "POST",
             data: formData,
             success: (response,)=>{
                console.log(response);
                response = JSON.parse(response);
            if(response[0]){
              
                $("#"+ selectedContactID).find('.contact_name').text($('#updateFirstname').val() +" "+ $('#updateLastname').val());
            
                 // set phone number
                let phoneNumber = reformatPhoneNumber($("#updatePhone").val().trim());
                
                $("#"+ selectedContactID).find(".number").text(phoneNumber + " since");
                  
                
                $("#updateContactModal").toggle();

                $("#updateFirstname").val("");
                $("#updateLastname").val("");
                $("#updatePhone").val("");
                
               return;


            }

            if(typeof response[0] === 'string'){
                
                if(response.length === 1) {
                    ($(".message").html(response[0]));
                 
                }else{
                    response.foreach((item)=>{
                        $(".message").append(item + "<br>");
                    });
                }


                $(".overlay").show();
                $(" #error_dialog").show();
                
               
               

            }
            console.log(response)
                if(response){
                //  location.reload();
                }
             }
        });



   });


});



// hlper function for validating all the fields
const areAllFieldsValid = (firstnameSelector = "firstname",lastnameSelector = "lastname", phoneNumberSelector = "phone" ) => {

   return containsOnlyLetters($("#" + firstnameSelector).val()) &&
    containsOnlyLetters($("#" + lastnameSelector).val()) &&
    isNumber($("#" + phoneNumberSelector).val()) ;

}

// helper function for validating firstname and lastname

const containsOnlyLetters = (str) =>{
    const regex =/^[a-zA-Z]{3,}(?:\s*[a-zA-Z]*)$/;
    return regex.test(str);
  }




 const isNumber = (input) => {
    // Remove leading and trailing whitespace
    input = input.trim();
  
    // Remove the "+" character
    input = input.startsWith("+") ? input.slice(1) : input;
  
    // Check if the remaining input consists of 10-12 digits
    var regex = /^\d{10,12}$/;
    return regex.test(input);
  }


const sanitizeContactDetails = () =>{



}