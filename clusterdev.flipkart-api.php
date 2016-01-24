<?php
namespace clusterdev;
class Flipkart
{
	//Affiliate ID and token are entered through the constructor
    private $affiliateId;
    private $token;
    private $response_type;
    private $api_base = 'https://affiliate-api.flipkart.net/affiliate/api/';
    private $verify_ssl   = false;
 
    function __construct($affiliateId, $token, $response_type="json")
    {
        $this->affiliateId = $affiliateId;
        $this->token = $token;
        $this->response_type = $response_type;
        //Add the affiliateId and response_type to the base URL to complete it.
        $this->api_base.= $this->affiliateId.'.'.$this->response_type;
    }
 
    public function api_home(){
        return $this->sendRequest($this->api_base);
    }

    public function call_url($url){
        return $this->sendRequest($url);
    }
    /**
     * Sends the HTTP request using cURL.
     * 
     * @param string $url The URL for the API
	 * @param int $timeout Timeout before the request is cancelled.
     * @return string Response from the API
     **/
    private function sendRequest($url, $timeout=30){
    	//Make sure cURL is available
    	if (function_exists('curl_init') && function_exists('curl_setopt')){
	        //The headers are required for authentication
	        $headers = array(
	            'Cache-Control: no-cache',
	            'Fk-Affiliate-Id: '.$this->affiliateId,
	            'Fk-Affiliate-Token: '.$this->token
	            );
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-ClusterDev-Flipkart/0.1');
	        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        $result = curl_exec($ch);
	        curl_close($ch);
	        return $result ? $result : false;
	    }else{
            //Cannot work without cURL
			return false;
	    }        
    }
}
?>
