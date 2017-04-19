<?php
/*  This built-in function allows us to provide our own code, 
*  to use as a means of loading a class based on the name of 
*  the class requested.  The pattern we will use to ﬁnd class 
*  ﬁles will be the title-case class name with a directory 
*  separator between each word and .php at the end. So if we 
*  need to load the Framework\Database\Driver\Mysql class, we will 
*  look for the ﬁle framework/database/driver/mysql.php (assuming our 
*  framework folder is in the PHP include path). 
*  $class = php class passed to autoload function to be included
*
*  Modified this from the original autoloader to be core.php and exist in the Framework namespace
*/
define( "DB_DSN", "mysql:host=listDb;dbname=grList" ); //this constant will be use as our connectionstring/dsn
define( "DB_USERNAME", "root" ); //username of the database
define( "DB_PASSWORD", "July,252014" ); //password of the database
define ("PATH_SEPERATOR", "/");  //Need to define PATH_SEPERATOR to eliminate notice message about constant not being defined.
/**try {
  if (session_status()==1) {
	  session_start();
  } elseif (session_status()==2) {
	  //do nothing session is staye started
  } else {
	  throw new Exception ("Session Start Error");
  }
} catch (Exception $e) {
  echo "Error in creating session on line " . __LINE__. " in file " . __FILE__ . "</br>";
  echo $e->getMessage() . "</br>";
  exit;
}
*/
set_include_path( __DIR__ . PATH_SEPERATOR . "class" . PATH_SEPERATOR); //Need to set include path to include current directory
function autoload($class) 
{
    $paths = explode(PATH_SEPERATOR, get_include_path());
    $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
    $file = str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\")).".php";  //need to set name of php file to lowercase because of stringtolower command
             foreach ($paths as $path) {
		    $combined = $path.DIRECTORY_SEPARATOR.$file;
		    if (file_exists($combined)) {
			      //echo '<br>'.$combined.'<br>'; //Troubleshooting code to echo out the file that's being loaded
			      //exit;
		      	include($combined);
			      return;
        }
    }
    throw new Exception("{$class} not found");
}
class Autoloader 
{
    public static function autoload($class) {
		    autoload($class);
    }
}
spl_autoload_register('autoload');
spl_autoload_register(array('autoloader', 'autoload'));
// these can only be called within a class context…
//spl_autoload_register(array($this, 'autoload'));
//spl_autoload_register(__CLASS__.'::load');
