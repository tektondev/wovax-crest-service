<?php

class Update {
	
	protected $connect;
	
	protected $log;
	
	
	public function __construct(){
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		require_once 'config.php';
		
		require_once CRESTAPPCLASSPATH . 'log.class.php';
		
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		
		$this->connect = new Connect();
			
		$this->log = new Log( $this->connect->connect() );
		
		if ( isset( $_GET['property_id'] ) ){	
			
			$this->ajax_update();
		
		} else if ( isset( $_GET['agent_id'] ) ){
			
			$this->ajax_agent_update();
			
		} else {
			
			$this->do_update();
			
		}// End if
		
	} // End __construct
	
	
	public function do_update(){
		
		require_once 'classes/property-factory.class.php';
		
		$property_factory = new Property_Factory( $this->connect->connect() );
		
		$js_properties = array();
		
		$properties = $property_factory->get_db_properties();
		
		$js_agents = $this->get_agents_js();
		
		include_once 'includes/property-update/property-update.php';
		
		//var_dump( $properties );
		
	} // End do_update
	
	
	public function ajax_agent_update(){
		
		$feed = $this->get_feed( $this->connect );
		
		$feed->authenticate();
		
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		
		require_once CRESTAPPCLASSPATH . 'person.class.php';
		
		$crest = new Crest();
		
		$agent_id = $_GET['agent_id'];
		
		$person = new Person( $agent_id, $this->connect->connect(), $feed, $crest );
		
		echo 'agent: ' . $agent_id . ' updated';
		
	} // End ajax_agent_update
	
	
	public function ajax_update(){
		
		$updated_array = array();
		
		$feed = $this->get_feed( $this->connect );
		
		$feed->authenticate();
		
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		
		require_once CRESTAPPCLASSPATH . 'property.class.php';
		
		$crest = new Crest();
		
		$property_id = $_GET['property_id'];
		
		$property_type = ( ! empty($_GET['property_type'] ) ) ? $_GET['property_type'] : 'ResidentialSale';
		
		$property = new Property( $this->connect->connect(), $feed, $crest );
		
		if ( $property_type ){
			
			$crest_property = $crest->single_detail_get( $feed, $property_type, $property_id, $this->log );
			
			if ( $crest_property ){
				
				$property->set_from_crest( $crest_property );
				
				$property->set_field( 'SourcePropertyType', $property_type );
				
				$force_update = ( ! empty( $_GET['f_update'] ) )? true : false;
				
				$property->insert_property( true, $force_update );
				
			} else {
				
				echo $property_id . ' property not in crest';
				
			}
			
		} // End if
		
	} // End ajax_update
	
	
	protected function get_feed( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$feed = new Feed( $connect->connect(), 1 );
		
		return $feed;
		
	} // end get_feed
	
	
	protected function get_agents_js(){
		
		$agent_ids = array();
		
		$connection = $this->connect->connect();
		
		$sql = "SELECT * FROM crest_agents";
		
		$result = $connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			while( $row = $result->fetch_assoc() ) {
			
				$agent_ids[] = $row['agent_id'];
			
			} // End while
			
		} // End if
		
		$agent_js = '[\'' . implode('\',\'', $agent_ids ) . '\']';
		
		return $agent_js;
		
	} // End get_json_agents
	
} 

$update = new Update();