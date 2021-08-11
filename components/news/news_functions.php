<?php

if(!function_exists(news_sa))
{
	function news_sa($str)
	{
		return (int)preg_replace('`\D`', '', $str);
	}
}

?>