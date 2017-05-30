// JavaScript Document

$(document).ready(function() {
    $("#submit_btn").click(function() {
        //get input field values
        var user_name       = $('input[name=name]').val();
        var user_email      = $('input[name=email]').val();
        var user_phone      = $('input[name=phone]').val();
        var user_message    = $('textarea[name=message]').val();
       	
		//simple validation at client's end
        //we simply change border color to red if empty field using .css()
        if(user_name==""){
            $('input[name=name]').css('border-color','red');
            return false;
        }else if(user_email==""){
            $('input[name=email]').css('border-color','red');
            return false;
        }else if(user_phone=="") {    
            $('input[name=phone]').css('border-color','red');
            return false;
        }else if(user_message=="") {  
            $('textarea[name=message]').css('border-color','red');
            return false;
        }else{
			$("#result").html('<div class="alert alert-success"> Message has been sent successfully.</div>');
		}
		
    });
});
