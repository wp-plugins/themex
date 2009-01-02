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
    	*/

    	delete_option('themex_options');
    }

    function themex_admin_menu(){
    	/**
    	* The hook for the admin menu
    	*/
        add_management_page('ThemeX', 'ThemeX', 5, __FILE__, array($this, 'themex_admin_page'));
    }

    function timeZoneList(){
    	/**
    	* Produces a list of time zones in an array
    	* @return	array	$this->timeZoneArray	List of time zones in array format.
    	*/

    	$tz = array();
		$tz["GMT"] = "000";
		$tz["UTC"] = "000";
		$tz["ECT"] = "+100";
		$tz["EET"] = "+200";
		$tz["ART"] = "+200";
		$tz["EAT"] = "+300";
		$tz["MET"] = "+330";
		$tz["NET"] = "+400";
		$tz["PLT"] = "+500";
		$tz["IST"] = "+530";
		$tz["BST"] = "+600";
		$tz["VST"] = "+700";
		$tz["CTT"] = "+800";
		$tz["JST"] = "+900";
		$tz["ACT"] = "+930";
		$tz["AET"] = "+1000";
		$tz["SST"] = "+1100";
		$tz["NST"] = "+1200";
		$tz["MIT"] = "-1100";
		$tz["HST"] = "-1000";
		$tz["AST"] = "-900";
		$tz["PST"] = "-800";
		$tz["PNT"] = "-700";
		$tz["MST"] = "-700";
		$tz["CST"] = "-600";
		$tz["EST"] = "-500";
		$tz["IET"] = "-500";
		$tz["PRT"] = "-400";
		$tz["CNT"] = "-330";
		$tz["AGT"] = "-300";
		$tz["BET"] = "-300";
		$tz["CAT"] = "-100";

		$this->timeZoneArray = $tz;

    }

    function themex_admin_page(){
    	/**
    	* Creates the Admin page
    	*/

        clearstatcache();
        $this->timeZoneList();
        $options = get_option('themex_options');
		if ($_POST['action'] == "update"){
                if ($_POST['type'] == "simple"){
                    $options["type"]       = "simple";
                	$options["dayTheme"]   = $_POST["dayTheme"];
                	$options["dayStart"]   = $_POST["dayStart"];
                	$options["nightTheme"] = $_POST["nightTheme"];
                	$options["nightStart"] = $_POST["nightStart"];

					$message = 'Options Updated.  Simple Rotation Active.';
				}
				else if ($_POST["type"] == "time"){
                    $options["timeZone"] = $this->timeZoneArray[$_POST["timezone"]];
                    $options["timeZoneAbbr"] = $_POST["timezone"];
                    $message = 'Time Zone Updated';
				}
				else {

				}

				update_option('themex_options', $options);

		}

        $themes = get_themes();
		$tArray = array();
		foreach($themes as $t){
			$tArray[$t["Template"]] = $t["Name"];
		}


        $text .= "<div class=\"wrap\">";


        $text .= "<h2>ThemeX</h2>";
        $text .= "ThemeX automatically alternates between two themes based on the time of day.";
        $text .= "<br />Select your two themes and when each should become the active theme and press 'Save Changes'.";

        if ($message){ $text .= "<br /><b><span style=\"color:#FF0000;\">$message</span></b>"; }
		$text .= "<div id=\"poststuff\" class=\"metabox-holder\">";
		$text .= "<div id=\"post-body\" class=\"has-sidebar\">";
		$text .= "<div id=\"post-body-content\" class=\"has-sidebar-content\">";


        $text .= "<div class=\"postbox\">";
        $text .= "<h3><label>Time Zone</label></h3>";
		$text .= "<div class=\"inside\">";
        $text .= "<form method=\"post\" action=\"\">";
        $text .= "<input type=\"hidden\" name=\"action\" value=\"update\" />";
        $text .= "<input type=\"hidden\" name=\"type\" value=\"time\" />";

		$offset = date('O');

        $text .= "<table class=\"form-table\">";
        $text .= "<tr class=\"form-field form-required\">";
        $text .= "<th>";
        $text .= "<tr class=\"form-field form-required\">";
        $text .= "<th scope=\"row\" valign=\"top\"><label for=\"time_zone\">Time Zone:</label></th>";
        $text .= "<td>";

        $text .= "<select name=\"timezone\">";


        $used = false;

        foreach(array_keys($this->timeZoneArray) as $t){
        	if ($options["timeZoneAbbr"] == $t){ $s = "selected"; $used = true; }
        	else if ($this->timeZoneArray[$t] == $offset && !$used){ $s = "selected"; }
        	else { $s = ''; }
        	$text .= "<option value=\"$t\" $s>" . $this->timeZoneArray[$t] . " $t</option>";
        }



        $text .= "</select>";
        $text .= "&nbsp; &nbsp; Your server time zone is: $offset " . date('T');

        $text .= "</td></tr>";
        $text .= "</table>";
        $text .= "<p class=\"submit\"><input type=\"submit\" name=\"Submit\" value=\"Save Changes\" />";
        $text .= "</p></form>";
		$text .= "</div></div>";





		$text .= "<div class=\"postbox\">";
        $text .= "<h3><label>Simple Rotation</label></h3>";
		$text .= "<div class=\"inside\">";
        $text .= "<form method=\"post\" action=\"\">";
        $text .= "<input type=\"hidden\" name=\"action\" value=\"update\" />";
        $text .= "<input type=\"hidden\" name=\"type\" value=\"simple\" />";



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



        $text .= "<div class=\"postbox\">";
        $text .= "<h3><label>Date Based Rotation</label></h3>";
		$text .= "<div class=\"inside\">";


		$text .= "</div></div>";









        $text .= "</div></div>";
        print($text);



    }
}

?>
