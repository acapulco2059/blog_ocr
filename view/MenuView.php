<?php

class MenuView{

						// 
						// 	<li><a href="index.html">Home</a></li>
						// 	<li><a href="landing.html">Landing</a></li>
						// 	<li><a href="generic.html">Generic</a></li>
						// 	<li><a href="elements.html">Elements</a></li>
						// </ul>
						// <ul class="actions stacked">
						// 	<li><a href="#" class="button primary fit">Get Started</a></li>
						// 	<li><a href="#" class="button fit">Log In</a></li>
						// </ul>

	public function makeMenu($cssClass, $arr, $extra=""){

		$html = '<ul class="'.$cssClass.'">';
		foreach ($arr as $key => $value) {
			$html .= '<li><a href="'.$value.'" '.$extra.'>'.$key.'</a></li>';
		}
		return $html;
	}
}