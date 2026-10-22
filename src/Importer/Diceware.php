<?php
namespace Acme\Importer;

use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Factory;

class Diceware
{
    const DICEWARE_URL = 'http://world.std.com/~reinhold/diceware.wordlist.asc';
    
    const DICEWARE_FILE = 'diceware.wordlist.asc';
    
    /**
     * File system factory
     * 
     * @var Factory
     */
    protected $fsFactory;
    
    /**
     * Guzzle http client
     * 
     * @var Client
     */
    protected $httpClient;
    
    /**
     * Sets the http client
     * 
     * @param Client $client
     * 
     * @codeCoverageIgnore
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
    }
    
    /**
     * Gets the http client
     * 
     * @return Client
     * 
     * @codeCoverageIgnore
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
    
    /**
     * Create a new command instance.
     * 
     * @codeCoverageIgnore
     */
    public function __construct(Factory $fsFactory, Client $guzzleClient)
    {
        $this->setFsFactory($fsFactory);
        $this->setHttpClient($guzzleClient);
    }
    
    /**
     * Sets the filesystem factory
     * 
     * @param Factory $factory
     * 
     * @codeCoverageIgnore
     */
    public function setFsFactory(Factory $factory)
    {
        $this->fsFactory = $factory;
    }
    
    /**
     * Gets the filesystem factory
     * 
     * @return Factory
     * 
     * @codeCoverageIgnore
     */
    public function getFsFactory()
    {
        return $this->fsFactory;
    }
  
    /**
     * Performs import operation
     */
    public function import()
    {
        $this->saveToDisk($this->getFromWeb());
    }
    
    /**
     * Gets the list from the web
     * 
     * @return string
     */
    protected function getFromWeb()
    {
        $response = $this->getHttpClient()->get(self::DICEWARE_URL);
        return (string) $response->getBody();
    }
    
    /**
     * Saves data to disk
     * 
     * @param string $data
     */
    protected function saveToDisk($data)
    {
        $this->getFsFactory()->disk('local')->put(self::DICEWARE_FILE, $data);
    }
}