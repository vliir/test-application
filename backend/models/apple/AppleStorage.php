<?php
namespace app\models\apple;

use yii\db\ActiveRecord;

class AppleStorage extends ActiveRecord
{
    public static function tableName()
    {
        return 'apples';
    }
    
    public function getAllApples()
    {
        $appleStates = static::find()
        ->indexBy('id')
        ->all();
        
        if (!$appleStates) return false;
        $apples = [];
        
        foreach($appleStates as $id => $state) {
            $apples[$id] = $this->getAppleByState($state->state);
        }
        return $apples;
    }
    
    public function getAppleById($id)
    {
        $appleState = static::find()
        ->where(['id' => $id])
        ->one();
        
        if (!$appleState) return false;
        $apple = $this->getAppleByState($appleState->state);
        return $apple;
    }
    
    public function addApple(Apple $apple)
    {
        $state = $apple->getState();       
        $record = new static(null);
        $record->state = $state;
        $record->save();
    }
    
    public function rewriteApple($id, Apple $apple)
    {
        $state = $apple->getState();
        $record = static::find()
        ->where(['id' => $id])
        ->one();
        $record->state = $state;
        $record->save();
    }
    
    public function deleteAppleById($id)
    {
        $record = static::find()
        ->where(['id' => $id])
        ->one();
        $record->delete();
    }
    
    protected function getAppleByState($state)
    {
        $apple = new Apple();
        $apple->setState($state);
        return $apple;
    }
}