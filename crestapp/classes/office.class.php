<?php 

class Office {
	
	public $name;
	public $updated;
	public $id;
	public $listing_types;
	public $last_updated_type = false;
	public $next_type = false;
	public $next_status = false;
	public $last_updated_status = false;
	public $do_status = array( 'Active','Pending');
	
	protected $connection;
	
	public function __construct( $connection ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function set_last_updated(){
		
		$offices = $this->get_db_offices();
		
		if ( ! empty( $offices ) ){
			
			usort( $offices, function( $a, $b ){
				$a = strtotime($a['updated']);
    			$b = strtotime($b['updated']);
				return ( ( $a == $b ) ? ( 0 ) : ( ( $a > $b ) ? ( 1 ) : ( -1 ) ) );
			});
			
			return $this->set_office( $offices[0] );
			
		} else {
			
			return false;
			
		} // end if
		
	} //end get_db_offices
	
	
	protected function get_db_offices(){
		
		$offices = array();
		
		$sql = "SELECT * FROM crest_offices";
		
		$results = $this->connection->query( $sql );
		
		while( $office = $results->fetch_assoc() ) {
				
			$offices[] = $office;
			
		} // end while
		
		return $offices;
		
	} // end get_db_offices
	
	public function insert_type_status(){
		
		$update_set = $this->next_type . ':' . $this->next_status;
		
		$types = $this->listing_types;
			
		$stats = $this->do_status;
		
		
		if (  ( ( end( $types ) == $this->next_type ) && ( end( $stats ) == $this->next_status ) )  || empty( $this->next_type ) ){
			
			$this->insert_updated_now();
			
		} else {// end if
		
			$id = $this->id;
			
			var_dump( $id );
			
			$sql = "UPDATE crest_offices SET last_updated_set='$update_set' WHERE id='$id'";
			
			$results = $this->connection->query( $sql );
			
			var_dump( $results );
		
		} // end if
		
	} // end insert_type_status
	
	
	public function insert_updated_now(){
		
		$id = $this->id;
		
		$sql = "UPDATE crest_offices SET last_updated_set='',updated=now() WHERE id='$id'";
		
		$results = $this->connection->query( $sql );
		
		var_dump( $results );
		
	} // end insert_type_status
	
	
	protected function set_office( $office ){
		
		$this->listing_types = explode( ',' , $office['listing_types'] );
		
		$this->name = $office['name'];
		
		$this->id = $office['id'];
		
		$this->updated = strtotime( $office['updated'] );
		
		if ( ! empty( $office['last_updated_set'] ) ){
			
			$set = explode( ':', $office['last_updated_set'] );
			
			$this->last_updated_type = $set[0];
			$this->last_updated_status = $set[1];
			
		} // end if
		
		$type_set = $this->get_next_type_set();
		
		$this->next_type = $type_set['type'];
		
		$this->next_status = $type_set['status'];
		
		return true;
		
	} // end set_office
	
	
	
	public function get_next_type_set(){
		
		$set = array( 'type' => 'ResidentialSale', 'status' => 'Active' );
		
		if ( $this->last_updated_type ){
			
			$types = $this->listing_types;
			
			$stats = $this->do_status;
			
			$index = array_search( $this->last_updated_type, $types );
			
			$stat_index = array_search( $this->last_updated_status, $stats );
			
			if ( $stat_index === false ) $stat_index = 0;
			
			$next_index = ( $index + 1 );
			
			$next_s_index = ( $stat_index + 1 );
			
			// if end of stats and end of types do nothing
			if ( ( $next_index >= count( $types ) )  && ( $next_s_index >= count( $stats ) ) ){
				
				$set['type'] = $types[0];
				
			} else if ( $next_s_index >= count( $stats ) ) {
				
				$set['type'] = $types[ ( $index + 1 ) ];
				
			} else {
				$set['type'] = $this->last_updated_type;
				$set['status'] = $stats[ $next_s_index ];
				
			}// end if
			
			
		} // end if
		
		var_dump( $set );
		
		return $set;
		
	} // end get_next_type
	
} // end Office