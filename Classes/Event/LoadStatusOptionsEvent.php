<?php


namespace MyParcelCom\ContaoApi\Classes\Event;


use Symfony\Component\EventDispatcher\Event;

class LoadStatusOptionsEvent extends Event
{
    const NAME = "myparcelcom.load.status";
    
    private $options = [];
    
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Adds the given key-value pair to this options. Uses a new key, if the given key is already in use.
     * @param $key
     * @param $value
     */
    public function addOption($key, $value)
    {
        if (!$this->options[$key]) {
            $this->options[$key] = $value;
        } else {
            $this->options[] = $value;
        }
        
    }
}