<?php

class Log {
	
	protected $connection;
	
	public function __construct( $connection ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function add_record( $msg, $is_error = false ){
		
		$is_error = ( $is_error ) ? 1 : 0;
		
		$queue_sql = "INSERT INTO crest_log (msg, is_error, date) VALUES ( '$msg','$is_error', now() )";
					
		$this->connection->query( $queue_sql );
		
	} // end add_record
	
}