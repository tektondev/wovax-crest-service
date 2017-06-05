<?php

class Feed_Factory {
	
	protected $connection;
	
	
	public function __construct( $connection ){
		
		require_once 'feed.class.php';
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function get_feed(){
		
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$feed = new Feed();
		
		return $feed;
		
	} // end get_feed
	
	
	public function get_feed_by_id( $feed_id ){
		
		$sql = "SELECT * FROM crest_feeds WHERE id='$feed_id' LIMIT 1";
		
		$results = $this->connection->query( $sql );
		
		if ( $results->num_rows > 0) {
			
			$row = $results->fetch_assoc();
			
			$feed = $this->get_feed();
			
			$feed->set_feed_by_db_row( $row );
			
			return $feed;
			
		} else {// end if
		
			return $false;
		
		} // end if
		
	} // end set_feed_by_id 
	
	
}