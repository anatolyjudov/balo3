<!DOCTYPE html>
<html>
<head>
	{include file="$templates_path/common/common_title.tpl"}
</head>
<body ontouchstart="">

	{capture name=common_top_menu_code}
	{balo3_widget family="menus" widget="menus_show" menu_block_id=23 menu_block_template="topmenu.tpl" menu_cache="off"}
	{/capture}

	{capture name=common_main_menu_code}
	{balo3_widget family="menus" widget="menus_show" menu_block_id=24 menu_block_template="mainmenu.tpl" menu_cache="off"}
	{/capture}

	{include file="$templates_path/common/common_head.tpl" common_head_features="typical"}

	<section class="textPage buyPageTop">
		<div class="pageContainer clearfix">
			<div class="contentPage">

				<blockquote style="margin-top: 70px;">
				<div class="inset_media">
				<img src="/images/face_art_3.png">
				</div>
				<q>«Бывает, что мы получаем пять запросов на Гидру в день. Мы поняли, что это не предел и разработали специальную анкету. Ответьте на вопросы анкеты, выберите опции Гидры — и мы подготовим коммерческое предложение.»</q>
				<p>Роман Артюх, руководитель отдела продаж,
				<br>Латера</p>
				</blockquote>

			</div>
		</div>
	</section>

	<section class="buySteps">

			<div class="pageContainer">
				<h1>Как купить Гидру</h1>
			</div>

			<div class="buyStep buyStepFirst">
				<div class="pageContainer">
					<div class="buyStepTime">Сроки зависят от вас<br>(в среднем ~ 1 неделя)</div>
					<div class="buyStepIcon"><img src="/images/buy/icon1.png" alt=""></div>
					<div class="buyStepNote"><a href="/buy/#buyForm" class="button blue">Начать заполнение</a><br>Предварительная форма<br>размещена ниже</div>
					<div class="buyStepDescription">
						<h2>Опросник</h2>
						<p>Пожалуйста, заполните нашу анкету как можно более подробно. При необходимости, привлекайте технического специалиста.</p>
					</div>
				</div>
			</div>

			<div class="buyStep">
				<div class="pageContainer">
					<div class="buyStepTime">не более 2-3 дней</div>
					<div class="buyStepIcon"><img src="/images/buy/icon2.png" alt=""></div>
					<div class="buyStepNote"></div>
					<div class="buyStepDescription">
						<h2>Подробности</h2>
						<p>Мы связываемся с вами для уточнения подробностей внедрения.</p>
					</div>
				</div>
			</div>

			<div class="buyStep">
				<div class="pageContainer">
					<div class="buyStepTime">от 2 до 5 дней</div>
					<div class="buyStepIcon"><img src="/images/buy/icon3.png" alt=""></div>
					<div class="buyStepNote"></div>
					<div class="buyStepDescription">
						<h2>Коммерческое предложение</h2>
						<p>Вы получаете коммерческое предложение на внедрение биллинга.</p>
					</div>
				</div>
			</div>

			<div class="buyStep">
				<div class="pageContainer">
					<div class="buyStepTime">Сроки по этому этапу<br>зависят от вас</div>
					<div class="buyStepIcon"><img src="/images/buy/icon4.png" alt=""></div>
					<div class="buyStepNote">Шаблоны документов:<br><a href="">Лицензионный договор</a><br><a href="">Договор на внедрение</a></div>
					<div class="buyStepDescription">
						<h2>Договор</h2>
						<p>Вы заключаете с нами договор и оплачиваете его.</p>
					</div>
				</div>
			</div>

			<div class="buyStep buyStepImportant">
				<div class="pageContainer">
					<div class="buyStepTime">от 2 до 4 месяцев</div>
					<div class="buyStepIcon"><img src="/images/buy/icon5.png" alt=""></div>
					<div class="buyStepNote"></div>
					<div class="buyStepDescription">
						<h2>Развёртывание и настройка</h2>
						<p>Мы проводим развертывание и настройку Гидры, выполняем миграцию данных из старого биллинга.</p>
					</div>
				</div>
			</div>

			<div class="buyStep">
				<div class="pageContainer">
					<div class="buyStepTime">Сроки зависят от вас<br>(от недели до месяца)</div>
					<div class="buyStepIcon"><img src="/images/buy/icon6.png" alt=""></div>
					<div class="buyStepNote"></div>
					<div class="buyStepDescription">
						<h2>Тестирование</h2>
						<p>Вы проверяете нашу работу.</p>
					</div>
				</div>
			</div>

			<div class="buyStep buyStepFinal">
				<div class="pageContainer">
					<div class="buyStepTime">1 день</div>
					<div class="buyStepIcon"><img src="/images/buy/icon7.png" alt=""><div class="_buyStepVisual"></div></div>
					<div class="buyStepNote">Рекомендуем шампанское<br><a href="">Veuve Clicquot Ponsardin Brut</a></div>
					<div class="buyStepDescription">
						<h2>Запуск!</h2>
						<p>Гидра запускается в эксплуатацию.</p>
					</div>
				</div>
			</div>

	</section>


	<section class="buyForm">
		<div class="pageContainer clearfix">

		<h1>Предварительные данные</h1>

		{include file="$templates_path/common/antispam_function.tpl"}

		<form class="single_form buyForm fbForm{if $fbform_single_page == true} fbFormSingle{/if}" action="{$base_path}/buy/send/" method="POST" enctype="multipart/form-data" onSubmit="" name="ff"><input type="hidden" name="{$antispam_field}" value="">

		<div class="field_row"><label>Имя</label><input style="width: 360px;" class="" type="text" name="name" id="name" value="{$buy_info.name}" /></div>

		<div class="field_row"><label>Фамилия</label><input style="width: 360px;" class="" type="text" name="surname" id="surname" value="{$buy_info.surname}" /></div>

		<div class="field_row"><label>Компания</label><input style="width: 360px;" class="" type="text" name="company" id="company" value="{$buy_info.company}" /></div>

		<div class="field_row"><label>Телефон</label><input style="width: 260px;" class="" type="text" name="phone" id="phone" value="{$buy_info.phone}" /></div>

		<div class="field_row"><label>Адрес e-mail</label><input style="width: 260px;" class="" type="text" name="email" id="email" value="{$buy_info.email}" /></div>

		<div class="submitRow">
			<input class="button blue" type="submit" value="продолжить заполнение">
			<p>(Вы будете перенаправлены на специальную страницу с анкетой)</p>
		</div>

		<input type="hidden" id="localtimeGMT" name="localtimeGMT" value="">
		<script type="text/javascript">x = new Date(); document.getElementById('localtimeGMT').value =  "+" + x.getTimezoneOffset()/(-60) + " GMT";</script>
		</form>

		<script type="text/javascript">
		{$antispam_func_name}('ff');
		greyIt('texts');
		</script>


		</div>
	</section>


	{include file="$templates_path/common/common_foot.tpl"}


</body>
</html>