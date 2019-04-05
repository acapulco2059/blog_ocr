<?php

class View
{

	public static function makeHtml($data, $template){
		return str_replace(
      array_keys($data),
      $data,
      file_get_contents("template/$template.html")
    );
	}

	public static function makeLoopHtml($data, $template){
		$html = "";
		foreach ($data as $value) {
			$html .= self::makeHtml($value, $template);
		}
		return $html;
	}

}
