<?php

/**
  * Copyright 2011 SinglePlatform
  *
  * This is a library to interface the RestApi
  *
  * After initializing the apiInterface object,
  * the programmer can call methods that interact
  * with the RestApi directly:
  *
  * Public Methods:
  *     - searchLocation( $terms )
  *     - getLocation( $locationId )
  *     - getMenus( $locationId )
  *
  *
  * Necessary object initialization parameters:
  *     - signingKey: given to publishers
  *     - clientId: given to publishers
  *
  * Optional object initialization parameters:
  *     - fqdn: The fully qualified domain name (i.e. 'admin.singleplatform.co')
  *         - Default: 'localhost'
  *     - protocol: What type of protocol to run (i.e. 'http')
  *         - Default: 'http'
  *     - debugEnabled: Turn debugging information on or off (true/false)
  *         - Default: true
  *
  * For more usage information:
  *     See bottom of file for usage examples
  *
  */

class SP_ApiLibrary
{
    private $apiUrl;
    private $contentType;
    private $acceptType;

    private $signingKey;
    private $clientId;
    private $debugEnabled;

    public function __construct( $signingKey, $clientId, $fqdn='localhost' , $protocol='http', $debugEnabled=true )
    {
        $this->apiUrl = "$protocol://$fqdn";
        $this->contentType = 'Content-Type: application/json';
        $this->acceptType = 'Accept: application/json';

        $this->signingKey = $signingKey;
        $this->clientId = $clientId;

        $this->debugEnabled = $debugEnabled;
    }

    /**
      * Search the database with a query.
      *
      * @param  terms       Search terms seperated by spaces.
      * @return             An array of location info
      */
    public function searchLocation( $terms, $page=0, $count=100 )
    {
        $terms = str_replace( " " , "+", $terms );
        $url = $this->createUrl( "/search?q=$terms&page=$page&count=$count" );

        $retVal = $this->doGet($url);
        if ($this->debugEnabled) {
            print "Dumping location return values:\n";
            var_dump($retVal);
        }
        if ($retVal['code'] !== 200) {
            print ("Location does not exist: [$locationId]" .  PHP_EOL);
            return false;
        }
        else {
            $resp = json_decode($retVal['content'], true);
            return $resp;
        }
    }

    /**
      * Get information about a location.
      *
      * @param  locationId  The ID of the requested location
      * @return             An array of location info
      */
    public function getLocation( $locationId )
    {
        $url = $this->createUrl( "/$locationId" );

        $retVal = $this->doGet($url);
        if ($this->debugEnabled) {
            print "Dumping location return values:\n";
            var_dump($retVal);
        }
        if ($retVal['code'] !== 200) {
            print ("Location does not exist: [$locationId]" .  PHP_EOL);
            return false;
        }
        else {
            $resp = json_decode($retVal['content'], true);
            return $resp;
        }
    }

    /**
      * Get all the menus for a location
      *
      * @param  locationId  The ID of the requested location
      * @return             An array of menu info for all menus in location
      */
    public function getMenus( $locationId )
    {
        $url = $this->createUrl( "/$locationId/menu" );

        $retVal = $this->doGet($url);
        if ($this->debugEnabled) {
            print "Dumping menu return values:\n";
            var_dump($retVal);
        }
        if ($retVal['code'] !== 200) {
            print ("Menu does not exist for location: [$locationId]" .  PHP_EOL);
            return false;
        }
        else {
            $resp = json_decode($retVal['content'], true);
            return $resp;
        }
    }
    protected static function signPayload( $payload, $signingKey )
    {
        $binaryKey = base64_decode(str_pad(strtr($signingKey, '-_', '+/'), strlen($signingKey) % 4, '=', STR_PAD_RIGHT));
        $binarySignature = hash_hmac( "sha1", $payload, $binaryKey, true );
        $signature = rtrim(strtr(base64_encode($binarySignature), '+/', '-_'), '=');
        return $signature;
    }

    protected function createUrl( $path )
    {
        $sep = ( strpos($path, '?') !== false ) ? '&' : '?';
        $queries = array();
        array_push( $queries,  "client=" . $this->clientId );

        $package = "/locations" . $path . $sep . implode('&', $queries);
        $signature = $this->signPayload( $package, $this->signingKey );
        array_push( $queries, "sig=" . $signature );

        $url = $this->apiUrl . "/locations" . $path . $sep . implode('&', $queries);
        return $url;
    }

    protected function doGet( $url )
    {
        return $this->doIt($url, null, "GET");
    }

    protected function doIt( $url, $payload, $verb )
    {

        if ($this->debugEnabled) {
            print("* $verb $url" . PHP_EOL);
        }

        $c = curl_init($url);
        $headers = array($this->acceptType);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, $verb );

        if ( $payload !== null ) {
            if( is_string($payload) ) {
                curl_setopt($c, CURLOPT_POSTFIELDS, $payload );
                array_push( $headers, $this->contentType );
            }
            else {
                die("Payload not properly encoded to json for url=[$url]!");
            }
        }

        curl_setopt($c, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        if ($this->debugEnabled) {
            curl_setopt($c, CURLOPT_VERBOSE, true );
        }

        $content = curl_exec($c);
        $code = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        return array('content' => $content, 'code' => $code);
    }

}

