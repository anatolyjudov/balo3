<h1>Доступ запрещён</h1>

{include file="$templates_path/users/enter.tpl"}

{if $common_console_log_errors}
<script type="text/javascript">
{foreach from=$balo3_errors item=balo3_error}
console.log("{$balo3_error.error_message|escape:'html'}");
{/foreach}
</script>
{/if}