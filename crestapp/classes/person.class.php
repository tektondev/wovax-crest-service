<?php

class Person {
	
	protected $feed = false;
	
	protected $crest = false;
	
	protected $connection = false;
	
	protected $person_id = '';
	
	protected $mls_id = '';
	
	protected $display_name = '';
	
	protected $email = '';
	
	protected $phone = '';
	
	protected $team_id = '';
	
	protected $staff_id = '';
	
	protected $is_primary = '';
	
	
	public function __construct( $crest_id, $connection = false, $feed = false, $crest = false, $set_person = true ){
		
		$this->feed = $feed;
		
		$this->crest = $crest;
		
		$this->connection = $connection;
		
		if ( $set_person ){
			
			$this->set_person( $crest_id );
			
		} // End if
		
	} // end __construct
	
	
	public function set_person( $crest_id ){
		
		$this->person_id = $crest_id;
		
		$db_agent = $this->get_person_from_db();
		
		$crest_agent = $this->get_person_from_crest();
		
		$agent = ( $db_agent ) ? $this->update_person_record( $db_agent, $crest_agent ) : $crest_agent;
		
		$this->display_name = $agent['agent_display_name'];

		$this->email = $agent['email'];

		$this->phone = $agent['phone'];

		$this->team_id = $agent['team_id'];

		$this->staff_id = $agent['staff_id'];

		$this->mls_id = $agent['agent_mls_id'];
		
		if ( ! $db_agent ){
			
			$this->create_agent();
			
		} // End if
		
	} // End set_person
	
	
	protected function update_agent( $key, $value ){ 
		
		$agent_id = $this->person_id;
		
		$value = $this->connection->real_escape_string( $value );
		
		$usql = "UPDATE crest_agents SET {$key}='{$value}' WHERE agent_id='{$agent_id}'";
				
		$results = $this->connection->query( $usql );
		
	} // End update_agent
	
	
	protected function create_agent(){ 
		
		$agent_id = $this->connection->real_escape_string( $this->person_id );
		
		$mls_id = $this->connection->real_escape_string( $this->mls_id );
		
		$agent_name = $this->connection->real_escape_string( $this->display_name );
			
		$email = $this->connection->real_escape_string( $this->email );
			
		$phone = $this->connection->real_escape_string( $this->phone );
			
		$team_id = $this->connection->real_escape_string( $this->team_id );
			
		$staff_id = $this->connection->real_escape_string( $this->staff_id );
		
		$sql = "INSERT INTO crest_agents (agent_id, agent_mls_id, agent_display_name, email, phone, team_id, staff_id) VALUES ( '$agent_id','$mls_id',$agent_name','$email','$phone','$team_id','$staff_id' )";
			  
		$this->connection->query( $sql );
		
	} // End update_agent
	
	
	protected function update_person_record( $db_agent, $crest_agent ){
		
		$agent = $db_agent;
		
		foreach( $crest_agent as $key => $value ){
			
			if ( ! empty( $value ) ){
				
				if ( empty( $db_agent[ $key ] ) || ( $db_agent[ $key ] !== $value ) ){
					
					$db_agent[ $key ] = $value;
					
					$this->update_agent( $key, $value );
					
				} // End if
				
			} // End if
			
		} // End foreach 
		
		return $agent;
		
	} // End update_person_record
	
	
	protected function get_person_from_db(){
		
		$agent_id = $this->person_id;
		
		$sql = "SELECT * FROM crest_agents WHERE agent_id='$agent_id'";
		
		$result = $this->connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			$agent = $result->fetch_assoc();
			
			return $agent;
			
		} else {
			
			return false;
			
		} // End if
		
	} // End get_person_from_db
	
	
	protected function get_person_from_crest(){
		
		$agent_id = $this->person_id;
		
		$crest_agent = $this->crest->get_agent( $agent_id, $this->feed );
		
		$agent = array(
			'agent_id' 				=> $agent_id,
			'agent_display_name' 	=> $this->get_crest_display_name( $crest_agent ),
			'email' 				=> $this->get_crest_email( $crest_agent ),
			'phone' 				=> $this->get_crest_phone( $crest_agent ),
			'team_id' 				=> '',
			'staff_id' 				=> $this->get_crest_staff_id( $crest_agent ),
			'agent_mls_id'			=> $this->get_crest_mls_id( $crest_agent ), 
		);
		
		return $agent;
		
	} // End get_person_from_crest
	
	
	protected function get_crest_mls_id( $crest_agent ){
		
		$mls_id = '';
		
		if ( isset( $crest_agent->PersonDetail->PersonMLSDetails )  ){
				
			$mls_id = $crest_agent->PersonDetail->PersonMLSDetails->PersonMLS->PersonMLSId;

		} // end if
		
		return $mls_id;
		
	} // End get_crest_display_name
	
	
	protected function get_crest_staff_id( $crest_agent ){
		
		$staff_id = '';
		
		if ( isset( $crest_agent->OfficeStaffsDetail->OfficeStaffDetail->OfficeStaffId )  ){
				
			$staff_id = $crest_agent->OfficeStaffsDetail->OfficeStaffDetail->OfficeStaffId;

		} // end if
		
		return $staff_id;
		
	} // End get_crest_display_name
	
	
	protected function get_crest_display_name( $crest_agent ){
		
		$display_name = '';
		
		if ( isset( $crest_agent->PersonDetail->StaffName )  ){
				
			$display_name = $crest_agent->PersonDetail->StaffName->DisplayName;

		} // end if
		
		return $display_name;
		
	} // End get_crest_display_name
	
	
	protected function get_crest_email( $crest_agent ){
		
		$email = '';
		
		if ( isset( $crest_agent->PersonDetail->DefaultEmail )  ){
				
			$email = $crest_agent->PersonDetail->DefaultEmail->EmailAddress;

		} // end if
		
		return $email;
		
	} // End get_crest_email
	
	
	protected function get_crest_phone( $crest_agent ){
		
		$phone = '';
		
		if ( isset( $crest_agent->PersonDetail->DefaultPhoneNumber->Number ) ){
				
			$phone = $crest_agent->PersonDetail->DefaultPhoneNumber->Number;

		} else {

			if ( isset( $crest_agent->PersonDetail->AdditionalPhoneNumbers ) ){

				if ( is_array( $crest_agent->PersonDetail->AdditionalPhoneNumbers ) ){

					if ( isset( $crest_agent->PersonDetail->AdditionalPhoneNumbers[0]->PhoneNumber->Number ) ){

						$phone = $crest_agent->PersonDetail->AdditionalPhoneNumbers[0]->PhoneNumber->Number;

					} // end if

				} else {

					if ( isset( $crest_agent->PersonDetail->AdditionalPhoneNumbers->PhoneNumber->Number ) ){

						$phone = $crest_agent->PersonDetail->AdditionalPhoneNumbers->PhoneNumber->Number;

					} // end if

				} // end if

			} // end if

		}// end if
		
		return $phone;
		
	} // End get_crest_phone
	
	
} // End Person