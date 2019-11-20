<?php
	require_once __DIR__ . "/../PHPMailer/PHPMailerAutoload.php";

	$Mailer = new PHPMailer();
	
	// define que será usado SMTP
	// $Mailer->SMTPDebug = 2;

	// define que será usado SMTP
	$Mailer->IsSMTP();
	 
	// envia email HTML
	$Mailer->isHTML(true);

	// codificação UTF-8, a codificação mais usada recentemente
	// $Mailer->Charset = 'UTF-8';
	$Mailer->SMTPSecure = 'tls';  

	// Configurações do SMTP
	$Mailer->SMTPAuth = true;
	
	// Correção necessária para execução em VPS...............
		// $Mailer->SMTPOptions = array(
		//     'ssl' => array(
		//         'verify_peer' => false,
		//         'verify_peer_name' => false,
		//         'allow_self_signed' => true
		//     )
		// );
	// Fim Coreção -------------------------------------------

	
	 
	// $Mailer->SMTPSecure = 'ssl';
	$Mailer->Host = '';
	$Mailer->Port = 587;

	$Mailer->Username = '';
	$Mailer->Password = '';