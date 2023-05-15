

 const contactWidget = (contactObject) => {

    const {id,firstname,lastname,phone_number,timestamp} = contactObject;
    console.log(phone_number);
    return `<div class="contacts_item">
    <div id="${id}" class="details_parent">
             <div class="phone_icon  material-symbols-outlined">
                 <i class=" icon material-symbols-outlined center_icon">call</i>
             </div>
             <div class="contact_details">
                 <b class="contact_name">${firstname} ${lastname}</b>
                 <p class="phone_number"><span class="number"> ${reformatPhoneNumber(phone_number)} since </span><b class="timestamp">${monthAndDay(timestamp)}</b></p>
             </div>
    </div>
 <div id=d-${id} class="delete_option_wrapper"><i class=" icon material-symbols-outlined delete">delete</i></div>
         </div> `;
}



const reformatPhoneNumber = phoneNumber => phoneNumber.slice(0, 3) + "-" + phoneNumber.slice(3, 6) + "-" + phoneNumber.slice(6);

const monthAndDay = (seconds) =>{


    // Create a new Date object using the provided seconds
    const date = new Date(seconds * 1000);
  
    // Extract the month and day from the date object
    const month = date.toLocaleString('default', { month: 'short' });
    const day = date.getDate();
  
    // Format the result as desired
    return month + ' ' + day;
  
   
      }

      export {monthAndDay,reformatPhoneNumber,contactWidget}

 