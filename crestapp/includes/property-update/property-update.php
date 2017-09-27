<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
a {
	display: inline-block;
	padding: 20px 40px;
	color: #fff;
	font-weight: bold;
	background-color: blue;
	border-radius: 6px;
	text-decoration: none;
	text-transform: uppercase; 
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
<script>

var properties = [<?php echo implode(',', $properties );?>];

var i = 0;

jQuery('body').on('click','#update-properties', function( event ) { event.preventDefault(); i = jQuery('#start').val(); get_property() });

function get_property(){
	
	console.log( properties[0][0] );
	
	console.log( i );
	
	console.log(properties.length);
	
	console.log( jQuery('#start').val() );
	
	var p_type = ( jQuery('#property_type').val() ) ? jQuery('#property_type').val() : 'ResidentialSale';
	
	var force_update = jQuery('#force_update').val();
	
	var data = { property_id: properties[i][0], property_type: p_type, f_update: force_update };
	
	console.log( data );
	
	jQuery.get( 
		'',
		data,  
		function( response ) {
			
			jQuery('#results').append( '<li>' + response + '</li>' );
			
			var percent = Math.floor( ( i / ( properties.length - 1 ) ) * 100);
			
			jQuery('#percent').html( percent + '%' );

			i++;
			
			get_property();	
 
		}
	);
	
} // End 



</script>
<p>
<a href="#" id="update-properties">Update Properties</a>
</p><p>
Start at: <input type="text" id="start" value="0" /> (in case you need to restart).
</p>
<p>
Force Property Type:
<select id="property_type">
	<option selected="selected" value="">Any</option>
	<option value="ResidentialSale">ResidentialSale</option>
	<option value="CommercialSale">CommercialSale</option>
	<option value="ResidentialRental">ResidentialRental</option>
	<option value="CommercialLease">CommercialLease</option>
	</select></p>
<p>
Force Update:
<select id="force_update">
	<option selected="selected" value="0">Auto</option>
	<option value="1">Force Update</option>
	</select></p>
<h2>Updated Properties:</h2>
<p><span id="percent">0%</span> Completed</p>
<ul id="results">
</ul>
</body>
</html>