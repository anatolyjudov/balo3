<div id="list">
{foreach from=$mod_fotos_list key=foto_id item=foto_info}
<div
{if count($mod_fotos_tags.fotos[$foto_id]) > 0}class="{foreach from=$mod_fotos_tags.fotos[$foto_id].tags item=fototag_id}tag_{$fototag_id} {/foreach}"{/if}

style="width: {$foto_info.TECH_INFO.previews.small[0]}px; height: {$foto_info.TECH_INFO.previews.small[1]}px;"
>
<a id="{$foto_id}" rel="group" href="{$arein_foto_params.folders.link}/{$arein_foto_params.previews.main.prefix}{$foto_id}.{$foto_info.TECH_INFO.extension}" title="{$foto_info.FOTO_TITLE|escape:'html'}"><img src="{$arein_foto_params.folders.link}/{$arein_foto_params.previews.small.prefix}{$foto_id}.{$foto_info.TECH_INFO.extension}" border=0 alt="{$foto_info.FOTO_TITLE|escape:'html'}"></a></div>
{/foreach}
</div>
{literal}
<script type="text/javascript">

$(document).ready(function() {

	$("#list div a").fancybox({
		'overlayOpacity'	:	0.7,
		'overlayColor'		:	'#FFF',
		'titlePosition'		:	'inside',
		'transitionIn'		:	'none',
		'transitionOut'		:	'none',
		'autoScale'			:	'true',
		'speedIn'			:	'50',
		'speedOut'			:	'50',
		'changeSpeed'		:	'50',
		'changeFade'		:	'50',
		'onComplete'		:	function(a, b, c) {
			window.location.hash = "f"+a[b].id;
		},
		'onClosed'			:	function() {
			window.location.hash = "!";
		}
	});

	if (document.URL.indexOf("#") != -1) {
		h = window.location.hash;
		if (h.match(/^#t\d+$/i)) {
			showfotos('tag_'+h.substr(2));
		}
		if (h.match(/^#f\d+$/i)) {
			$("#"+h.substr(2)).click();
		}
	}

});

function showfotos(t) {
	if (t == 'all') {
		window.location.hash = "";
		$("#list div img").show();
		$("#list div a").attr("rel","group");
	} else {
		window.location.hash = "#t"+t.substr(4);
		$("#list div."+t+" img").show();
		$("#list div."+t+" a").attr("rel","group");
		$("#list div:not(."+t+") img").hide();
		$("#list div:not(."+t+") a").attr("rel","hide");
	}
}

</script>
{/literal}