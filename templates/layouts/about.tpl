{include file="$templates_path/common/common_head.tpl"}

<main class="_c">

	<aside>
		<hr>
		{balo3_widget family="catalog" widget="catalog_menu"}
	</aside>

	<section class="main_section">

		<div class="about_salon">

			<div class="about_salon_text">
				{balo3_placeholder name="main"}
			</div>

		</div>

		<div class="about_map clearfix">
			<div id="map" style="height: 460px;">
			<h1>{#lang_about_address_title#}</h1>
			<div class="about_contacts">
				{balo3_widget family="textblocks" widget="textblocks_show" textblock_name="about_contacts" textblock_template="clean.tpl"}
			</div>
			<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Aywvmu-rafo0a4m8NLzS0CKgHHZ0Vmw50&amp;width=67%&amp;height=400px&amp;lang=ru_RU&amp;scroll=true"></script>
			</div>
		</div>

		<div class="about_gladtosee">

			<hr class="about_hr">

			<h1>{#lang_about_staff_title#}</h1>
			{balo3_widget family="farch" widget="show_album_by_id" album_id=5 template="show_album_by_id.tpl"}

		</div>

		<div class="messengers_area" id="messengers">

			<hr class="about_hr">

			<div class="messengers_links">

				<h1>{#lang_about_messengers_title#}</h1>

				{balo3_widget family="textblocks" widget="textblocks_show" textblock_name="about_messengers" textblock_template="clean.tpl"}

			</div>

		</div>

	</section>

</main>

{include file="$templates_path/common/common_foot.tpl"}