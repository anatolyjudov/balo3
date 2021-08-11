{foreach from=$news_list item=news key=id_new name=modannounce}

<div class="announce-popup" style="display: none;" data-announce-id="{$news.id_new}">
	<div class="announce-wrapper as_plate">
		<div class="announce-title">
			{$news.name}
		</div>
		<div class="announce-text">
			{$news.short_text}
		</div>

		<div class="announce-actions">
			<a class="announce-close" href="/">Закрыть окно</a>
		</div>
	</div>
</div>

{/foreach}