<?php

class Feed {
	
	protected $table = 'crest_feeds';
	protected $feed_id = false;
	protected $user = false;
	protected $pwd = false;
	protected $brand_code = false;
	protected $updated = false;
	protected $connection = false;
	protected $token = false;
	protected $token_expires = false;
	
	
	public function get_table(){ return $this->table; }
	public function get_feed_id(){ return $this->feed_id; }
	public function get_feed_user(){ return $this->user; }
	public function get_feed_pwd(){ return $this->pwd; }
	public function get_brand_code(){ return $this->brand_code; }
	public function get_updated() { return $this->updated; }
	public function get_token(){ return $this->token; }
	public function get_token_expires() { return $this->token_expires; }
	
	
	public function set_feed_id( $feed_id ){ $this->feed_id = $feed_id; }
	public function set_feed_user(  $feed_user ){ $this->user = $feed_user; }
	public function set_feed_pwd( $feed_pwd ){ $this->pwd = $feed_pwd; }
	public function set_brand_code( $feed_brand_code ){ $this->brand_code = $feed_brand_code; }
	public function set_updated( $value ) { $this->updated = $value; }
	public function set_token( $value ) { $this->token = $value; }
	public function set_token_expires( $value ) { $this->token_expires = $value; }
	
	
	public function __construct( $connection = false, $feed_id = false ){
		
		$this->connection = $connection;
		
		if ( $feed_id ){
		
			$this->set_feed_by_id( $feed_id );
			
		} // end if
		
	} // end __construct
	
	public function authenticate(){
		
		$date = new DateTime();
		
		if ( $this->get_token() && ( strtotime( $this->get_token_expires() ) > strtotime( 'now' ) ) ) {
			
			return true;
			
		} else {
			
			$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Header/><soapenv:Body/></soapenv:Envelope>';

			$headers = array(
				'Content-Type: text/xml',
				'Accept-Encoding: gzip,deflate',
				'SOAPAction: "http://rfg.realogy.com/Btt/AuthenticationManagement/Services/2009/05/AuthenticationManagementServiceContract/Authenticate"',
				'Host: auth.ws.realogyfg.com',
				'Connection: Keep-Alive',
				'Cookie: OBBasicAuth=fromDialog; ObSSOCookie=loggedoutcontinue',
				//'Cookie2: $Version=1',
				'Authorization: Basic ' . base64_encode( $this->get_feed_user() . ':' . $this->get_feed_pwd() ),
			);
			
			$process = curl_init( 'https://auth.ws.realogyfg.com/AuthenticationService/AuthenticationMgmt.svc' );
			
			curl_setopt($process, CURLOPT_HTTPHEADER, $headers );
			curl_setopt($process, CURLOPT_TIMEOUT, 30);
			curl_setopt($process, CURLOPT_POST, 1);
			curl_setopt($process, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($process, CURLOPT_ENCODING, '');
			
			$response = curl_exec($process);
			
			preg_match( '/<a:Token>(.*)<\/a:Token>/', $response, $matches, PREG_OFFSET_CAPTURE );
			
			preg_match( '/<a:Expiration>(.*)<\/a:Expiration>/', $response, $expires, PREG_OFFSET_CAPTURE );
			
			if ( ! empty( $matches[1][0] ) && ! empty( $expires[1][0] )){
				
				$this->set_token( $matches[1][0] );
				$this->set_token_expires( $expires[1][0] );
				$this->update_token();
				
				return true;
				
			} else {
				
				//return false;
				
			}// end if
			
		} // end if
		
	} // end authenticate
	
	public function set_feed_by_id( $feed_id ){
		
		$sql = "SELECT * FROM crest_feeds WHERE id='$feed_id' LIMIT 1";
		
		$results = $this->connection->query( $sql );
		
		if ( $results->num_rows > 0) {
			
			$row = $results->fetch_assoc();
			
			$this->set_feed_by_db_row( $row );
			
		} else {
		
			return $false;
		
		} // end if
		
	} // end set_feed_by_id
	
	
	public function set_feed_by_db_row( $feed ){
		
		$id = ( $feed['id']  )? $feed['id']  : '';
		$user = ( $feed['user']  )? $feed['user']  : ''; 
		$pwd = ( $feed['pwd']  )? $feed['pwd']  : '';
		$brand_code = ( $feed['brand_code']  )? $feed['brand_code']  : '';
		$updated = ( $feed['updated']  )? $feed['updated']  : false;
		$token =  ( $feed['token']  )? $feed['token']  : false;
		$token_expires = ( $feed['token_expires']  )? $feed['token_expires']  : false;
		 
		$this->set_feed_id( $id );
		$this->set_feed_user( $user );
		$this->set_feed_pwd( $pwd );
		$this->set_brand_code( $brand_code );
		$this->set_updated( $updated );
		$this->set_token( $token );
		$this->set_token_expires( $token_expires );
		
	} // end set_feed_by_id 
	
	
	protected function update_token() {
		
		$feed_id = $this->get_feed_id();
		
		$sql = "UPDATE $this->table SET token='$this->token',token_expires='$this->token_expires' WHERE id='$feed_id'";
		
		$this->connection->query( $sql );
		
	} // end update_token
	
	
	public function set_updated_now() {
		
		$feed_id = $this->get_feed_id();
		
		$dt1 = new DateTime();
		
		$time = $dt1->format('Y-m-d H:i:s');
		
		$sql = "UPDATE $this->table SET updated='$time' WHERE id='$feed_id'";
		
		$this->connection->query( $sql );
		
	} // end update_token
	
	
	public function check_update(){
		
		if ( ! $this->get_updated() || isset( $_GET['testing'] ) ) { 
			
			return true;
			
		} else {
			
			$dt1 = new DateTime( $this->get_updated() );
	
			$dt2 = new DateTime();
			
			$interval = $dt2->diff( $dt1 );
			
			$i =  $interval->format('%i'); 
			
			if ( $i < 15 ){
				
				return false;
				
			} else {
			
				return true;
				
			} // end if
			
		} // end if
		
		return false;
		
	} // end do_update
	
}