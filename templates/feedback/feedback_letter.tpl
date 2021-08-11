С сайта {$common_domain}{$base_path}/, в {$smarty.now|date_format:'%H:%M %Y-%m-%d'} отправлено письмо:

{$feedback_info.subject}

{$feedback_info.text}

Контактная информация
{if $feedback_info.phone ne ""}Телефон: {$feedback_info.phone}
{/if}{if $feedback_info.email ne ""}Эл. почта: {$feedback_info.email}
{/if}{if $feedback_info.person ne ""}Контактное лицо: {$feedback_info.person}
{/if}

---

{if $feedback_info.email ne ""}Для ответа по указанному адресу нажмите "ответить" в вашей почтовой программе или почтовом сервисе.
{/if}