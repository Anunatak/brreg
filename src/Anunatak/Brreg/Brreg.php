<?php namespace Anunatak\Brreg;

use GuzzleHttp\Client;
use App;

class Brreg {

	/**
	 * The endpoint to the API
	 * 
	 * @var string Endpoint URL
	 * @access protected
	 */
	protected static $endpoint = 'http://data.brreg.no/enhetsregisteret/enhet.json';

	/**
	 * Filtering options
	 * 
	 * @var array Filter options
	 * @access protected
	 */
	protected static $filter = [ 'name' => 'startswith(navn,%s)' ];

	/**
	 * Retrieve information about a company
	 *
	 * @see https://confluence.brreg.no/display/DBNPUB/API
	 * 
	 * @param string $name The input from the user
	 * @param string $type The filter type
	 * 
	 * @return array An array of data
	 * @access public
	 */
	public static function getFilterResult( $name, $type = 'name' ) 
	{
		$url = self::$endpoint . '?$filter=' . urlencode( sprintf( self::$filter[$type], "'".$name."'" ) );
		return self::request($url);
	}


	/**
	 * Perform a request using the GuzzleHttp Client
	 * 
	 * @param string $url The URL
	 * 
	 * @return array An array of data
	 * @access private
	 */
	private static function request( $url, $ssl = false ) {
		
		// create an instance of a guzzle client
		$client = new Client();

		$config = [];

		if( $ssl ) {
			$config = ['config' => [
		        'curl' => [
		            CURLOPT_SSLVERSION => 3,
		            CURLOPT_PORT => 443,
		            CURLOPT_SSL_CIPHER_LIST => 'SSLv3'
		        ]
		    ]];
		}

		// try getting a response and set the correct curl options
		$response = $client->get( $url, $config);

		return $response->json();

	}

}

?>