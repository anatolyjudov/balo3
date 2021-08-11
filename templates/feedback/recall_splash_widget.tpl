<div id="recall-popup" class="white-popup mfp-hide">
	<div class="formContainer">
		<h3>{#lang_feedback_form_title_widget#}</h3>
		<form name="ffw" action="{$base_path}/recall/send/" method="post"><input type="hidden" name="{$antispam_field}" value="">
			<div class="formLine clearfix">
				<label for="fio">{#lang_feedback_form_field_person#}</label>
				<input name="person" type="text" id="fio"/>
			</div>
			<div class="formLine clearfix">
				<label for="phone">{#lang_feedback_form_field_phone#}</label>
				<input name="phone" type="text" id="email"/>
			</div>
			{*
			<div class="formLine clearfix">
				<label for="email">{#lang_feedback_form_field_email#}</label>
				<input name="email" type="text" id="email"/>
			</div>*}
			<div class="formLine clearfix">
				<label class="fullSize" for="comment">{#lang_feedback_form_field_text#}</label>
				<textarea name="text" resize="off"></textarea>
			</div>
			<input type="submit" value="{#lang_feedback_form_button#}" class="button blue"/>
		</form>
		<script type="text/javascript">{$antispam_func_name}('ffw');</script>
	</div>
	<p class="bottomText">{#lang_feedback_form_bottom_note#}</p>
	<div class="mfp-close"></div>
</div>