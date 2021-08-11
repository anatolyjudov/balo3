<script type="text/javascript">
{literal}
function cleanIt(idd) {
	f = document.getElementById(idd);
	if (f.value == '{/literal}{#lang_feedback_form_field_textarea#}{literal}') {
		f.value = "";
		f.style.color = "#333333";
		f.style.fontStyle = "normal";
	}
}
function greyIt(idd) {
	f = document.getElementById(idd);
	if (f.value == '{/literal}{#lang_feedback_form_field_textarea#}{literal}') {
		f.style.color = "#666666";
		f.style.fontStyle = "italic";
	}
}
{/literal}
</script>

{include file="$templates_path/common/antispam_function.tpl"}

<h1>{#lang_feedback_form_title#}</h1>

<div class="feedback_form">

	<p>{#lang_feedback_form_top_note#}</p>

	{if $errmsg}<p style="color: red;">{#lang_feedback_form_error_prefix#} {$errmsg}</p>{/if}

	<form class="single_form" action="{$base_path}/feedback/send/" method="post" enctype="multipart/form-data" name="ff"><input type="hidden" name="{$antispam_field}" value="">

		<div class="field_row">
			<label>{#lang_feedback_form_field_subject#}</label>
			<input type="text" class="subject" name="subject" id="subject" value="{strip}
			{if $feedback_info.subject ne ''}
				{$feedback_info.subject|escape:'html'}
			{else}
				{if $subject_good_info.TITLE ne ''}
					{if $ml_current_language_id == 0}
						{if $subject_type eq 'discuss'}Обсудить предмет: {/if}
						{if $subject_type eq 'ask'}Интересует цена на предмет: {/if}
						{if $subject_type eq 'buy'}Хочу приобрести: {/if}
					{else}
						{if $subject_type eq 'discuss'}Discuss the item: {/if}
						{if $subject_type eq 'ask'}Interested in the price of the item: {/if}
						{if $subject_type eq 'buy'}Want to purchase: {/if}
					{/if}
					{$subject_good_info.TITLE|escape:'html'}
				{/if}
			{/if}{/strip}" />
		</div>

		<div class="field_row">
			<label>{#lang_feedback_form_field_person#}</label>
			<input type="text" class="person" name="person" id="person" value="{$feedback_info.person|escape:'html'}" >
		</div>

		<div class="field_row">
			<label>{#lang_feedback_form_field_phone#}</label>
			<input type="text" class="phone" name="phone" id="phone" value="{$feedback_info.phone|escape:'html'}" />
		</div>

		<div class="field_row">
			<label>{#lang_feedback_form_field_email#}</label>
			<input type="text" class="email" name="email" id="email" value="{$feedback_info.email|escape:'html'}" />
		</div>

		<div class="field_row">
			<label>{#lang_feedback_form_field_text#}</label>
			{if $feedback_info.text != ''}
				<textarea name="text" id="texts" class="comment">{$feedback_info.text}</textarea>
			{else}
				{if $subject_type eq ''}
					<textarea name="text" id="texts" class="comment">{$feedback_info.text}</textarea>
				{/if}
				{if $subject_type eq 'discuss'}
<textarea name="text" id="texts" class="comment">{if $ml_current_language_id == 0}Обсудить предмет{else}Discuss the item{/if}: {$subject_good_info.TITLE}

---
{$common_domain}{$base_path}{$catalog_collection_uri}/{$subject_good_info.GOOD_ID}
</textarea>
				{/if}
				{if $subject_type eq 'ask'}
<textarea name="text" id="texts" class="comment">{if $ml_current_language_id == 0}Интересует цена на предмет{else}Interested in the price of the item{/if}: {$subject_good_info.TITLE}

---
{$common_domain}{$base_path}{$catalog_collection_uri}/{$subject_good_info.GOOD_ID}
</textarea>
				{/if}
				{if $subject_type eq 'buy'}
<textarea name="text" id="texts" class="comment">{if $ml_current_language_id == 0}Хочу приобрести предмет{else}Want to purchase{/if}: {$subject_good_info.TITLE}

---
{$common_domain}{$base_path}{$catalog_collection_uri}/{$subject_good_info.GOOD_ID}
</textarea>
				{/if}
			{/if}
		</div>

		<div class="submit_row">
			<button type="submit">{#lang_feedback_form_button#}</button>
		</div>

</div>

</form>



<p style="margin-top: 18px;">{#lang_feedback_form_bottom_note#}</p>

<script type="text/javascript">
{$antispam_func_name}('ff');
</script>
