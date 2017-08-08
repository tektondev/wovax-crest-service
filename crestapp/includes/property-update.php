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

jQuery('body').on('click','#update-properties', function( event ) { event.preventDefault(); i = ( properties.length - jQuery('#start').val() ); get_property() });

function get_property(){
	
	
	jQuery.get( 
		'',
		{ property_id: properties[i][0], property_type: 'ResidentialSale' },  
		function( response ) {
			
			jQuery('#results').append( '<li>' + ( properties.length - i ) + ' ' + response + '</li>' );

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
<h2>Updated Properties:</h2>
<ul id="results">
</ul>
</body>
</html>