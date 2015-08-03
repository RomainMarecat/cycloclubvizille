<?php
$emailTo = 'thomas.difruscia@cycloclubvizille.com';
$siteTitle = 'Cyclo-club Vizille';

error_reporting(E_ALL ^ E_NOTICE); // hide all basic notices from PHP

if (isset($_POST['submitted'])) {
    if (trim($_POST['contactName']) === '') {
        $nameError =  'Vous avez oublié de rentrer votre nom et/ou prénom ou pseudo';
        $hasError = true;
    } else {
        $name = trim($_POST['contactName']);
    }
    if (trim($_POST['contactEmail']) === '') {
        $emailError = 'Vous avez oublié de rentrer votre adresse email.';
        $hasError = true;
    } elseif (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['contactEmail']))) {
        $emailError = "Vous avez rentré une mauvaise adresse email.";
        $hasError = true;
    } else {
        $email = trim($_POST['contactEmail']);
    }
    if (trim($_POST['contactTitle']) === '') {
        $titleError = "Vous avez oublié de rentrer un titre pour l'objet.";
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $title = stripslashes(trim($_POST['contactTitle']));
        } else {
            $title = trim($_POST['contactTitle']);
        }
    }
    if (trim($_POST['contactDescription']) === '') {
        $descriptionError = "Vous avez oublié de rentrer une desciption de l'objet.";
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $description = stripslashes(trim($_POST['contactDescription']));
        } else {
            $description = trim($_POST['contactDescription']);
        }
    }
    if (isset($_FILES['contactImage'])) {
        $file_tmp_name    = $_FILES['contactImage']['tmp_name'];
        $file_name        = $_FILES['contactImage']['name'];
        $file_size        = $_FILES['contactImage']['size'];
        $file_type        = $_FILES['contactImage']['type'];
        $file_error       = $_FILES['contactImage']['error'];
        if ($file_error > 0) {
            $hasError = true;
        }
        //read from the uploaded file & base64_encode content for the mail
        $handle = fopen($file_tmp_name, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));
        $file_attached = true;
    } else {
        $imageError = "Vous avez oublié de rentrer une image de l'objet.";
        // $hasError = true;
        $file_attached = false;
    }
    if (trim($_POST['contactPrice']) === '') {
        $priceError = "Vous avez oublié de rentrer un prix pour l'objet.";
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $price = stripslashes(trim($_POST['contactPrice']));
        } else {
            $price = trim($_POST['contactPrice']);
        }
    }
    if (trim($_POST['contactCodePostal']) === '') {
        $codePostalError = "Vous avez oublié de rentrer votre code postal.";
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $codePostal = stripslashes(trim($_POST['contactCodePostal']));
        } else {
            $codePostal = trim($_POST['contactCodePostal']);
        }
    }
    if (trim($_POST['contactCity']) === '') {
        $cityError = "Vous avez oublié de rentrer votre ville.";
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $city = stripslashes(trim($_POST['contactCity']));
        } else {
            $city = trim($_POST['contactCity']);
        }
    }

    // upon no failure errors let's email now!
    if (!isset($hasError)) {
        $subject = 'Nouveau message de ' . $siteTitle.' pour l\'annonce ' . $title;
        $sendCopy = trim($_POST['sendCopy']);
        $body = "Name: $name \nEmail: $email \nTitre: $title \nDescription: $description \nPrix: $price \nCode postal: $codePostal \nVille: $city \n
A integrer dans le fichier annonces.json: \n{\nname: $name,\nemail: $email,\ntitle: $title,\ndescription: $description,\nprice: $price,\ncodePostal: $codePostal,\ncity: $city\n}";
        // $headers = 'From: ' .' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;
        if ($file_attached) {
            # Mail headers should work with most clients (including thunderbird)
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion()."\r\n";
            $headers .= "From:".$email."\r\n";
            $headers .= "Subject:".$subject."\r\n";
            $headers .= "Reply-To: ".$email."" . "\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=".md5('boundary1')."\r\n\r\n";

            $headers .= "--".md5('boundary1')."\r\n";
            $headers .= "Content-Type: multipart/alternative;  boundary=".md5('boundary2')."\r\n\r\n";

            $headers .= "--".md5('boundary2')."\r\n";
            $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n\r\n";
            $headers .= $message."\r\n\r\n";

            $headers .= "--".md5('boundary2')."--\r\n";
            $headers .= "--".md5('boundary1')."\r\n";
            $headers .= "Content-Type:  ".$file_type."; ";
            $headers .= "name=\"".$file_name."\"\r\n";
            $headers .= "Content-Transfer-Encoding:base64\r\n";
            $headers .= "Content-Disposition:attachment; ";
            $headers .= "filename=\"".$file_name."\"\r\n";
            $headers .= "X-Attachment-Id:".rand(1000, 9000)."\r\n\r\n";
            $headers .= $encoded_content."\r\n";
            $headers .= "--".md5('boundary1')."--";
        } else {
            $headers = 'From: ' .$email. '' . "\r\n" .
            'Reply-To: '. $email. '' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        }

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
