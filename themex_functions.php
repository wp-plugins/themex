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
		$current = get_current_theme();
		$options = get_option("themex_options");

		$offset = 0;

		if (count($options) > 3){
			//Ok, module is active, let's see what to do.
			if ($options["timeZone"]){
	        	$server = date("O");
        		if ($server != $options["timeZone"]){
					if ($server < 0){
						$offset = ($server - $options["timeZone"])/100;
						if ($options["timeZone"] > 0){
							$offset = $offset * -1;

						}
						$time = $time - $offset;
					}
					else {
						$offset = ($server - $options["timeZone"])/100;
						if ($options["timeZone"] > 0){
                        	$offset = abs($offset);
						}
						$time = $time + $offset;
					}
        		}
			}

            //print("<font color=\"white\">Time: $time<br>Theme: $current<br>TZ: $server<br>TZUser: " . $options['timeZone'] . "<br>Offset: $offset<br><br>");

			if (($time) >= $options['dayStart'] && $current != $options['dayTheme']){
				//Time is past or equal to day start and current theme is night.
				//print("Inside 1");
				$this->changeTheme($options['dayTheme']);
				$current = $options['dayTheme'];

			}

			if (($time) < $options['dayStart'] && $current != $options['nightTheme']){
				//Time is prior to day start and current theme is day
				//print("Inside 2");
				$this->changeTheme($options['nightTheme']);
				$current = $options['nightTheme'];
			}

            if (($time) >= $options['nightStart'] && $current != $options['nightTheme'] && $options['dayStart'] < $options['nightStart']){
            	//Time is after or equal to night start and current theme is day and day start is less than night start
            	//print("Inside 3");
            	$this->changeTheme($options['nightTheme']);
            	$current = $options['nightTheme'];
            }

		}
		//print("</font>");
	}

	function changeTheme($newTheme){

		/**
	 	* Changes the theme
	 	* @param    string    $newTheme	The actual theme name
	 	*/

		update_option("template", $newTheme);
		update_option("stylesheet", $newTheme);
		update_option("current_theme", $newTheme);
	}
}
?>
