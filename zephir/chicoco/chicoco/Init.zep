namespace Chicoco;

class Init
{
    public function __construct() {
    	array pa;
    	let pa = [this, "autoLoadClass"];
    	spl_autoload_register(pa);
    }

    public function autoLoadClass(className)
    {
        let className = str_replace("Chicoco\\", "", className);
        if  is_file("core/".className.".php") {
            require("core/".className.".php");
        }
        else {
        	if is_file("controller/".className.".php") {
            	require("controller/".className.".php");
        	}
        
        	else {
        		if (is_file("model/".className.".php")) {
            		require("model/".className.".php");
        		}
    			else {
                    //throw new Exception("Unable to load class $className.");
					echo "ERROR loading class";
        		}
        	}
        }
    }
}