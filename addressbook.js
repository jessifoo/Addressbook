// JavaScript Document 
//global variable to hold client copy of the contacts in the database 
var addresslist=""; 

/**
* Function to ensure there are no duplicate entries 
* @param <string> name: contact name
* @param <string> phone: contact phone number
* @param <string> twitter: contact twitter handle
* @return <boolean>: true or false depending on if duplicate entries are found
*/
function isDuplicate(name,phone,twitter){
	var isduplicate=false; 
		for(var i=0;i<addresslist.length; i++){ 
			if(addresslist[i].name.toLowerCase()==name.toLowerCase() 
				&& addresslist[i].phone.toLowerCase()==phone.toLowerCase()
				&& addresslist[i].twitter.toLowerCase()==twitter.toLowerCase()){ 
				isduplicate=true; 
			} 
		} 
	return isduplicate; 
} 


/**
* Function to display the contacts in table format
* @param <array> items: Array of contacts containing name,phone,twitter handle, and follower count
*/
function displayAddressList(items){
	var list=$("#contacts-lists"); 
	addresslist=items; 
	var lh=""; 
	for(var i=0;i<items.length; i++){ 
		lh+='<table><tr><td width="200px">'+items[i].name+'</td>';
		lh+='<td width="200px">[ '+items[i].phone+' ]</td>';
		lh+='<td width="200px"> <a href="http://www.twitter.com/'+items[i].twitter+'" id="twitterlink">'+items[i].twitter+'</a></td>';
		lh+='<td width="200px">'+items[i].followercount+'</td>';
		lh+='<td width="100px"> <a href="#delete-id" class="deletebtn" id='+items[i].id+'"> delete </a></td></tr></table>';
	} 
	list.html(lh); 
	setDeleteButtonEvents();
} 

/**
* Function to set click event and execute ajax call for the "Update Follower Count" button
*/
function setUpdateButtonEvent(){
	$('#update-count-btn').click(function(){
		$('#notice').empty().html('updating...').show();
		$.ajax({ 
                	url: 'addressbook.php', 
                        data: 'action=update', 
                        dataType: 'json', 
                        type: 'post', 
                        success: function (j) {    
                                $('#notice').empty().html(j.msg); 
                                displayAddressList(j.contacts); 
                        }, error: function (xhr,error){
			 	console.debug(xhr); console.debug(error);	
			}
              	});
	});	
}

/**
* Function to set click event and execute ajax call for the "Save Contact" button
* Also validate user input for non-null entries, correct format for phone number and no numbers in name
*/
function setSaveButtonEvent(){ 
	$('#save-contact-btn').click(function(){ 
		$('#notice').hide(); 
		var name=$('#name').val(); 
		var phone=$('#phone').val(); 
		var twitter=$('#twitter').val();
		if(name=="" || phone=="" || twitter==""){ 
			$('#notice').empty().html('No field can be empty').show('slow'); 
		}else if(isNaN(new Number(phone))){ 
			$('#notice').empty().html('the phone field must contain valid numeric data').show('slow'); 
		}else if((parseInt(phone/1000000)==0)){ 
			$('#notice').empty().html('the phone field is too short, it must be minimum 7 characters XXX-XXXX').show('slow'); 
		}else if((parseInt(100000000000/phone)==0)){ 
			$('#notice').empty().html('the phone field is too long, it must be maximum 11 characters X-XXX-XXX-XXXX').show('slow'); 
		}else if(name.match(/\d/)){ 
			$('#notice').empty().html('the name field must not contain numeric input').show('slow'); 
		}else if(twitter.length > 15){
			$('#notice').empty().html('Woops, twitter only allows maximum 15 character handles').show('slow');
		}else if(isDuplicate(name,phone,twitter)){ 
			$('#notice').empty().html('the contact info you specified is already in the database').show('slow'); 
		}else{ 
			$('#notice').empty().html('saving....').show(); 
			$.ajax({ 
			 url: 'http://addressbook.jessica-johnson.ca/addressbook.php', 
			 data: 'action=add&name='+name+'&phone='+phone+'&twitter='+twitter, 
			 dataType: 'json', 
			 type: 'POST', 
			 success: function (j) {     
				$('#notice').empty().html(j.msg); 
				$('#name').val(''); 
				$('#phone').val('');
				$('#twitter').val(''); 
				$('#add-contact-form').hide(); 
				displayAddressList(j.contacts); 
			 },
			 error: function (xhr,error){
			 	console.debug(xhr); console.debug(error);	
			 } 
			}); 
		} 
	}); 
} 


/**
* Function to set click event and execute ajax call for the "Delete" button
*/
function setDeleteButtonEvents(){ 	
	$('.deletebtn').each(function(i){ 
		$(this).click(function(){ 
			var answer = confirm("are you sure you want to delete this contact?"); 
			if(!answer){ 
				return; 
			} 
			$('#add-contact-form').hide(); 
			$('#notice').empty().html('deleting...').show(); 
			var id=$(this).attr('id'); 
			 $.ajax({ 
			 url: 'addressbook.php', 
			 data: 'action=delete&id='+id, 
			 dataType: 'json', 
			 type: 'post', 
			 success: function (j) {   
				$('#notice').empty().html(j.msg); 
				displayAddressList(j.contacts); 
			 } ,
			 error: function (xhr,error){
			 	console.debug(xhr); console.debug(error);	
			 }
			}); 
		}); 
	}); 
} 

//initilize the javascript when the page is fully loaded 
$(document).ready(function(){              
              $('#add-contact-form').hide(); 
              $('#notice').hide(); 
              // Add contact button event
              $('#add-contact-btn').click(function(){ 
              		$('#notice').hide(); 
                        $('#add-contact-form').show('slow'); 
              }); 
	      // Cancel button event
              $('#cancel-btn').click(function(){ 
                         $('#add-contact-form').hide('slow'); 
                         $('#notice').hide(); 
              }); 
              setDeleteButtonEvents(); 
              setSaveButtonEvent(); 
              setUpdateButtonEvent(); 
              $.ajax({ 
              		url: 'addressbook.php', 
                        data: '', 
                        dataType: 'json', 
                        type: 'post', 
                        success: function (j) {             
                        	displayAddressList(j.contacts); 
                        },
                        error: function (xhr,error){
			 	console.debug(xhr); console.debug(error);	
			}
              }); 
}); 