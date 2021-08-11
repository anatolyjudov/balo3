<h1>Подверждение удаления</h1>
<h1 class="sub"></h1>

<form action="./remove/" method="post">
	<input type="hidden" name="confirmed" value="1">
	<input type="hidden" name="id" value="{$id}">
	<p>Вы уверены, что хотите удалить категорию новостей "{$cat[0][1]}"?<br>
	<br>
	<a href="../">Нет</a><br>
	<br>
	<input type="submit" value="Да" class="knopke">
	</p>
</form>