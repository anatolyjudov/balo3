<h1>Создать лот в разделе <em>{$sections_info.list[$section_id].SECTION_NAME}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

<form method="POST" action="{$base_path}/admin/catalog/sections/{$section_id}/goods/new/add/" enctype="multipart/form-data">

	<table class="ctrl" cellspacing=0 cellpadding=4>

		<tr class="striked">
		<td valign=top><b>Название лота</b></td>
		<td>
		<input type="text" name="title" class="multilang" value="{$good_info.TITLE|escape:'html'}" style="width: 450px;">
		</td>
		</tr>

		<tr>
		<td></td>
		<td><input type="submit" value="Создать" style="font-size: 18px;"></td>
		</tr>

	</table>

</form>

</div>