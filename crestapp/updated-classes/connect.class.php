<?php

class Connect {
	
	
    static $connection = false;
	
	
	public function connect(){

    	if( ! self::$connection ) { 
			
			$mysqli = new mysqli('localhost', CRESTAPPDBNAME, CRESTAPPDBPWD, CRESTAPPDBUSER );
			
			$mysqli->set_charset("utf8");
			
			if ( ! $mysqli->connect_errno) {
				
				self::$connection = $mysqli;
				
			} // end if
		 
    	} // end if
		
		return self::$connection;
		
	} // end connect
	
	
} // end Connect