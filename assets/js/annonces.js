$(document).ready(function() {

	$('.btn__annonces').click(function(e){
		e.preventDefault();
		console.log("Merci de déposer une annonce !");
		$.ajax({
	       url: './data/tracking.php',
	       dataType: 'json',
	       success: function(data){
	            console.log(data);
	       },
	       failure: function(error){
	            console.log(error);
	       }
    	});
	});

	$('#annonces-form').submit(function() {

		var buttonCopy = $('#annonces-form button').html(),
			errorMessage = $('#annonces-form button').data('error-message'),
			sendingMessage = $('#annonces-form button').data('sending-message'),
			okMessage = $('#annonces-form button').data('ok-message'),
			hasError = false;

		$('#annonces-form .error-message').remove();

		$('#annonces-form .requiredField').each(function() {
			if($.trim($(this).val()) == '') {
				var errorText = $(this).data('error-empty');
				$(this).parent().append('<span class="error-message" style="display:none;">'+errorText+'.</span>').find('.error-message').fadeIn('fast');
				$(this).addClass('inputError');
				hasError = true;
			} else if($(this).is("input[type='email']") || $(this).attr('name')==='contactEmail') {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test($.trim($(this).val()))) {
					var invalidEmail = $(this).data('error-invalid');
					$(this).parent().append('<span class="error-message" style="display:none;">'+invalidEmail+'.</span>').find('.error-message').fadeIn('fast');
					$(this).addClass('inputError');
					hasError = true;
				}
			}
		});

		if(hasError) {
			console.log(hasError);
			$('#annonces-form button').html('<i class="fa fa-times"></i>'+errorMessage);
			setTimeout(function(){
				$('#annonces-form button').html(buttonCopy);
			},2000);
		} else {
			$('#annonces-form button').html('<i class="fa fa-spinner fa-spin"></i>'+sendingMessage);

			var formInput = $(this).serialize();
			$.post($(this).attr('action'),formInput, function(data){
				$('#annonces-form button').html('<i class="fa fa-check"></i>'+okMessage);

				$('#annonces-form')[0].reset();

				setTimeout(function(){
					$('#annonces-form button').html(buttonCopy);
				},2000);

			});
		}

		return false;
	});
});