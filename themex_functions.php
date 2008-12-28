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

		if (count($options) == 4){
			if (($time > $options['dayStart'] - 1 && $time < $options['nightStart']) && $current != $options['dayTheme']){
				$this->changeTheme($options['dayTheme']);
			}
			else if (($time < $options['dayStart'] || $time > $options['nightStart'] - 1) && $current != $options['nightTheme']){
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
