<?php
namespace app\models;

use Yii;
use yii\base\Model;

class AppleManager extends Model
{
    public $maxNumbersApples;
    protected $appleStorage;
    protected $statusControl;
    
    protected $actions = ["fallToGround", "eat", "delete"];
    
    public function __construct()
    {
        $this->appleStorage = new apple\AppleStorage();
        $this->statusControl = new apple\StatusControl();
    }
    
    /**
     * Выполнение кокого-либо действия над выбранным яблоком
     * @param array $data
     * @return array
     */
    public function action($data)
    {
        $id = $data['id'];
        $action = $data['action'];
                
        $apple = $this->appleStorage->getAppleById($id);
        $this->actualStatus($id, $apple);
        
        if ($action == "fallToGround") {
            try {
                $apple->fallToGround();
                $this->appleStorage->rewriteApple($id, $apple);
                $newState = new \stdClass();
                $newState->dateFallDown = $apple->dateFallDown;
                $newState->status = $apple->status;
                $newState = json_encode($newState);
                return ["newState" => $newState];
            } catch(\Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }
        
        if ($action == "eat") {
            $percent = $data["percent"];
            try {
                $apple->eat($percent);
                $this->appleStorage->rewriteApple($id, $apple);
                $newState = new \stdClass();
                $newState->eatingSize = $apple->eatingSize;
                $newState->size = $apple->size;
                $newState = json_encode($newState);
                return ["newState" => $newState];
            } catch(\Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }
           
        if ($action == "delete") {
            try {
                $apple->delete();
                if ($apple->isDeleted()) {
                    $this->appleStorage->deleteAppleById($id);
                    return ["deleted" => true];
                }
            } catch(\Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }
        
        return ["response" => "принято id=".$id." action = ".$action];
    }
    
    /**
     * Получение из БД объектов Apple
     * @return array
     */
    public function getApples()
    {
        $apples = $this->appleStorage->getAllApples();
        if ($apples) {
            foreach ($apples as $id => $apple) {
                $this->actualStatus($id, $apples[$id]);
            }
        } else {
            $apples = [];
        }
        return $apples;
    }
    
    /**
     * Генерация и запись в БД случайного количества объектов Apple
     */
    public function addRandomNumberApples()
    {
        if (!$this->maxNumbersApples) return false;
        $col = rand(1, $this->maxNumbersApples);
        $apples = [];
        for ($i = 0; $i < $col; $i++) {
            $apple = new apple\Apple();
            $this->appleStorage->addApple($apple);
            $apples[] = $apple;
        }
    }
    
    /**
     * Проверка статуса объекта Apple с заданным id
     * @param int $id
     * @param apple\Apple $apple
     */
    protected function actualStatus($id, $apple)
    {
        if ($this->statusControl->makeStatusControl($apple)) {
            $this->appleStorage->rewriteApple($id, $apple);
        }
    }
    
    /**
     * @return array
     */
    public function rules()
    {
        $rules[] = [['maxNumbersApples'], 'safe'];
        return $rules;
    }
}