<script type="text/javascript">
function {$antispam_func_name|default:'fckspambots'}(formname)
{ldelim}
	obj = document.forms[formname].elements['{$antispam_field}'];
	obj.value = '{$antispam_value}';
{rdelim}
</script>