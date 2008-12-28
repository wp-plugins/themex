<?php

class themex_admin {

    function themex_install(){
	    /**
	    * Installs the plugin by creating the page and options
	    */

		if (!get_option('themex_options')){
        	update_option('themex_options', array());
        }

    }

    function themex_uninstall(){
    	/**
    	* Uninstalls the plugin by deleting the options and page
    	*
    	*/

    	delete_option('themex_options');
    }

    function themex_admin_menu(){
    	/**
    	* The hook for the admin menu
    	*
    	* @param NULL
    	* @return NULL
    	*/
        add_management_page('ThemeX', 'ThemeX', 5, __FILE__, array($this, 'themex_admin_page'));
    }

    function themex_admin_page(){
    	/**
    	* Creates the Admin page
    	*
    	* @param NULL
    	* @return NULL
    	*/

        clearstatcache();

        $options = get_option('themex_options');
		if ($_POST['action'] == "update"){
                $options["dayTheme"] = $_POST["dayTheme"];
                $options["dayStart"] = $_POST["dayStart"];
                $options["nightTheme"] = $_POST["nightTheme"];
                $options["nightStart"] = $_POST["nightStart"];
                update_option('themex_options', $options);
				$message = 'Options Updated';

		}

        $themes = get_themes();
		$tArray = array();
		foreach($themes as $t){
			$tArray[$t["Template"]] = $t["Name"];
		}




		$text .= "<div id=\"post-body\" class=\"has-sidebar\">";
		$text .= "<div id=\"post-body-content\" class=\"has-sidebar-content\">";
		$text .= "<div class=\"stuffbox\">";

        $text .= "<h3><label>ThemeX</label></h3>";
		$text .= "<div class=\"inside\">";

        $text .= "ThemeX automatically alternates between two themes based on the time of day.";
        $text .= "  Select your two themes and when each should become the active theme and press 'Save Changes'.";

        $text .= "</div></div>";

        $text .= "<form method=\"post\" action=\"\">";
        $text .= "<input type=\"hidden\" name=\"action\" value=\"update\" />";

        if ($message){ $text .= "<br /><b><span style=\"color:#FF0000;\">$message</span></b>"; }

        $text .= "<table class=\"form-table\">";
        $text .= "<tr class=\"form-field form-required\">";
        $text .= "<th scope=\"row\" valign=\"top\"><label for=\"day_theme\">Day Theme:</label></th>";
        $text .= "<td><select name=\"dayTheme\">";
        foreach(array_keys($tArray) as $t){
        	if ($t == $options["dayTheme"]){ $s = "selected"; }
        	else { $s = '';  }
        	$text .= "<option value=\"$t\" $s>" . $tArray[$t] . "</option>";
        }
        $text .= "</select> starts at ";
        $text .= "<select name=\"dayStart\">";
        for($i=0;$i<24;$i++){
        	if (strlen($i) == 1){ $v = "0" . $i; }
        	else { $v = $i; }
        	if ($v == $options["dayStart"]){ $s = "selected"; }
        	else { $s = ''; }
        	$text .= "<option value=\"$v\" $s>$v</option>";
        }
        $text .= "</select> hours";

        $text .= "</td></th></tr>";
        $text .= "<tr class=\"form-field\">";
        $text .= "<th scope=\"row\" valign=\"top\"><label for=\"reset_cache\">Night Theme:</label></th>";
        $text .= "<td><select name=\"nightTheme\">";
        foreach(array_keys($tArray) as $t){
        	if ($t == $options["nightTheme"]){ $s = "selected"; }
        	else { $s = '';  }
        	$text .= "<option value=\"$t\" $s>" . $tArray[$t] . "</option>";
        }
        $text .= "</select> starts at ";
        $text .= "<select name=\"nightStart\">";
        for($i=0;$i<24;$i++){
        	if (strlen($i) == 1){ $v = "0" . $i; }
        	else { $v = $i; }
        	if ($v == $options["nightStart"]){ $s = "selected"; }
        	else { $s = ''; }
        	$text .= "<option value=\"$v\" $s>$v</option>";
        }
        $text .= "</select> hours";

        $text .= "</td></th></tr>";
        $text .= "</table>";



        $text .= "<p class=\"submit\"><input type=\"submit\" name=\"Submit\" value=\"Save Changes\" />";
        $text .= "</p></form>";
        $text .= "</div></div>";
        print($text);



    }
}

?>
