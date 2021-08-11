<h1>{#lang_popups_admin_manage_title#}</h1>

<div class="content">

{if count($popups_list) > 0}
<form action="{$base_path}/admin/popups/save/" method="POST">
<table class="ctrl">
	<thead>
		<tr class="head">
			<td>#</td>
			<td>Окно</td>
			<td>Приоритет</td>
			<td>Показывать?</td>
			<td></td>
		</tr>
	</thead>
	<tbody>
		{foreach from=$popups_list key=id item=popup name=popupsloop}
		<tr class="{cycle values='odd,nodd'}">
			<td>{$id}</td>
			<td>{$popup.COMMENT}</td>
			<td>
				<input type="text" size="5" value="{$popup.PRIORITY|escape:'html'}" name="priority_{$id}" />
			</td>
			<td>
				<input type="checkbox" {if $popup.IS_HIDDEN==0}checked{/if} name="is_active_{$id}" />
			</td>
			<td>
				{if isset($popup_current.ID) && $popup_current.ID == $id}<b>текущий</b>{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2"></td>
			<td colspan="3"><input type="submit" value="{#lang_admin_save_btn_text#}"></td>
		</tr>
	</tfoot>
</table>
</form>
{/if}

</div>