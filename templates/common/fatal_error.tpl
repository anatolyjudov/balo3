<h1>Произошла непредвиденная ошибка,<br>наши специалисты уже занимаются её устранением.</h1>

{if $common_console_log_errors}
<script type="text/javascript">
{foreach from=$balo3_errors item=balo3_error}
console.log("{$balo3_error.error_message|escape:'html'}");
{/foreach}
</script>
{/if}