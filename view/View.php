<?php

/**
 * 
 */
class View
{
	
	// function __construct(argument)
	// {
	// 	# code...
	// }

	public function makeHtml($data, $template){
		return str_replace(
      array_keys($data),
      $data,
      file_get_contents("template/$template.html")
    );
	}

	public function makeLoopHtml($data, $template){
		$html = "";
		foreach ($data as $value) {
			$html .= $this->makeHtml($value, $template);
		}
		return $html;
	}
}