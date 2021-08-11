<?php

function feedback_probably_spam() {
	global $antispam_field, $antispam_value;

	if (isset($_POST[$antispam_field]) && ($_POST[$antispam_field]==$antispam_value)) {
		return false;
	}

	return true;
}

?>