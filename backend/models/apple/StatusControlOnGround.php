<?php
namespace app\models\apple;

class StatusControlOnGround
{
    protected $storage;
    
    public function makeStatusControl($apple)
    {
        if ($apple->status == 1) {
            if ((time() - $apple->dateFallDown) > 30) {
                $apple->status = 2;
                return true;
            }
        }
    }
}