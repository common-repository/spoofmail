<?php
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Content-type: application/json' );

// Require wp-load
require_once '../../../wp-load.php';

// Get email if set
$email = isset( $_GET['email'] ) ? $_GET['email'] : NULL;

// Init response array
$response = array();

// If email missing
if ( !$email ) {

	$response['test'] = class_exists( 'rv_spoofmail' );
	$response['status'] = 'error';
	$response['responseText'] = 'email required';

	echo json_encode( $response );

	// Email supplied, so check email
} else {

	sm_verify_email( $email, function( $err ) {

			if ( $err ) {

				$response['email'] = 'Failed';
				$response['errors'] = $err;

			} else {

				$response['email'] = 'Passed';
				$response['errors'] = NULL;
			}

			echo json_encode( $response );
		} );
}
