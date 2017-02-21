<?php require_once dirname( dirname( __FILE__ ) ) . '/config.php';
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
var crest = {
	
	base_url: '<?php echo CRESTAPPBASEURL;?>',
	
	init:function(){
		
		crest.form.events();
		
	},
	
	form: {
		
		events:function(){
			
			jQuery( 'form' ).on( 'submit', function( e ) { 
				e.preventDefault();
				crest.msg.add_msg( 'Getting Properties...', 'action' );
				crest.feed.populate.run( 1, 1 );
			});
			
		},
		
	},
	
	feed: {
		
		feed: 1,
		
		populate:{
			
			timer: false,
			
			days: 32,
			
			properties: new Object(),
			
			run:function( day, dpart ){
				
				crest.feed.populate.days = jQuery( '#queue-days' ).val();
				
				if ( day < crest.feed.populate.days ){
				
					var date = new Date( jQuery( '#queue-start-date' ).val() );
					
					start_date = new Date( date );
					
					start_date.setDate( start_date.getDate() - day ); // minus the date
					
					crest.feed.get_properties( start_date, day, dpart );
				
				} else {
					
					//crest.feed.clean_properties();
					
					//var pkeys = Object.keys( crest.feed.populate.properties );
					
					//crest.msg.add_msg( pkeys.length + ' Active Properties Found', 'update' );
					
					crest.msg.add_msg( 'All Done', 'action' );
					
					//crest.feed.save_properties_recursive( false, false );
					
					//crest.feed.add_to_queue();
					
				} // end if
				
			}, // end run
			
			get_properties_callback: function( response, args ){ 
				
				//var count_array = new Array();
				
				var inc = jQuery('#feed-i').val();
				
				try {
				
					var json = JSON.parse( response );
					
					if ( json ){
						
						
					
						var tb_row = '<tr><td>&rarr; ' + args.render_date + ' (' + args.day_part + ' of ' + inc + ' )</td><td>' + json.data + ' Results Found</td></tr>';
						
					} // end if
					
				} catch( e ){
					
					var tb_row = '<tr><td>&rarr; ' + args.render_date + ' (' + args.day_part + ' of ' + inc + ' ) Something Went Wrong</td><td>NA</td></tr>';
					
				} // end catch
				
				
				
				var inc = jQuery('#feed-i').val();
				
				jQuery('#feed-results').prepend( tb_row );

				
				crest.feed.populate.timer = setTimeout( function(){ 
				
					console.log( args );
					
					var inc = jQuery('#feed-i').val();
				
					if ( args.day_part == inc ) { 
					
						args.day_part = 1;
						
						args.day_index = args.day_index + 1;
						
					} else {
						
						args.day_part = args.day_part + 1
						
					};
					
					console.log( args.day_index + ' ' + args.day_part );
					
					crest.feed.populate.run( args.day_index, args.day_part );
					 
				}, 1000 );
				
			}, // end get_properties_callback
			
		}, // end populate
		
		get_properties:function( date, pday, dpart ){
			
			var inc = jQuery('#feed-i').val();
			
			var ptype = jQuery('#types').val();
			
			var set_i = ( 1440 / inc );
			
			var day = date.getDate();
			
  			var month = date.getMonth() + 1;
			
  			var year = date.getFullYear();
			
			//var c_date = day + '-' + month + '-' + year;
			
			var c_date = year + '-' + month + '-' + day;
			
			var feedid = jQuery('#feed-id').val();
			
			console.log( c_date );
			
			var url_params = [];
			
			var smins = ( ( dpart - 1 ) * set_i );
			
			var mins = ( dpart * set_i );
			
			var args = { days: pday, end_time: c_date, render_date: c_date, minutes:mins, minutes_start: smins, day_index: pday, day_part: dpart, type: ptype };
			
			console.log( args );
			
			crest.ajax.request_service( crest.ajax.services.feed_popluate, url_params, args, crest.feed.populate.get_properties_callback );
			
		}, // end get_properties 
		
		//clean_properties:function(){
			
			//console.log( crest.feed.populate.properties );
			
			/*for( var p = 0; p < crest.feed.populate.properties.length; p++ ){
				
				if ( 'DE' == crest.feed.populate.properties[ p ]['status'] ){
					
					delete crest.feed.populate.properties[ p ];
					
				} // end if
				
			} // end for
			
			console.log( crest.feed.populate.properties );*/
			
			
			//for ( var key in crest.feed.populate.properties ) {
				// skip loop if the property is from prototype
				///if ( ! crest.feed.populate.properties.hasOwnProperty( key ) ) continue;
				
				//if ( 'DE' == crest.feed.populate.properties[ key ]['status'] ){
					
					///delete crest.feed.populate.properties[ key ];
					
				///} // end if
				
			///} // end for
			
			///console.log( crest.feed.populate.properties );
			
			
		///}, // end clean_properties
		
		/*add_to_queue: function(){
			
			var data = {
				properties: crest.feed.populate.properties,
				feed_id: 1,
			}; 
			
			crest.ajax.request_service( crest.ajax.services.update_queue, ['insert'], data, crest.feed.populate.add_to_queue_callback );
			
		}, // end add_to_queue
		
		add_to_queue_callback:function( response, data ){
			
			crest.msg.add_msg( 'Success: Poperties Added to Database', 'update' );
			
		}, //end add_to_queue*/
		
		/*save_properties_recursive:function( response, data ){
			
			console.log( crest.feed.populate.properties );
			
			if ( Object.keys( crest.feed.populate.properties ).length ){
				
				var temp_object = new Object(); 
				
				var i = 0;
				
				for ( var key in crest.feed.populate.properties ) {
					
					if ( i == 200 ) break;
					
					if ( ! crest.feed.populate.properties.hasOwnProperty( key ) ) continue;
					
					temp_object[ key ] = crest.feed.populate.properties[ key ];
					
					delete crest.feed.populate.properties[ key ]
					 
					i++;
					
					
				} // end for
				
				/*var temp_array = new Array();
				
				for ( var p = 0; p < 500; p++ ){
					
					if ( ! crest.feed.populate.properties.length ) break;
					
					temp_array.push( crest.feed.populate.properties[ p ] );
					
				} // end for
				
				crest.feed.populate.properties.splice( 0, ( p + 1 ) );
				
				console.log( crest.feed.populate.properties );*/
				
				/*var data = {
					properties: temp_object,
					feed_id: 1,
				}; 
				
				crest.ajax.request_service( crest.ajax.services.update_queue, [1], data, crest.feed.save_properties_recursive );
				
				
			} else {
				
				crest.msg.add_msg( 'Success: Properties Added', 'update' );
				
			}// end if
			
			console.log( crest.feed.populate.properties );
			
		} // end */
		
	}, // end feed
	
	ajax: {
		
		services: {
			
			feed_popluate: 'properties/queue/update.php',
			update_queue: 'properties/insert-queue',
		},
		
		request_service: function( service, url_params, data, callback ){
			
			var request_url = crest.base_url + service;
			
			for( i = 0; i < url_params.length; i++ ){
				
				request_url += '/' + url_params[i];
				
			} // end for
			
			console.log( request_url );
			
			console.log( data );
			
			jQuery.post(
				request_url,
				data,
				function( response ){
					
					console.log( response );
					
					if ( typeof callback != 'undefined' ) {
					
						callback( response, data );
						
					} // end if
					
				}
			);
			
		}, // end request service
		
		
		
	}, // end ajax
	
	msg: {
		
		add_msg: function( html, type ){
			
			jQuery('#msg').prepend('<div class="' + type + '">' + html + '</div>');
			
		}, // end add_msg
		
	}, // end msg
	
}
</script>
<link rel="stylesheet" type="text/css" href="<?php echo CRESTAPPBASEURL;?>style.css">
</head>
<body>
<header>
	<div class="wrap">
		<div class="site-title">Sotheby's Crest Feed</div>
   	</div>
</header>
<main>
	<div class="wrap">
        <form>
        <div class="field">
        	<label>End Date</label>
        	<input type="date" name="start-date" id="queue-start-date" /> 
        </div><div class="field">
        	<label># Days (Counting Back from End Date)</label>
        	<input type="text" name="days" id="queue-days" placeholder="days" value="30" />
        </div><div class="field">
        	<label>Feed ID</label>
        	<input type="text" name="feed_id" id="feed-id" placeholder="ID" value="1" /> 
        </div><div class="field">
        	<label>Property Types</label>
        	<select name="type" id="types">
            	<option value="">All</option>
            	<option value="residential-sale">Residential Sale</option>
                <option value="commercial-lease">Commercial Lease</option>
                <option value="commercial-sale">Commercial Sale</option>
                <option value="residential-rent">Residential Rent</option>
            </select>

        </div><div class="field">
        	<label>&nbsp;</label>
       		<input type="submit" value="go" />
        </div>
        <input type="hidden" name="i" id="feed-i" value="1" />
        </form>
        <div id="msg"></div>
        <table id="feed-results">
        </table>
    </div>
</main>
<script>crest.init();</script>
</body>
</html>