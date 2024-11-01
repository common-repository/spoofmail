<?php
/*
Plugin Name: SpoofMail
Plugin URI: <a href="http://www.fubra.com">Ray Viljoen</a>
Description: Validate an email address' structure, check MX-Records and check against known spoof/temporary email domains. E.g. mailinator.com, tempemail.net etc.
Version: 1.0.0
Author: Ray Viljoen
Author URI: https://github.com/RayViljoen
*/

// � 2009-2011 Fubra Limited, all rights reserved.

/*

Changelog:
v1.0: Initial release

*/

class rv_spoofmail {

	/**
	 *
	 *
	 * @var string $url The url to this plugin
	 */
	public $url = '';
	/**
	 *
	 *
	 * @var string $urlpath The path to this plugin
	 */
	public $urlpath = '';

	/**
	 *
	 *
	 * @var array $domains Array of known spoof domains
	 */
	private $domains = '';

	/**
	 *
	 *
	 * @var string Email regex
	 */
	private $emailregex = '/^[a-z0-9\._-]+@([a-z0-9][a-z0-9-]*[a-z0-9]\.)+([a-z]+\.)?([a-z]+)$/i';

	/**
	 *
	 *
	 * @var string Email address to validate
	 */
	private $email = '';

	//Class Functions

	/**
	 * PHP 5 Constructor
	 */
	function __construct( $email = NULL ) {

		// Set up ivars
		$this->url = plugins_url( basename( __FILE__ ), __FILE__ );
		$this->urlpath = plugins_url( '', __FILE__ );
		$this->domains = $this->get_domains();
		$this->email = $email;
	}

	/**
	 *
	 *
	 * @method Check mx record
	 * @param unknown $email
	 * @return BOOL
	 */
	function verify_mx() {

		// Get emil parts
		@list( $prefix, $domain ) = split( "@", $this->email );

		// Check mx records with getmxrr or use fsockopen if on WIN... :|
		if ( function_exists( "getmxrr" ) && getmxrr( $domain, $mxhosts ) ) return true;
		elseif ( @fsockopen( $domain, 25, $errno, $errstr, 5 ) ) return true;
		else return false;
	}

	/**
	 *
	 *
	 * @method Verify email address structure.
	 * @return BOOL
	 */
	function verify_address() {
		return preg_match( $this->emailregex, $this->email ) ? true : false;
	}

	/**
	 *
	 *
	 * @method Check email agains banned domains
	 * @param unknown $email
	 * @return BOOL
	 */
	function verify_domain() {
		$domain = array_pop( explode( '@', $this->email ) );
		return !in_array( $domain, $this->domains );
	}

	/**
	 *
	 *
	 * @method Return arrray of banned domains
	 */
	private function get_domains() {
		$json = file_get_contents( dirname( __FILE__ ).'/domains.json' );
		return json_decode( $json );
	}

	/**
	 *
	 *
	 * @method Load jQuery/plugin
	 */
	static function load_scripts() {

		$inst = new self();
		wp_enqueue_script( 'jquery' );
		// Load js validation script
		wp_enqueue_script( 'spoofmail', plugins_url( './spoofmail.js', __FILE__ ) );
		// Localize some php variables to jQuery plugin
		wp_localize_script( 'spoofmail', 'verificationURL', $inst->urlpath.'/jsresponder.php' );
	}

} //End Class

// Load jquery plugin
add_action( 'wp_enqueue_scripts', array( 'rv_spoofmail', 'load_scripts' ) );

// Create global function to check email
function sm_verify_email( $email, $callback = NULL ) {

	// Create instance
	$rv_spoofmail = new rv_spoofmail( $email );

	// Return BOOL
	$res = FALSE;

	// Error message
	$err = array();

	// Do checks

	// Validate structure
	if ( !$rv_spoofmail->verify_address() ) $err[] = 'invalid email address';
	// Get MX
	if ( !$rv_spoofmail->verify_mx() ) $err[] = 'failed mx-record';
	// Check banned domain
	if ( !$rv_spoofmail->verify_domain() ) $err[] = 'banned domain';

	// Set error array to NULL if empty and result to true
	if ( empty( $err ) ) {
		$err = NULL;
		$res = TRUE;
	}

	// Check if a callback is provided
	if ( $callback ) $callback( $err );

	return $res;
}
