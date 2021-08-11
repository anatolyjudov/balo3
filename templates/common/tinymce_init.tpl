<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="{$base_path}/js/lib/tinymce_3.5.8_jquery/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">

{literal}

	tinyMCE.init({
		language : 'en',
		mode: "exact",
		elements : "{/literal}{$tinymce_elements}{literal}",
		theme : "advanced",
		content_css : "{/literal}{$base_path}/css/tinymce_edit.css?{math equation='rand(100,x)' x=999}{literal}",
		plugins : "table,inlinepopups",
		apply_source_formatting : true,
		forced_root_block : 'p',
		force_p_newlines : true,
		theme_advanced_toolbar_location : "top",
		theme_advanced_blockformats : "p,div,h1,h2,h3,h4",
		theme_advanced_buttons1 : "formatselect, removeformat, separator, image, link, unlink, bold, italic, underline, separator, justifyleft, justifycenter, justifyright, separator, bullist, numlist, separator, fontselect, fontsizeselect, forecolor, separator, code",
		theme_advanced_buttons2 : "tablecontrols",
		theme_advanced_buttons3 : "",
		protect: [
			/<\?map.*?\?>/g // Protect php code
		]
	});

{/literal}

</script>
<!-- /tinyMCE -->