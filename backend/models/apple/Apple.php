<?php
namespace app\models\apple;

class Apple
{
    public function __construct($color = null)
    {
        $this->color = ($color) ? $color : $this->colors[rand(0, 5)];
        $this->dateCreate = rand(1, time());
    }
    
    /**
     * Упасть (на землю)
     */
    public function fallToGround()
    {
        $this->callHandler('fallToGround');
    }
    
    /**
     * Съесть
     */
    public function eat($percent)
    {
        $this->callHandler('eat', [$percent]);
    }
    
    /**
     * Удалить
     */
    public function delete()
    {
        $this->callHandler('delete');
    }
    
    public function getState()
    {
        $state = new \stdClass();
        foreach($this->stateProps as $prop) {
            if (isset($this->$prop)) $state->$prop = $this->$prop;
        }
        return json_encode($state);
    }
    
    public function setState($state)
    {
        $state = json_decode($state);
        foreach($state as $key => $val) {
            $this->$key = $val;
        }
    }
    
    /**
     * Вызов внешнего обработчика в зависимости от статуса;
     * @param string $functionName
     */
    protected function callHandler($functionName, $args = [])
    {
        $state = $this->getHandlerState();
        $className = __NAMESPACE__ . '\\' . $this->handlerClassNames[$this->status];
        $className::$functionName($state, $args);
        foreach ($state as $key => $val) {
            $this->$key = $val;
        }
    }
    
    /**
     * Яблоко помечено на удаление?
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }
    
    protected function getHandlerState()
    {
        $state = new \stdClass();
        $state->eatingSize = $this->eatingSize;
        $state->size = $this->size;
        return $state;
    }
    
    /**
     * цвет (устанавливается при создании объекта случайным)
     */
    public $color;
    
    /**
     * Дата появления (устанавливается при создании объекта случайным unixTmeStamp)
     */
    public $dateCreate = 1;
    
    /**
     * Дата падения (устанавливается при падении объекта с дерева)
     */
    public $dateFallDown;
    
    /** Состояния
     *  0 висит на дереве
     *  1 упало/лежит на земле
     *  2 гнилое яблоко
     *  @int
     */
    public $status = 0;
    
    /**
     * сколько съели (%)
     */
    public $eatingSize = 0;
    
    /**
     * Сколько осталось
     */
    public $size = 100; 
    
    /**
     * Пометка на удаление
     * @var bool
     */
    protected $deleted = false;
    
    protected $handlerClassNames = ['AppleFunctionsTree', 'AppleFunctionsGround', 'AppleFunctionsRotten'];
    
    protected $colors = ['red', 'green', 'yellow', 'white', 'blue', 'orange'];
    
    protected $stateProps = ['color', 'dateCreate', 'dateFallDown', 'status', 'eatingSize', 'size'];
}