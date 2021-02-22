<?php
namespace app\models\apple;

class StatusControl
{
    protected $storage;
    protected $result;
    
    public function __construct()
    {
        $this->storage[] = new StatusControlOnGround();
    }
    
    /**
     * @param apple/Apple
     * @return bool
     */
    public function makeStatusControl($apple)
    {
        foreach ($this->storage as $statusController) {
            $this->result = $this->result || $statusController->makeStatusControl($apple);
        }
        $result = $this->result;
        $this->result = false;
        return $result;
    }
}