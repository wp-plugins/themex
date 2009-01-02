<?php

class themex_functions {
	/**
	* The front-end methods for themeX.
 	* @package WordPress
 	*/

	function checkTheme(){

		/**
	 	* Checks to see if the theme/time are in sync, and changes the theme if not.
	 	*/

		$time = date("H", time());
		$current = get_option("template");
		$options = get_option("themex_options");

		$offset = 0;
		if ($options["timeZone"]){
        	$server = date("O");
        	if ($server != $options["timeZone"]){
				if ($server < 0){
					if ($options["timeZone"] < 0){
						$offset = $server - $options["timeZone"];
					}

				}
				else if ($server > 0){

				}
				else {  //$server == 0

				}
        	}
		}
        //print($offset);
		if (count($options) > 3){
			if (((($time > $options['dayStart'] - 1) + $offset) && ($time < $options['nightStart']) + $offset) && $current != $options['dayTheme']){
				$this->changeTheme($options['dayTheme']);
			}
			else if ((($time < $options['dayStart'] + $offset) || ($time > $options['nightStart'] - 1) + $offset) && $current != $options['nightTheme']){
				$this->changeTheme($options['nightTheme']);
			}
		}
	}

	function changeTheme($newTheme){

		/**
	 	* Changes the theme
	 	* @param    string    $newTheme	The actual theme name
	 	*/

		update_option("template", $newTheme);
		update_option("stylesheet", $newTheme);
	}
}
?>
