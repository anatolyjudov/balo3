<script language="javascript">

function show_add_dialog() {ldelim}

	window.open('./new/?path={$path}', 'add', 'width=350,height=230,resizeable=no,toolbar=no,status=no,dependent=yes');

{rdelim}

function show_delete_dialog(filenam) {ldelim}

	window.open('./delete/?fullname={$path}' + filenam, 'delete', 'width=350,height=230,resizeable=no,toolbar=no,status=no,dependent=yes');

{rdelim}


function renew() {ldelim}
	window.location.reload();
{rdelim}


</script>
<h1>{#lang_files_admin_filebrowser_title#}</h1>

<div class="additional">
</div>

<div class="content">

<table cellspacing=0 class="data" style="width: auto;">
<tr class="head">
<td></td>
<td>{#lang_files_admin_filebrowser_head_name#}</td>
<td>{#lang_files_admin_filebrowser_head_size#}</td>
<td>{#lang_files_admin_filebrowser_head_date#}</td>
<td></td>
</tr>
{if $parent!=""}
<tr class="nhl">
<td>D</td>
<td><a href="./?path={$parent}">..</a></td>
<td></td>
<td></td>
<td></td>
</tr>
{/if}

{if count($files_info)>0}
{foreach from=$files_info item=fi}
{if $fi.type=="dir"}<tr class="nhl">{else}<tr class="hl">{/if}
<td>{if $fi.type=="dir"}D{/if}</td>
<td style="padding-right: 20px;">{if $fi.type=="dir"}<a href="./?path={$path}{$fi.filename|escape:'url'}/">{$fi.filename}</a>{else}<a href="{$base_path}{$path}{$fi.filename|escape:'url'}" target="view">{$fi.filename}</a>{/if}</td>
<td style="padding-right: 20px;">{$fi.size}</td>
<td>{$fi.last_modify|date_format:"%H:%M:%S %d-%m-%Y"}</td>
<td>
<div class="actions">
{if $fi.type=="file"}<a href="./delete/" onClick="show_delete_dialog('{$fi.filename}'); return false;"><img src="{$pics_path}/delete.png" alt="{#lang_admin_remove_alt_text#}"></a>{/if}
</div>
</td>
</tr>
{/foreach}
{/if}

</table>

<p style="margin-top: 20px;">
<img src="{$pics_path}/add.png" alt="" style="position: relative; top: 3px; margin-right: 3px;"><a href="./new/" onClick="show_add_dialog(); return false;">{#lang_admin_addfile_btn_text#}</a>
</p>

</div>