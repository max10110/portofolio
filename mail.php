<?php

//session_start();
//if(session_id() != $_POST['sid']) die('Access_denied');
 
$ext = substr($_FILES['bid_file']['name'], 1 + strrpos($_FILES['bid_file']['name'], "."));
$ext = strtolower($ext);

$valid_ext = array('jpg', 'jpeg', 'png',  'gif', 'bmp', 'pdf', 'doc', 'docx', 'xls', 'rar', 'zip'); // допустимые расширения
if(in_array($ext, $valid_ext)){
    $filename = time().'.'.$ext; 
    $path_file = './uploads/'.$filename;
    if(($_FILES['bid_file']['size'] != 0 and $_FILES['bid_file']['size']<=20000000))
    {
    if(!copy($_FILES['bid_file']['tmp_name'], $path_file)){
        echo 'Файл не загружен. Повторите попытку';
    }else{
        
        echo 'true#%#'.$filename; // Возврат статуса загрузки и имени файла
    }
    }else{
    echo 'Размер файла не должен превышать 200Кб.';
}
}else{
    echo 'Недопустимый формат файла.';
}

$method = $_SERVER['REQUEST_METHOD'];

//Script Foreach
$c = true;
if ( $method === 'POST' ) {

	$project_name = trim($_POST["project_name"]);
	$admin_email  = trim($_POST["admin_email"]);
	$form_subject = trim($_POST["form_subject"]);

	foreach ( $_POST as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" ) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
} else if ( $method === 'GET' ) {

	$project_name = trim($_GET["project_name"]);
	$admin_email  = trim($_GET["admin_email"]);
	$form_subject = trim($_GET["form_subject"]);

	foreach ( $_GET as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" ) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
}

$message = "<table style='width: 100%;'>$message</table>";

function adopt($text) {
	return '=?UTF-8?B?'.Base64_encode($text).'?=';
}

$headers = "MIME-Version: 1.0" . PHP_EOL .
"Content-Type: text/html; charset=utf-8" . PHP_EOL .
'From: '.adopt($project_name).' <'.$admin_email.'>' . PHP_EOL .
'Reply-To: '.$admin_email.'' . PHP_EOL;

mail($admin_email, adopt($form_subject), $message, $headers );

include("amocrm_api.php");
