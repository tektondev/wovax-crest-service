<?php

class Property {
	
	public $fields;
	protected $connection = false;
	protected $exclude_db = array(
		'AssociatedAgents',
		'ListingRemarks',
		'SourceSystemID',
		'GeographicRegions',
		'ListingFeature',
		'PropertyLocation',
		'BuildingArea',
		'LotSize',
		'PropertyFeatures',
		'Floors',
		'Rooms',
		'Schools',
		'ListingMedia',
	);
	protected $delete_status = array(
		'Withdrawn',
		'Expired'
	);
	
	
	public function __construct( $connection = false ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function get_fields() { return $this->fields; }
	
	
	public function get_field_value( $field ) {
		
		$value = '';
		
		if ( array_key_exists( $field, $this->fields ) ){
			
			$value = $this->fields[ $field ];
			
		} // end if
		
		return $value;
		
	} // end getget_field_value
	
	
	
	public function set_from_crest( $p ) {
		
		$primary_agent = ( isset( $p->CoreListingDetail->AssociatedAgents->Agent ) ) ? $this->get_primary_agent( $p->CoreListingDetail->AssociatedAgents->Agent  ) : $this->get_primary_agent( array() );
		
		
		$this->fields = array(
			'Property_ID' => ( isset( $p->CoreListingDetail->ListingId) )? $p->CoreListingDetail->ListingId : '',
			'MLS_ID' => ( isset( $p->PrimaryMLS ) )? $p->PrimaryMLS : '',
			'TermDays' => ( isset( $p->TermDays ) )? $p->TermDays : '',
			'IsForeClosure' => ( isset( $p->IsForeClosure ) && $p->IsForeClosure )? 1 : 0,
			'IsShortSale' => ( isset( $p->IsShortSale ) && $p->IsShortSale )? 1 : 0,
			'IsForAuction' => ( isset( $p->IsForAuction ) && $p->IsForAuction )? 1 : 0,
			'OccupancyRate' => ( isset( $p->OccupancyRate) )? $p->OccupancyRate : '',
			'AvailableFloors' => ( isset( $p->AvailableFloors) )? $p->AvailableFloors : '',
			'LoanPayment' => ( isset( $p->LoanPayment) )? $p->LoanPayment : '',
			'DownPayment' => ( isset( $p->DownPayment) )? $p->DownPayment : '',
			'GrossIncome' => ( isset( $p->GrossIncome) )? $p->GrossIncome : '',
			'NetoperatingIncome' => ( isset( $p->NetoperatingIncome) )? $p->NetoperatingIncome : '',
			'TotalExpenses' => ( isset( $p->TotalExpenses) )? $p->TotalExpenses : '',
			'CashFlow' => ( isset( $p->CashFlow) )? $p->CashFlow : '',
			'AvailableSpace' => ( isset( $p->AvailableSpace) )? $p->AvailableSpace : '',
			'ListPrice' => ( isset( $p->ListPrice->Amount) )? $p->ListPrice->Amount : '',
			'Rent' => ( isset( $p->Rent->Amount) )? $p->Rent->Amount : '',
			'RentCurrency' =>  ( isset( $p->Rent->Currency ) )? $p->Rent->Currency : '',
			'ListPriceCurrency' => ( isset( $p->ListPrice->Currency) )? $p->ListPrice->Currency : '',
			'ListedDate' =>( isset( $p->CoreListingDetail->ListedDate) )? $p->CoreListingDetail->ListedDate : '',
			'ListingContractDate' => ( isset( $p->CoreListingDetail->ListingContractDate) )? $p->CoreListingDetail->ListingContractDate : '',
			'ExpirationDate' => ( isset( $p->CoreListingDetail->ExpirationDate ) )? $p->CoreListingDetail->ExpirationDate : '',
			'IsCallToShow' => ( isset( $p->CoreListingDetail->IsCallToShow ) && $p->CoreListingDetail->IsCallToShow )? 1 : 0,
			'IsNewConstruction' => ( isset( $p->CoreListingDetail->IsNewConstruction ) && $p->CoreListingDetail->IsNewConstruction )? 1 : 0,
			'ListingMedia' => ( isset( $p->CoreListingDetail->ListingMedia->MediaItem ) )? $p->CoreListingDetail->ListingMedia->MediaItem : array(),
			'Status' => ( isset( $p->CoreListingDetail->Status ) )? $p->CoreListingDetail->Status : '',
			'WebURL' => ( isset( $p->CoreListingDetail->WebURL ) )? $p->CoreListingDetail->WebURLs : '',
			'RFGListingID' => ( isset( $p->CoreListingDetail->RFGListingID ) )? $p->CoreListingDetail->RFGListingID : '',
			'OfficeId' => ( isset( $p->CoreListingDetail->OfficeId ) )? $p->CoreListingDetail->OfficeId : '',
			'OfficeName' => ( isset( $p->CoreListingDetail->OfficeName ) )? $p->CoreListingDetail->OfficeName : '',
			
			'IsShownOnInternet' => ( isset( $p->CoreListingDetail->IsShownOnInternet ) && $p->CoreListingDetail->IsShownOnInternet )? 1 : 0,
			'IsShowAddressOnInternet' => ( isset( $p->CoreListingDetail->IsShowAddressOnInternet ) && $p->CoreListingDetail->IsShowAddressOnInternet )? 1 : 0,
			'IsHideListPrice' => ( isset( $p->CoreListingDetail->IsHideListPrice ) && $p->CoreListingDetail->IsHideListPrice )? 1 : 0,
			'IsAllowMapping' => ( isset( $p->CoreListingDetail->IsAllowMapping ) && $p->CoreListingDetail->IsAllowMapping )? 1 : 0,
			'IsPriceuponRequest' => ( isset( $p->CoreListingDetail->IsPriceuponRequest ) && $p->CoreListingDetail->IsPriceuponRequest )? 1 : 0,
			
			'DateAvailable' => ( isset( $p->CoreListingDetail->DateAvailable ) )? $p->CoreListingDetail->DateAvailable : '',
			'BrandName' => ( isset( $p->CoreListingDetail->Brand->BrandName ) )? $p->CoreListingDetail->Brand->BrandName : '',
			'BrandCode' => ( isset( $p->CoreListingDetail->Brand->BrandCode ) )? $p->CoreListingDetail->Brand->BrandCode : '',
			'AssociatedAgents' => ( isset( $p->CoreListingDetail->AssociatedAgents ) )? $p->CoreListingDetail->AssociatedAgents : array(),
			
			'PrimaryAgentName' => $primary_agent['name'],
			'PrimaryAgentId' => $primary_agent['crest_id'],
			'PrimaryAgentStaffId' => $primary_agent['staff_id'],
			
			//'PropertyDescription' => ( isset( $p->CoreListingDetail->ListingRemarks->Remark->RemarkText ) )? $p->CoreListingDetail->ListingRemarks->Remark->RemarkText : '',
			'PropertyDescription' => ( isset( $p->CoreListingDetail->ListingRemarks->Remark ) )? $this->get_property_description( $p->CoreListingDetail->ListingRemarks->Remark ) : '',
			
			'ListingRemarks' => ( isset( $p->CoreListingDetail->ListingRemarks ) )? $p->CoreListingDetail->ListingRemarks : array(),
			'SourceSystemID' => ( isset( $p->CoreListingDetail->SourceSystemID ) )? $p->CoreListingDetail->SourceSystemID : '',
			'GeographicRegions' => ( isset( $p->CoreListingDetail->GeographicRegions ) )? $p->CoreListingDetail->GeographicRegions : '',
			'ListingFeature' => ( isset( $p->CoreListingDetail->ListingFeature ) )? $p->CoreListingDetail->ListingFeature : '',
			'ListingFees' => ( isset( $p->CoreListingDetail->ListingFees ) )? $p->CoreListingDetail->ListingFees : '',
			'DevelopmentID' => ( isset( $p->CoreListingDetail->DevelopmentID ) )? $p->CoreListingDetail->DevelopmentID : '',
			'LastUpdatedDate' => ( isset( $p->CoreListingDetail->LastUpdateDate ) )? $p->CoreListingDetail->LastUpdateDate : '',
			'ClosedDate' => ( isset( $p->CoreListingDetail->ClosedDate ) )? $p->CoreListingDetail->ClosedDate : '',
			'WithdrawnOn' => ( isset( $p->CoreListingDetail->WithdrawnOn ) )? $p->CoreListingDetail->WithdrawnOn : '',
			'LastUpdatedBy' => ( isset( $p->CoreListingDetail->LastUpdatedBy ) )? $p->CoreListingDetail->LastUpdatedBy : '',
			'EstimatedCloseDate' => ( isset( $p->CoreListingDetail->EstimatedCloseDate ) )? $p->CoreListingDetail->EstimatedCloseDate : '',
			'OfficeTradeName' => ( isset( $p->CoreListingDetail->OfficeTradeName ) )? $p->CoreListingDetail->OfficeTradeName : '',
			'PropertyStyleCode' => ( isset( $p->Property->PropertyStyleCode ) )? $p->Property->PropertyStyleCode : '',
			'PropertyStyle' => ( isset( $p->Property->PropertyStyle ) )? $p->Property->PropertyStyle : '',
			
			'PropertyLocation' => ( isset( $p->Property->PropertyLocation ) )? $p->Property->PropertyLocation : '',

			
			'AddressLine1' => ( isset( $p->Property->PropertyLocation->AddressLine1 ) )? $p->Property->PropertyLocation->AddressLine1 : '',
			'AddressLine2' => ( isset( $p->Property->PropertyLocation->AddressLine2 ) )? $p->Property->PropertyLocation->AddressLine2 : '',
			'AddressLine3' =>  ( isset( $p->Property->PropertyLocation->AddressLine3 ) )? $p->Property->PropertyLocation->AddressLine3 : '',
			'City' => ( isset( $p->Property->PropertyLocation->City ) )? $p->Property->PropertyLocation->City : '',
			'County' =>( isset( $p->Property->PropertyLocation->County ) )? $p->Property->PropertyLocation->County : '',
			'StateCode' =>( isset( $p->Property->PropertyLocation->StateCode ) )? $p->Property->PropertyLocation->StateCode : '',
			'StateName' =>( isset( $p->Property->PropertyLocation->StateName ) )? $p->Property->PropertyLocation->StateName : '',
			'PostalCode' =>( isset( $p->Property->PropertyLocation->PostalCode ) )? $p->Property->PropertyLocation->PostalCode : '',
			'CountryCode' => ( isset( $p->Property->PropertyLocation->CountryCode ) )? $p->Property->PropertyLocation->CountryCode : '',
			'CountryName' =>( isset( $p->Property->PropertyLocation->CountryName ) )? $p->Property->PropertyLocation->CountryName : '',
			'AddressType' => ( isset( $p->Property->PropertyLocation->AddressType ) )? $p->Property->PropertyLocation->AddressType : '',
			
			
			'PropertyLocationDescription' => ( isset( $p->Property->PropertyLocationDescription ) )? $p->Property->PropertyLocationDescription : '',
			'BuildingArea' => ( isset( $p->Property->BuildingArea ) )? $p->Property->BuildingArea : '',
			
			'Area' =>  ( isset( $p->Property->BuildingArea->Area ) )? $p->Property->BuildingArea->Area : '',
			'AreaUnit' =>  ( isset( $p->Property->BuildingArea->AreaUnit ) )? $p->Property->BuildingArea->AreaUnit : '',
			
			'YearBuilt' => ( isset( $p->Property->YearBuilt ) )? $p->Property->YearBuilt : '',
			'YearRenovated' => ( isset( $p->Property->YearRenovated ) )? $p->Property->YearRenovated : '',
			'LotSize' => ( isset( $p->Property->LotSize ) )? $p->Property->LotSize : '',
			
			'LotArea' => ( isset( $p->Property->LotSize->Area ) )? $p->Property->LotSize->Area : '',
			'LotAreaUnit' => ( isset( $p->Property->LotSize->AreaUnit ) )? $p->Property->LotSize->AreaUnit : '',
			
			'LotDimension' => ( isset( $p->Property->LotDimension ) )? $p->Property->LotDimension : '',
			'PropertyUse' => ( isset( $p->Property->PropertyUse ) )? $p->Property->PropertyUse : '',
			'NoOfParkingPlaces' => ( isset( $p->Property->NoOfParkingPlaces ) )? $p->Property->NoOfParkingPlaces : '',
			'FullBath' => ( isset( $p->Property->FullBath ) )? $p->Property->FullBath : '',
			'HalfBath' => ( isset( $p->HalfBath ) )? $p->HalfBath : '',
			'LastSoldOn' => ( isset( $p->LastSoldOn ) )? $p->LastSoldOn : '',
			'PropertyFeatures' => ( isset( $p->Property->PropertyFeatures->Feature ) )? $p->Property->PropertyFeatures->Feature  : array(),
			'NumberOfLevels' => ( isset( $p->Property->NumberOfLevels ) )? $p->Property->NumberOfLevels : '',
			'Floors' => ( isset( $p->Property->Floors ) )? $p->Property->Floors : '',
			'ZoomLevel' => ( isset( $p->Property->ZoomLevel ) )? $p->Property->ZoomLevel : '',
			'SourcePropertyType' => ( isset( $p->Property->SourcePropertyType ) )? $p->Property->SourcePropertyType : '',
			'VersionNumber' => ( isset( $p->Property->VersionNumber ) )? $p->Property->VersionNumber : '',
			'Zoning' => ( isset( $p->Property->Zoning ) )? $p->Property->Zoning : '',
			'TaxRollNo' => ( isset( $p->Property->ResidentialProperty->TaxRollNo ) )? $p->Property->ResidentialProperty->TaxRollNo : '',
			'NoOfBedrooms' => ( isset( $p->Property->ResidentialProperty->NoOfBedrooms ) )? $p->Property->ResidentialProperty->NoOfBedrooms : '',
			'ThreeQuarterBath' => ( isset( $p->Property->ResidentialProperty->ThreeQuarterBath ) )? $p->Property->ResidentialProperty->ThreeQuarterBath : '',
			'QuarterBath' => ( isset( $p->Property->ResidentialProperty->QuarterBath ) )? $p->Property->ResidentialProperty->QuarterBath : '',
			'Rooms' => ( isset( $p->Property->ResidentialProperty->Rooms ) )? $p->Property->ResidentialProperty->Rooms : '',
			'Schools' => ( isset( $p->Property->ResidentialProperty->Schools ) )? $p->Property->ResidentialProperty->Schools : '',
			'TotalRooms' => ( isset( $p->Property->ResidentialProperty->TotalRooms ) )? $p->Property->ResidentialProperty->TotalRooms : '',
			'BuildingClass' => ( isset( $p->Property->CommercialProperty->BuildingClass ) )? $p->Property->CommercialProperty->BuildingClass : '',
			'BuildingClassCode' => ( isset( $p->Property->CommercialProperty->BuildingClassCode ) )? $p->Property->CommercialProperty->BuildingClassCode : '',
			'NoOfDocks' => ( isset( $p->Property->CommercialProperty->NoOfDocks ) )? $p->Property->CommercialProperty->NoOfDocks : '',
			'CapRatePercent' => ( isset( $p->Property->CommercialProperty->CapRatePercent ) )? $p->Property->CommercialProperty->CapRatePercent : '',
			'MaxContiguousArea' => ( isset( $p->Property->CommercialProperty->MaxContiguousArea ) )? $p->Property->CommercialProperty->MaxContiguousArea  : '',
			'MinDivisibleArea' => ( isset( $p->Property->CommercialProperty->MinDivisibleArea ) )? $p->Property->CommercialProperty->MinDivisibleArea : '',
			'ParkingRatio' => ( isset( $p->Property->CommercialProperty->ParkingRatio ) )? $p->Property->CommercialProperty->ParkingRatio : '',
			'CommonAreaFactor' => ( isset( $p->Property->CommercialProperty->CommonAreaFactor ) )? $p->Property->CommercialProperty->CommonAreaFactor : '',
			'TaxIDNumber' => ( isset( $p->Property->CommercialProperty->TaxIDNumber ) )? $p->Property->CommercialProperty->TaxIDNumber : '',
			'ScheduleIncome' => ( isset( $p->Property->CommercialProperty->ScheduleIncome ) )? $p->Property->CommercialProperty->ScheduleIncome : '',
			'TotalUnits' => ( isset( $p->Property->CommercialProperty->TotalUnits ) )? $p->Property->CommercialProperty->TotalUnits : '',
			'AverageOccupancyRate' => ( isset( $p->Property->CommercialProperty->AverageOccupancyRate ) )? $p->Property->CommercialProperty->AverageOccupancyRate : '',
			'NumberofBallrooms' => ( isset( $p->Property->CommercialProperty->NumberofBallrooms ) )? $p->Property->CommercialProperty->NumberofBallrooms : '',
			'NumberofConferenceRooms' => ( isset( $p->Property->CommercialProperty->NumberofConferenceRooms ) )? $p->Property->CommercialProperty->NumberofConferenceRooms : '',
			'BayDepth' => ( isset( $p->Property->CommercialProperty->BayDepth ) )? $p->Property->CommercialProperty->BayDepth : '',
			'Clearance' => ( isset( $p->Property->CommercialProperty->Clearance ) )? $p->Property->CommercialProperty->Clearance : '',
			'DockHeight' => ( isset( $p->Property->CommercialProperty->DockHeight ) )? $p->Property->CommercialProperty->DockHeight : '',
			'IsGroundLevel' => ( isset( $p->Property->CommercialProperty->IsGroundLevel ) )? $p->Property->CommercialProperty->IsGroundLevel : '',
			'Power' => ( isset( $p->Property->CommercialProperty->Power) )? $p->Property->CommercialProperty->Power : '',
			'TurningRadius' => ( isset( $p->Property->CommercialProperty->TurningRadius ) )? $p->Property->CommercialProperty->TurningRadius : '',
			'IsCrossDocks' => ( isset( $p->Property->CommercialProperty->IsCrossDocks ) )? $p->Property->CommercialProperty->IsCrossDocks : '',
			'HasRailAccess' => ( isset( $p->Property->CommercialProperty->HasRailAccess ) )? $p->Property->CommercialProperty->HasRailAccess : '',
			'IsSubLease' => ( isset( $p->Property->CommercialProperty->IsSubLease ) )? $p->Property->CommercialProperty->IsSubLease : '',
			'IsSprinkler' => ( isset( $p->Property->CommercialProperty->IsSprinkler ) )? $p->Property->CommercialProperty->IsSprinkler : '',
			'AgriculturalPropertyNumber' => ( isset( $p->Property->CommercialProperty->AgriculturalPropertyNumber) )? $p->Property->CommercialProperty->AgriculturalPropertyNumber : '',
			'AverageFloorSize' => ( isset( $p->Property->CommercialProperty->AverageFloorSize ) )? $p->Property->CommercialProperty->AverageFloorSize : '',
			'ColumnSpacing' => ( isset( $p->Property->CommercialProperty->ColumnSpacing ) )? $p->Property->CommercialProperty->ColumnSpacing : '',
			'CeilingHeight' => ( isset( $p->Property->CommercialProperty->CeilingHeight ) )? $p->Property->CommercialProperty->CeilingHeight : '',
			'AnchorStores' => ( isset( $p->Property->CommercialProperty->AnchorStores) )? $p->Property->CommercialProperty->AnchorStores : '',
			'SuiteApartmentName' => ( isset( $p->Property->CommercialProperty->SuiteApartmentName ) )? $p->Property->CommercialProperty->SuiteApartmentName : '',
			'SubUnits' => ( isset( $p->Property->CommercialProperty->SubUnits ) )? $p->Property->CommercialProperty->SubUnits : '',
			'SchoolElementary' => '',
			'SchoolMiddle' => '',
			'SchoolHigh' => '',
			
			'PropertySubType' => ( isset( $p->Property->PropertySubType->Name ) )? $p->Property->PropertySubType->Name : '',
			'PropertyListingFeatures' => '',
			
			'PropertyType' => ( isset( $p->Property->PropertyType->Type ) )? $p->Property->PropertyType->Type : '',
			'Latitude' => ( isset( $p->Property->GeographicData->Latitude ) )? $p->Property->GeographicData->Latitude : '',
			'Longitude' => ( isset( $p->Property->GeographicData->Longitude ) )? $p->Property->GeographicData->Longitude : '',
			'TotalAcres' => ( isset( $p->Property->TotalAcres ) )? $p->Property->TotalAcres : '',
			'defaultPropertyName' => ( isset( $p->Property->defaultPropertyName ) )? $p->Property->defaultPropertyName : '',
			'PricePerArea' => ( isset( $p->PricePerArea ) )? $p->PricePerArea : '',
			'FullyLeasedIncome' => ( isset( $p->FullyLeasedIncome ) )? $p->FullyLeasedIncome : '',
			'TaxYear' => ( isset( $p->Property->TaxYear ) )? $p->TaxYear : '',
			'AnnualTax' => ( isset( $p->Property->AnnualTax ) )? $p->Property->AnnualTax : '',
			'AdditionalMLS' => ( isset( $p->AdditionalMLS ) )? $p->AdditionalMLS : '',
			'AlternateListPrice' => ( isset( $p->AlternateListPrice->Amount ) )? $p->AlternateListPrice->Amount : '',
			'AlternateListPriceCurrency' => ( isset( $p->AlternateListPrice->Currency ) )? $p->AlternateListPrice->Currency : '',
		);
		
		$schools = $this->get_field_value('Schools');
		
		if ( ! empty( $schools->School ) && is_array( $schools->School ) ){
				
			foreach( $schools->School as $school ){
				
				if ( isset( $school->SchoolType ) ){
					
					switch( $school->SchoolType ){
						case 'Elementary School':
							$this->fields['SchoolElementary'] = $school->SchoolName;
							break;
						case 'Middle School':
							$this->fields['SchoolMiddle'] = $school->SchoolName;
							break;
						case 'High School':
							$this->fields['SchoolHigh'] = $school->SchoolName;
							break;
					} // end swithc
					
				} // end if
				
			} // end foreach
		
		} // end if	
		
		$feature_array = $this->get_property_features_array();
		
		$p_features = array();
		
		foreach( $feature_array as $feature_set ){
			
			$p_features[] = $feature_set['feature_name'];
			
		} // end foreach
		
		$this->fields['PropertyListingFeatures'] = implode( ',' , $p_features );
		
	} // end set_from_crest
	
	
	public function get_property_description( $remark ){
		
		if ( is_array( $remark ) ){
			
			$desc = $remark[0]->RemarkText;
			
		} else {
			
			$desc = $remark->RemarkText;
			
		} // end if
		
		return $desc;
		
	} // end $remark
	
	
	protected function get_sql_insert_query(){
		
		$fields = $this->get_fields();
		
		$values = array();
		
		$keys = array();
		
		$exclude = $this->exclude_db;
		
		foreach( $fields as $key => $value ){
			
			if ( ! in_array( $key, $exclude ) ){
			
				if ( is_array( $value ) || is_object( $value ) ){
					
					$value = json_encode( $value, JSON_HEX_APOS );
					
				} // end if 
				
				$values[] = "'" . $this->connection->real_escape_string( $value ) . "'";
				
				$keys[] = $key;
			
			} // end if
			
		} // end foreach
		
		$sql = "INSERT INTO crest_properties (" . implode( ',', $keys ) . ",wovaxUpdated ) VALUES ( " . implode( ',', $values  ) . ", now() )";
		
		return $sql;
		
	} // end get_sql_insert_query
	
	
	public function to_db(){
		
		$status = $this->get_field_value('Status');
		
		if ( in_array( $status , $this->delete_status ) ){
			
			$this->remove_property();
			
		} else {
			
			$this->insert();
			
		} // end if
		
		
	} // end to_db
	
	
	public function remove_property(){
		
		$property_id = $this->get_field_value('Property_ID');
		
		$tables = array(
			'crest_properties',
			'crest_property_agents',
			'crest_property_features',
			'crest_property_georegions',
			'crest_property_images',
			'crest_property_remarks',
			'crest_property_schools',
		);
		
		foreach( $tables as $table ){
			
			$sql = "DELETE FROM $table WHERE Property_ID='$property_id'";
			
			$this->connection->query( $sql );
			
		} // end foreach
		
	} // end delete_property_by_id
	
	
	public function insert(){
		
		$this->insert_agents();
		
		$this->insert_property_features();
		
		$this->insert_regions();
		
		$this->insert_images();
		
		$this->insert_remarks();
		
		$this->insert_schools();
		
		$this->insert_property();
		
	} // end insert
	
	
	public function insert_agents(){
		
		$agents = $this->get_field_value('AssociatedAgents');
		
		if ( isset( $agents->Agent ) ){
			
			if ( is_array( $agents->Agent ) ){
				
				foreach( $agents->Agent as $agent ){
					
					$insert_agent = $this->get_agent( $agent );
					
					$this->insert_property_agent( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $insert_agent['id'], $insert_agent['is_primary']);
					
					$this->insert_agent_record( $insert_agent['id'], $insert_agent['name'], $insert_agent['team_id'], $insert_agent['staff_id'] );
					
				} // end foreach;
				
			} else {
				
				$insert_agent = $this->get_agent( $agents->Agent );
				
				$this->insert_property_agent( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $insert_agent['id'], $insert_agent['is_primary'] );
				
				$this->insert_agent_record( $insert_agent['id'], $insert_agent['name'], $insert_agent['team_id'], $insert_agent['staff_id'] );
				
			} // end if
			
		} // end if
		
	} // end insert_update_properties
	
	
	public function insert_property_agent( $property_id, $mls_id, $agent_id, $is_primary ){
		
		$sql = "INSERT INTO crest_property_agents (Property_ID, MLS_ID, agent_id,is_primary) VALUES ( '$property_id','$mls_id','$agent_id','$is_primary' )";
		
		$this->connection->query( $sql );
		
	} // end insert_image
	
	
	public function insert_agent_record( $agent_id, $agent_name, $team_id, $staff_id){
		
		if ( ! $this->check_existing( 'crest_agents', 'agent_id', $agent_id ) ){
		
			  $sql = "INSERT INTO crest_agents (agent_id, agent_display_name, team_id, staff_id) VALUES ( '$agent_id','$agent_name','$team_id','$staff_id' )";
			  
			  $this->connection->query( $sql );
		
		} // end if
		
	} // end insert_image
	
	
	protected function get_agent( $agent ){
		
		$insert_agent = array(
			'id' => ( isset( $agent->AgentId ) ) ? $agent->AgentId : '',
			'is_primary' => ( isset( $agent->IsPrimary ) ) ? $agent->IsPrimary : 0,
			'name' => ( isset( $agent->Name->DisplayName ) ) ? $agent->Name->DisplayName : '',
			'team_id' => ( isset( $agent->TeamId ) ) ? $agent->TeamId : '',
			'staff_id' => ( isset( $agent->StaffId ) ) ? $agent->StaffId : '', 
		);
		
		return $insert_agent;
		
	} // end get_agent
	
	
	protected function get_primary_agent( $agents ){
		
		$primary = array(
			'name' 	=> '',
			'crest_id' 	=> '',
			'staff_id' => '',
		);
		
		if ( is_array( $agents ) ){
		
			foreach( $agents as $agent ){
				
				if ( isset( $agent->IsPrimary ) && $agent->IsPrimary ){
					
					$primary['name'] = isset( $agent->Name->DisplayName )? $agent->Name->DisplayName : '';
					$primary['crest_id'] = isset( $agent->AgentId )? $agent->AgentId : '';
					$primary['staff_id'] = isset( $agent->StaffId )? $agent->StaffId : '';
					
					break;
					
				} // end if
				
			} // end foreach
		
		} else {
			
			$primary['name'] = isset( $agents->Name->DisplayName )? $agents->Name->DisplayName : '';
			$primary['crest_id'] = isset( $agents->AgentId )? $agents->AgentId : '';
			$primary['staff_id'] = isset( $agents->StaffId )? $agents->StaffId : '';
			
		}// end if
		
		return $primary;
		
	} // end 
	
	
	public function insert_property_features(){
		
		$features = $this->get_field_value('PropertyFeatures');
		
		if ( $features && is_array( $features ) ){
			
			foreach( $features as $feature ){
				
				if ( is_array( $feature ) ){
					
					foreach( $feature as $sub_feature ){
						
						$group_name = ( isset( $sub_feature->FeatureGroupName ) )? $sub_feature->FeatureGroupName : '';
						$feature_name = ( isset( $sub_feature->FeatureName ) )? $sub_feature->FeatureName : '';
						$feature_code = ( isset( $sub_feature->FeatureCode ) )? $sub_feature->FeatureCode : ''; 
						
						$this->insert_property_feature( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $group_name, $feature_name, $feature_code ); 
						
					} // end foreach
					
				} else {
					
					$group_name = ( isset( $feature->FeatureGroupName ) )? $feature->FeatureGroupName : '';
					$feature_name = ( isset( $feature->FeatureName ) )? $feature->FeatureName : '';
					$feature_code = ( isset( $feature->FeatureCode ) )? $feature->FeatureCode : ''; 
					
					$this->insert_property_feature( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $group_name, $feature_name, $feature_code );
					
				}
				
			} // end foreach
			
		} // end if	
		
	} // end insert_update_properties
	
	
	public function get_property_features_array(){
		
		$p_array = array();
		
		$features = $this->get_field_value('PropertyFeatures');
		
		if ( $features && is_array( $features ) ){
			
			foreach( $features as $feature ){
				
				if ( is_array( $feature ) ){
					
					foreach( $feature as $sub_feature ){
						
						$group_name = ( isset( $sub_feature->FeatureGroupName ) )? $sub_feature->FeatureGroupName : '';
						$feature_name = ( isset( $sub_feature->FeatureName ) )? $sub_feature->FeatureName : '';
						$feature_code = ( isset( $sub_feature->FeatureCode ) )? $sub_feature->FeatureCode : '';
						
						$p_array[] = array(
							'group_name' => $group_name,
							'feature_name' => $feature_name,
							'feature_code' => $feature_code,
						); 
						
					} // end foreach
					
				} else {
					
					$group_name = ( isset( $feature->FeatureGroupName ) )? $feature->FeatureGroupName : '';
					$feature_name = ( isset( $feature->FeatureName ) )? $feature->FeatureName : '';
					$feature_code = ( isset( $feature->FeatureCode ) )? $feature->FeatureCode : ''; 
					
					$p_array[] = array(
						'group_name' => $group_name,
						'feature_name' => $feature_name,
						'feature_code' => $feature_code,
					); 
					
				}
				
			} // end foreach
			
		} // end if	
		
		return $p_array;
		
	}
	
	
	public function insert_property_feature( $listing_id, $mls_id, $group_name, $name, $code ){
		
		if ( ! $this->check_existing( 'crest_property_features', 'Property_ID', $listing_id, 'FeatureCode', $code ) ){
		
			$sql = "INSERT INTO crest_property_features (MLS_ID, Property_ID, FeatureName, FeatureGroup, FeatureCode ) VALUES ('$mls_id','$listing_id','$name','$group_name','$code' )";
		
			$this->connection->query( $sql );
		
		} // end if
		
	} // end insert_image
	
	
	public function insert_regions(){
		
		$regions = $this->get_field_value('GeographicRegions');
		
		if ( isset( $regions->GeographicArea ) ){
			
			if ( is_array( $regions->GeographicArea ) ){
				
				foreach( $regions->GeographicArea as $region ){
					
					$insert_region = $this->get_region( $region );
					
					$this->insert_region( $this->get_field_value('MLS_ID'), $this->get_field_value('Property_ID'), $insert_region['type_code'], $insert_region['area_type'] );
					
				} // end foreach;
				
			} else {
				
				$insert_region = $this->get_region( $regions->GeographicArea );
				
				$this->insert_region( $this->get_field_value('MLS_ID'), $this->get_field_value('Property_ID'), $insert_region['type_code'], $insert_region['area_type'] );
				
			} // end if
			
		} // end if
		
	} // end insert_update_properties
	
	
	protected function insert_region( $mls_id, $property_id, $type_code, $area){
		
		if ( ! $this->check_existing( 'crest_property_georegions', 'Property_ID', $property_id, 'type_code', $type_code ) ){
		
			$sql = "INSERT INTO crest_property_georegions (MLS_ID,Property_ID, type_code, area_type) VALUES ( '$mls_id','$property_id','$type_code','$area' )";
		
			$this->connection->query( $sql );
		
		} // end if
		
	} // end insert_image
	
	
	protected function get_region( $region ){
		
		$insert_region = array(
			'type_code' => ( isset( $region->GeographicAreaTypeCode ) ) ? $region->GeographicAreaTypeCode : '',
			'area_type' => ( isset( $region->GeographicAreaType ) ) ? $region->GeographicAreaType : '',
		);
		
		return $insert_region;
		
	} // end get_region
	
	
	public function insert_images(){
		
		$images = $this->get_field_value('ListingMedia');
		
		if ( $images && is_array( $images ) ){
			
			foreach( $images as $image ){
				
				if ( isset( $image->MediaFormat ) && 'Image' == $image->MediaFormat ){
					
					if ( isset( $image->URL ) ){
						
						$this->insert_image(  $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $image->URL );
						
					} // end if
					
				} // end if
				
			} // end foreach
			
		} // end if
		
	} // end insert_update_properties
	
	
	public function insert_image( $listing_id, $mls_id, $image_url ){
		
		if ( ! $this->check_existing( 'crest_property_images', 'image', $image_url, 'Property_ID', $listing_id ) ){
		
			$sql = "INSERT INTO crest_property_images (image, MLS_ID, Property_ID) VALUES ( '$image_url','$mls_id','$listing_id' )";
			
			$this->connection->query( $sql );
		
		} // end if
		
	} // end insert_image
	
	
	public function insert_remark( $listing_id, $mls_id, $remark_name, $text ){
		
		if ( ! $this->check_existing( 'crest_property_remarks', 'Property_ID', $listing_id, 'remark_name', $remark_name ) ){
		
			$sql = "INSERT INTO crest_property_remarks (MLS_ID, Property_ID, remark_name, text ) VALUES ('$mls_id','$listing_id','$remark_name','$text' )";
		
			$this->connection->query( $sql );
		
		} // end if
		
	} // end insert_image
	
	
	public function insert_remarks(){
		
		$listing_remarks= $this->get_field_value('ListingRemarks');
		
		$remarks = ( isset( $listing_remarks->Remark ) ) ? $listing_remarks->Remark : '';
		
		if ( ! empty( $remarks ) ){
			
			if ( is_array( $remarks ) ){
				
				foreach( $remarks as $remark ){
					
					$insert_remark = $this->get_remark( $remark );
					
					$this->insert_remark( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $insert_remark['name'], $insert_remark['text'] );
					
				} // end foreach
				
			} else {
				
				$insert_remark = $this->get_remark( $remarks );
				
				$this->insert_remark( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $insert_remark['name'], $insert_remark['text'] );
				
			} // end if
			
		} // end if
		
	} // end insert_update_properties
	
	
	public function get_remark( $remark ){
		
		$insert_remark = array(
			'name' => ( isset( $remark->RemarkType ) )? $remark->RemarkType : '',
			'text' => ( isset( $remark->RemarkText ) )? $remark->RemarkText : '',
		);
		
		return $insert_remark;
		
	} // end get_remark
	
	public function insert_schools(){
		
		$schools = $this->get_field_value('Schools');
		
		if ( ! empty( $schools->School ) && is_array( $schools->School ) ){
				
			foreach( $schools->School as $school ){
				
				$this->insert_school( $this->get_field_value('Property_ID'), $this->get_field_value('MLS_ID'), $school->SchoolType, $school->SchoolName ); 
				
			} // end foreach
		
		} // end if
		
	} // end insert_data
	
	
	public function insert_school( $listing_id, $mls_id, $type, $name ){
		
		if ( ! $this->check_existing( 'crest_property_schools', 'Property_ID', $listing_id, 'name', $name ) ){
		
			$sql = "INSERT INTO crest_property_schools (Property_ID, MLS_ID, type, name ) VALUES ('$listing_id','$mls_id','$type','$name' )";
		
			$this->connection->query( $sql );
		
		} // end if
		
	} // end insert_image
	
	
	protected function insert_property(){
		
		$property_id = $this->get_field_value('Property_ID');
		
		$is_existing = $this->check_existing( 'crest_properties', 'Property_ID', $property_id );
		
		if ( ! $is_existing ){
		
			$sql = $this->get_sql_insert_query();
		
			$this->connection->query( $sql );
		
		} else {
			
			if ( $this->get_field_value('LastUpdatedDate') && ( strtotime( $this->get_field_value('LastUpdatedDate') ) > strtotime( $is_existing['LastUpdatedDate'] ) ) ) {
			
				$fields = $this->get_fields();
				
				$qvalues = array();
				
				foreach( $fields as $label => $value ){
					
					if ( 'Property_ID' == $label || in_array( $label, $this->exclude_db ) ) continue;
					
					$qvalues[] = $label . "='" .  $value . "'";
					
				} // end foreach
				
				$sql = "UPDATE crest_properties SET " . implode( ',', $qvalue ) . " WHERE Property_ID='$property_id'";
				
				$this->connection->query( $sql );
				
			} // end if
			
		}// end if
		
	} // end insert_property
	
	protected function check_existing( $table, $key, $value, $key2 = false, $value2 = false ){
		
		if ( $key2 ){
			
			$sql = "SELECT * FROM $table WHERE $key='$value' AND $key2='$value2'";
			
		} else {
			
			$sql = "SELECT * FROM $table WHERE $key='$value'";
			
		} // end if
		
		$result = $this->connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			$row = $result->fetch_assoc();
			
			return $row;
			
		} // end if
		
		return false;
		
	} // end check_existing
	
}