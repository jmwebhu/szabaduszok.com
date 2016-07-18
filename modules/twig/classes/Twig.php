<?php

class Twig extends Kohana_Twig
{
	public static function getHtmlFromTemplate($template, $context)
	{
		$twig = Twig::instance();
		$html = $twig->loadTemplate($template)->render((array) $context);

		return $html;
	}
}
