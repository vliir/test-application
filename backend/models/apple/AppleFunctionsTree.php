<?php
namespace app\models\apple;

class AppleFunctionsTree
{
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function eat($state = null, $args = null)
    {
        throw new \Exception('Съесть нельзя, яблоко на дереве', 0);
    }
    
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function fallToGround($state = null, $args = null)
    {
        if ($state !== null) $state->status = 1;
        $state->dateFallDown = time();
    }
    
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function delete($state = null, $args = null)
    {
        throw new \Exception('Яблоко висящее на дереве удалить нельзя', 12);
    }
}