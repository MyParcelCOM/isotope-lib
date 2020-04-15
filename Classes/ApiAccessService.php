<?php

namespace MyParcelcom\ContaoApi\ApiAccessService;

class ApiAccessService
{
    private $clientID = "";
    
    private $clientSecret = "";
    
    private $url = "";
    
    // are we authenticated yet?
    private $authenticated = false;
    
    /**
     * ApiAccessService constructor.
     * @param string $clientID
     * @param string $clientSecret
     * @param string $url
     */
    public function __construct(string $clientID, string $clientSecret, string $url)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->url = $url;
    }
    
    public function authenticate()
    {
    
    }
    
    public function createShipment()
    {
    
    }
    
    public function registerShipment()
    {
    
    }
}