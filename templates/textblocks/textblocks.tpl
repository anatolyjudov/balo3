<h1>{#lang_tb_admin_manage_title#}</h1>

{if $superuser eq true}
<div class="additional">
<img src="{$pics_path}/add.png" alt="" style="position: relative; top: 3px; margin-right: 3px;"><a href="new/">{#lang_tb_admin_manage_links_add#}</a>
</div>
{/if}

<div class="content">
<table class="data" cellspacing="0">
<tr class="head">
{*<td>{#lang_tb_admin_table_head_id#}</td>*}
<td>{#lang_tb_admin_table_head_path#}</td>
<td>{#lang_tb_admin_table_head_identify#}</td>
<td>{#lang_tb_admin_table_head_title#}</td>
<td width="38%">{#lang_tb_admin_table_head_comment#}</td>
<td></td>
</tr>
{foreach from=$textblocks item=block}
<tr class={cycle values="hl, nhl"}>
{*<td>{$block.ID}</td>*}
<td>{$block.PATH}</td>
<td>{$block.NAME}</td>
<td>{$block.TITLE|strip_tags}</td>
<td>{$block.COMMENT}</td>
<td><a href="edit/?id={$block.ID}"><img src="{$base_path}/images/edit.png" alt="{#lang_admin_edit_alt_text#}"></a>
{if $superuser eq true}<a href="delete/?id={$block.ID}"><img src="{$base_path}/images/delete.png" alt="{#lang_admin_remove_alt_text#}"></a>{/if}</td>
</tr>
{/foreach}
</table>

</div>