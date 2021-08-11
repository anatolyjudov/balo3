<h1>{#authors_list#}</h1>

{if $errmsg}<p style="color:red;">{$errmsg}</p>{/if}

{if $authors_list ne ""}

<div class="authors_list">

{foreach from=$authors_list item=author}
<div class="author">
	<div class="author_name"><a href="{$base_path}/authors/{$author.id}/">{$author.sirname} {$author.name} {$author.patronymic}</a></div>
	{if $author.short_text ne ''}<div class="author_short_text">{$author.short_text}</div>{/if}
</div>
{/foreach}

</div>

{else}

<h3>{#authors_admin_no_authors#}</h3>

{/if}
