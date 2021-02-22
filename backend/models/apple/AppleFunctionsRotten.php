<?php
namespace app\models\apple;

class AppleFunctionsRotten
{
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function eat($state = null, $args = null)
    {
        throw new \Exception('Нельзя есть, яблоко гнилое', 0);
    }
    
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function fallToGround($state = null, $args = null)
    {
        throw new \Exception('Яблоко не может упасть, оно и так лежит на земле и уже сгнило.', 11);
    }
    
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function delete($state = null, $args = null)
    {
        $state->deleted = true;
    }
}