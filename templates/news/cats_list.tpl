<h2>Редактировать категории новостей (все сразу)</h2>
<small>Пожалуйста, будьте внимательнее при введении путей URI.</small><br><br>
<form action="./modify/" method="post">

{section loop=$cats name=i}
	<input type="text" class="sto60" name="uri{$cats[i][0]}" value="{$cats[i][3]}"> URI<br>
	<input type="text" class="sto60" name="nam{$cats[i][0]}" value="{$cats[i][1]}"> Title (<a href="./delete/?id={$cats[i][0]}">delete?</a>) <a href="{$base_path}{$cats[i][3]}">Перейти &gt;&gt;</a><br>
	<br>
{/section}

	<input type="submit" class="knopke" value="Принять изменения">
</form>

<h2>Создать новую категорию новостей</h2>
<form action="./add/" method="post">
	<input type="text" class="sto60" name="new_uri" value=""> URI<br>
	<input type="text" class="sto60" name="new_nam" value=""> Title<br>
	<br>
	<input type="submit" class="knopke" value="Готово">
</form>