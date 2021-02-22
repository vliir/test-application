<?php
namespace app\models\apple;

class AppleFunctionsGround
{
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function eat($state = null, $args = null)
    {
        if (($state !== null) && ($args !== null)) {
            $percent = $args[0];
            if ($percent > $state->size) throw new \Exception('Вы не можете съесть '.$percent.'% яблока, т.к. осталось всего '.$state->size.'%', 1);
            $state->eatingSize += $percent;
            $state->size -= $percent;
        }
    }
    
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function fallToGround($state = null, $args = null)
    {
        throw new \Exception('Яблоко не может упасть, оно и так лежит на земле.', 11);
    }
    
    /**
     * @param stdClass $state
     * @param array $args
     */
    public static function delete($state = null, $args = null)
    {
        if ($state->size > 0) {
            throw new \Exception('Яблоко не доедено, его нельзя удалить', 12);
        } else {
            $state->deleted = true;
        }
    }
}