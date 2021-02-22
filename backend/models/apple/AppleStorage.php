<?php
namespace app\models\apple;

use yii\db\ActiveRecord;

class AppleStorage extends ActiveRecord
{
    public static function tableName()
    {
        return 'apples';
    }
    
    /**
     * Получить из БД массив объектов Apples
     * @return array
     */
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
    
    /**
     * Получить объект Apple по его id
     * @param int $id
     * @return apple/Apple
     */
    public function getAppleById($id)
    {
        $appleState = static::find()
        ->where(['id' => $id])
        ->one();
        
        if (!$appleState) return false;
        $apple = $this->getAppleByState($appleState->state);
        return $apple;
    }
    
    /**
     * Добавить в БД "состояние" объекта apple/Apple
     * @param apple/Apple $apple
     */
    public function addApple(Apple $apple)
    {
        $state = $apple->getState();       
        $record = new static(null);
        $record->state = $state;
        $record->save();
    }
    
    /**
     * Перезаписать в БД "состояние" объекта Apple с заданным $id
     * @param int $id
     */
    public function rewriteApple($id, Apple $apple)
    {
        $state = $apple->getState();
        $record = static::find()
        ->where(['id' => $id])
        ->one();
        $record->state = $state;
        $record->save();
    }
    
    /**
     * Удалить из БД "состояние" объекта Apple с заданным $id 
     * @param int $id
     */
    public function deleteAppleById($id)
    {
        $record = static::find()
        ->where(['id' => $id])
        ->one();
        $record->delete();
    }
    
    /**
     * Получить объект Apple по его "состоянию"
     * @param string $state
     */
    protected function getAppleByState($state)
    {
        $apple = new Apple();
        $apple->setState($state);
        return $apple;
    }
}