<?php


if ($choix==1){
 $maison = "bleue";
}
else {
 $maison = "rouge";

}

$maison = $this->defineColor($choix);

function defineColor($choix){
	if ($choix==1) return "bleue";
	return "rouge";
}

// ---------------------------

$jeanLuc      = new Salarie("ouvrier");
$jeanFrancois = new Salarie("comptable");



$vue = new HTMLview(['data'=>[], 'template'=>"article.html"]);

$vue->html;

// ---------------------------


class SessionBenoit
{
	// public $user;
	// public $versions;
	public $data;
	function __construct($argument)
	{
		//
		$this->data = filter_var_array(
			$_SESSION,
			[
				'user'     => FILTER_SANITIZE_STRING,
    		'versions' => FILTER_SANITIZE_ENCODED
			]
		);
		// foreach ($data as $key => $value) {
		// 	$this->$key = $value;
		// }
	}

	public function delete($key){
		unset($this->data[$key]);
		$_SESSION = $data;
	}
}

//---------------------------------------

$GLOBALS["envProd"] = false;

$envProd = false;

global $envProd;
if (!$envProd);
