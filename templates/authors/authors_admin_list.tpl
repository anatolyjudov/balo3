<h1>{#authors_admin_list#}</h1>

<div class="additional">
{include file="authors/authors_admin_additional.tpl"}
</div>

<div class="content">

{if $errmsg}<p style="color:red;">{$errmsg}</p>{/if}

{if $authors_list ne ""}

<table class="ctrl sections_manage" style="width: 100%;">
<tr class="head">
<td style="width: 50px;">{#authors_admin_id#}</td>
<td style="width: 150px;">{#authors_admin_sirname#}</td>
<td style="width: 150px;">{#authors_admin_name#}</td>
<td style="width: 150px;">{#authors_admin_patronymic#}</td>
<td style="width: 400px;">{#authors_admin_short_text#}</td>
<td></td>
</tr>

{foreach from=$authors_list item=author}
<tr class="{cycle values='odd,nodd'}">
<td>{$author.id}</td>
<td>{$author.sirname}</td>
<td>{$author.name}</td>
<td>{$author.patronymic}</td>
<td>{$author.short_text}</td>
<td><a href="{$base_path}/admin/authors/edit/?author_id={$author.id}"><img src="{$pics_path}/edit.png" border=0 alt="{#lang_admin_edit_alt_text#}"></a><a href="{$base_path}/admin/authors/delete/?author_id={$author.id}"><img src="{$pics_path}/delete.png" border=0 alt="{#lang_admin_remove_alt_text#}"></a></td>
</tr>
{/foreach}

</table>

{else}

<h3>{#authors_admin_no_authors#}</h3>

{/if}


</div>