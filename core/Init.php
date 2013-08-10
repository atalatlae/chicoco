<?php

class Init
{
	function __construct() {
		spl_autoload_register(array($this, "autoLoadClass"));
	}

	public function autoLoadClass($className)
	{
		if (is_file("core/".$className.".php")) {
			include("core/".$className.".php");
		}
		else if (is_file("controller/".$className.".php")) {
			include("controller/".$className.".php");
		}
		elseif (is_file("model/".$className.".php")) {
			include ("model/".$className.".php");
		}
		else {
			throw new Exception("Unable to load class $className.");
		}
	}
}
