<h1>Ошибка! Страницы с таким адресом не существует</h1>
<p><a href="{$base_path}/">Перейти на главную</a></p>

{if $common_console_log_errors}
<script type="text/javascript">
{foreach from=$balo3_errors item=balo3_error}
console.log("{$balo3_error.error_message|escape:'html'}");
{/foreach}
</script>
{/if}