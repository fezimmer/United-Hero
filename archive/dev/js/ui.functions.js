$(document).ready(function() {

	$('input.main-search-box').focus();
	$('div.forgot_form').hide();
	$('div#googlebar').hide();
	$('div#imageBox').hide().fadeIn(1500, function() { $('div#googlebar').fadeIn(200); });
	
	





$('a.submit-signup').click(function() {
	
	$(this).hide(); 
	//alert('work!');
	
	//$('.loading').show().html('<img src="../images/contactform_loader.gif" align="absmiddle">');
	$('#form-container').animate({ opacity: 0.1 }, 100, function() {
																 
			
		// gather form values
		var firstname = $('input#firstname').val();
		var email = $('input#email').val();
		var password = $('input#password').val();	
		var lastname = $('input#lastname').val();
		var alt_email = $('input#alt_email').val();
		var location = $('input#location').val();	
		var confirm = $('input#confirm').val();
		
		var data_string = '?firstname='+firstname+'&email='+email+'&password='+password+'&alt_email='+alt_email+'&lastname='+lastname+'&location='+location+'&confirm='+confirm;
		var theURL = 'email_lib/add_email.php'+data_string;
		
		//alert(theURL);
		
		$.ajax({
		
		url: theURL,
		type: 'GET',
		success: function(work){
					
					
					$('#form-container').empty().animate({ opacity: 1.0 }, 100).html(work);
					
				}
		});	// end ajax
																 
	});
	

});


/// email request


$('a.send_info').click(function() {
	
	$(this).hide(); 
	
	//$('.loading').show().html('<img src="../images/contactform_loader.gif" align="absmiddle">');
	$('.forgot_form').show();
																 
			
		// gather form values
		var firstname = $('input#firstname').val();
		var email = $('input#email').val();
		var password = $('input#password').val();	
		var age = $('input#age').val();
		var alt_email = $('input#alt_email').val();
		var location = $('input#location').val();	
		var confirm = $('input#confirm').val();
		
		var data_string = '?firstname='+firstname+'&email='+email+'&password='+password+'&alt_email='+alt_email+'&age='+age+'&location='+location+'&confirm='+confirm;
		var theURL = 'email_lib/add_email.php'+data_string;
		
		//alert(theURL);
		
		$.ajax({
		
		url: theURL,
		type: 'GET',
		success: function(work){
					
					
					$('#form-container').empty().animate({ opacity: 1.0 }, 100).html(work);
					
				}
		});	// end ajax
																 
	});
	





});