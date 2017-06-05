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
	public $do_status = array( 'Active','Pending','Closed');
	
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
		
		var_dump( $this->next_type );
		
		var_dump( $this->next_type );
		
		if ( ( ( end( $stats ) == $this->next_type ) && ( end( $types ) == $this->next_status ) ) || empty( $this->next_type ) ){
			
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
			
			// if end of stats and end of types do nothing
			if ( ( end( $stats ) == $this->last_updated_status ) && ( end( $types ) == $this->last_updated_type ) ){
				
				$set['type'] = false;
				
				$set['status'] = false;
				
			} else if( ( end( $stats ) == $this->last_updated_status ) && ! empty( $types[ ( $index + 1 ) ] )  ) {
				
				$set['type'] = $types[ ( $index + 1 ) ];
				
				$set['status'] = $stats[0];
				
			} else {
				
				$set['type'] = $this->last_updated_type;
				
				$status_index = array_search( $this->last_updated_status, $stats );
				
				$set['status'] = $stats[ ( $status_index + 1 ) ];
				
			}// end if
			
			
		} // end if
		
		return $set;
		
	} // end get_next_type
	
} // end Office