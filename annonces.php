<?php 
$emailTo = 'thomas.difruscia@cycloclubvizille.com';
$siteTitle = 'Cyclo-club Vizille';

error_reporting(E_ALL ^ E_NOTICE); // hide all basic notices from PHP

if(isset($_POST['submitted'])) {
	if(trim($_POST['contactName']) === '') {
		$nameError =  'Vous avez oublié de rentrer votre nom et/ou prénom ou pseudo'; 
		$hasError = true;
	} else {
		$name = trim($_POST['contactName']);
	}
	if(trim($_POST['contactEmail']) === '')  {
		$emailError = 'Vous avez oublié de rentrer votre adresse email.';
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['contactEmail']))) {
		$emailError = "Vous avez rentré une mauvaise adresse email.";
		$hasError = true;
	} else {
		$email = trim($_POST['contactEmail']);
	}
	if(trim($_POST['contactTitle']) === '') {
		$titleError = "Vous avez oublié de rentrer un titre pour l'objet.";
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$title = stripslashes(trim($_POST['contactTitle']));
		} else {
			$title = trim($_POST['contactTitle']);
		}
	}
	if(trim($_POST['contactDescription']) === '') {
		$descriptionError = "Vous avez oublié de rentrer une desciption de l'objet.";
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$description = stripslashes(trim($_POST['contactDescription']));
		} else {
			$description = trim($_POST['contactDescription']);
		}
	}
	if(trim($_POST['contactPrice']) === '') {
		$priceError = "Vous avez oublié de rentrer un prix pour l'objet.";
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$price = stripslashes(trim($_POST['contactPrice']));
		} else {
			$price = trim($_POST['contactPrice']);
		}
	}
	if(trim($_POST['contactCodePostal']) === '') {
		$codePostalError = "Vous avez oublié de rentrer votre code postal.";
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$codePostal = stripslashes(trim($_POST['contactCodePostal']));
		} else {
			$codePostal = trim($_POST['contactCodePostal']);
		}
	}
	if(trim($_POST['contactCity']) === '') {
		$cityError = "Vous avez oublié de rentrer votre ville.";
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$city = stripslashes(trim($_POST['contactCity']));
		} else {
			$city = trim($_POST['contactCity']);
		}
	}	
		
	// upon no failure errors let's email now!
	if(!isset($hasError)) {
		$subject = 'Nouveau message de ' . $siteTitle.' pour l\'annonce ' . $title;
		$sendCopy = trim($_POST['sendCopy']);
		$body = "Name: $name \nEmail: $email \nTitre: $title \nDescription: $description \nPrix: $price \nCode postal: $codePostal \nVille: $city \n
A integrer dans le fichier annonces.json: \n{\nname: $name,\nemail: $email,\ntitle: $title,\ndescription: $description,\nprice: $price,\ncodePostal: $codePostal,\ncity: $city\n}";
		$headers = 'From: ' .' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

		mail($emailTo, $subject, $body, $headers);
		
        //Autorespond
		$respondSubject = "Merci d'avoir déposé votre annonce sur " . $siteTitle;
		$respondBody = "Votre message à $siteTitle a été envoyé! \n\nNous allons vous répondre aussi vite que possible.";
		$respondHeaders = 'From: ' .' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
		
		mail($email, $respondSubject, $respondBody, $respondHeaders);
		
        // set our boolean completion value to TRUE
		$emailSent = true;
	}
}
?>