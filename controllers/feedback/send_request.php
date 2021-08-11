<?php

require_once("$entrypoint_path/components/feedback/feedback_init.php");
do {

	// принимаем
	if (isset($_POST['text'])) {
		$feedback_info['text'] = $_POST['text'];
	} else {
		$feedback_info['text'] = "";
	}	
	if (isset($_POST['phone'])) {
		$feedback_info['phone'] = $_POST['phone'];
	} else {
		$feedback_info['phone'] = "";
	}	
	if (isset($_POST['person'])) {
		$feedback_info['person'] = $_POST['person'];
	} else {
		$feedback_info['person'] = "";
	}
	if ( isset($_POST['email'])) {
		$feedback_info['email'] = $_POST['email'];
	} else {
		$feedback_info['email'] = "";
	}
	if ( isset($_POST['subject'])) {
		$feedback_info['subject'] = $_POST['subject'];
	} else {
		$feedback_info['subject'] = "";
	}

	// сохраняем полученную информацию перед проверками
	$smarty->assign_by_ref("feedback_info", $feedback_info);

	// проверяем на спам
	if (feedback_probably_spam()) {
		$smarty->assign("errmsg", $balo3_texts['feedback']['PROBABLY_SPAM']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}

	// наличие вопроса
	if ($feedback_info['text'] == "") {
		$smarty->assign("errmsg", $balo3_texts['feedback']['NO_TEXT']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}

	// корректность телефона
	if ($feedback_info['phone'] == "") {
		$smarty->assign("errmsg", $balo3_texts['feedback']['EMPTY_PHONE']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}
	if ( !preg_match("/^[\d\s\(\)\-\+]+$/", $_POST['phone']) ) {
		$smarty->assign("errmsg", $balo3_texts['feedback']['BAD_PHONE']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}
	

	// корректность мыла
	if (!preg_match("/^\w[\w\d\-_\.]*@[\w\d_\-\.]+\.\w{2,6}$/", $_POST['email'])) {
		$smarty->assign("errmsg", $balo3_texts['feedback']['BAD_EMAIL']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}

	// наличие какого-нибудь контакта
	/*
	if ( (trim($feedback_info['email']) == "") && (trim($feedback_info['phone']) == "") ) {
		$smarty->assign("errmsg", $balo3_texts['feedback']['NO_CONTACTS']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}
	*/

	// Проверки закончены, можно отправлять письмо

	// Текст письма
	$mail_body = $smarty->fetch("$templates_path/feedback/feedback_letter.tpl");

	// Хедеры
	$headers = "From: " . $feedback_from . "\r\n";
	if ($feedback_info['email'] != "") {
		$headers .= "Reply-to: " . $feedback_info['email'] . "\r\n";
		/*
		if ($feedback_info['person'] != "") {
			$headers .= "Reply-to: " . $feedback_info['person'] . " <" . $feedback_info['email'] . ">\r\n";
		} else {
			$headers .= "Reply-to: " . $feedback_info['email'] . "\r\n";
		}
		*/
	}
	$headers .= "Content-Type: text/plain; charset=\"utf-8\"\n";
	if ($feedback_info['subject'] != "") {
		$feedback_subject = "=?utf-8?b?".base64_encode($feedback_info['subject'])."?=";
	} else {
		$feedback_subject = "=?utf-8?b?".base64_encode($feedback_subj)."?=";
	}

	if (!@mail($feedback_to, $feedback_subject, $mail_body, $headers)) {
		/*
		echo "<pre>";
		echo $headers;
		echo $mail_body;
		echo "</pre>";
		*/
		$smarty->assign("errmsg", $balo3_texts['feedback']['EMAIL_NOT_SENT']);
		balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));
		break;
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/feedback/send_request.tpl"));


} while (false);

?>