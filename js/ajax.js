( function( $, plugin ) {
	"use strict";

	// Working with promises to bubble event later than core.
	$.when( someObjectWithEvents ).done( function() {
		console.log( 'AJAX request done.' );
	} )
		.then( function() {
			setTimeout( function() {
				console.log( 'AJAX requests resolved.' );
			}, 500 );
		} );

	// Same as above, but happens as onClick Action
	$( '#form-button' ).on( 'click', function() {
		// Debug: return; here if we need to debug/var_dump the save_post callback in PHP.
		// return;

		// Working with promises to bubble event later than core.
		$.when( someObjectWithEvents ).done( function() {
			setTimeout( function() {
				console.log( 'AJAX requests done. Happened after onClick event.' );
			}, 2000 );
		} );
		// One can happily use .then() here as well.
	} );

	// Alternate solution: jQuery.ajax()
	// One can use $.post(), $.getJSON() as well
	// I prefer defered loading & promises as shown above
	$.ajax( {
		 url  : plugin.ajaxurl,
		 data : {
			action      : plugin.action,
			_ajax_nonce : plugin._ajax_nonce,
			// WordPress JS-global
			// Only set in admin
			postType     : typenow,
		 },
		 beforeSend : function( d ) {
		 	console.log( 'Before send', d );
		 }
	} )
		.done( function( response, textStatus, jqXHR ) {
			console.log( 'AJAX done', textStatus, jqXHR, jqXHR.getAllResponseHeaders() );
		} )
		.fail( function( jqXHR, textStatus, errorThrown ) {
			console.log( 'AJAX failed', jqXHR.getAllResponseHeaders(), textStatus, errorThrown );
		} )
		.then( function( jqXHR, textStatus, errorThrown ) {
			console.log( 'AJAX after finished', jqXHR, textStatus, errorThrown );
		} );
} )( jQuery, ajaxexampleObject || {} );