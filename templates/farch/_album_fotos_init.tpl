{if $farch_fancybox_init_profile eq "simple"}
<script type="text/javascript">{literal}
$(document).ready(function() {
	$("a.zoom").fancybox({
		'padding'			:	0,
		'topRatio'			:	0.2,
		'autoSize'			:	true,
		'autoCenter'		:	false,
		'autoResize'		:	true,
		'fitToView'			:	false,
		'scrolling'			:	'no',
		'overlayOpacity'	:	.4,
		'overlayColor'		:	'black',
		'openEffect'		:	'fade',
		'closeEffect'		:	'fade',
		'nextEffect'		:	'none',
		'prevEffect'		:	'none',
		'showCloseButton'	:	true,
		'hideOnOverlayClick':	true,
		'hideOnContentClick':	false,
		'mouseWheel'		:	false,
		helpers: {
				overlay : null
		}
	});

});
{/literal}
</script>
{else}
<script type="text/javascript">{literal}
$(document).ready(function() {
	$("a.zoom").fancybox({
		'padding'			:	0,
		'type'				:	'ajax',
		'topRatio'			:	0.2,
		'autoSize'			:	true,
		'autoCenter'		:	false,
		'autoResize'		:	true,
		'fitToView'			:	false,
		'scrolling'			:	'no',
		'overlayOpacity'	:	.4,
		'overlayColor'		:	'black',
		'openEffect'		:	'fade',
		'closeEffect'		:	'fade',
		'nextEffect'		:	'none',
		'prevEffect'		:	'none',
		'showCloseButton'	:	true,
		'hideOnOverlayClick':	true,
		'hideOnContentClick':	false,
		'mouseWheel'		:	false,
		helpers: {
			overlay : null
		},
		'afterShow'			:	function () {
			$(".fancybox-nav").css("height", $("#fancybox_openimage_img").css("height"));
		}
	});

});
{/literal}
</script>
{/if}