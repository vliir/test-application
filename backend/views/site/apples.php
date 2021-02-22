<?php

/**
 * @var $this yii\web\View
 * @var $model app\models\AppleManager
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Apples';

$model->load(Yii::$app->request->post());
$model->addRandomNumberApples();
?>
<div class="row">
    <h2>Управление яблоками</h2>
    
    <div class="col-md-2">

        <?php            
            $form = ActiveForm::begin([
                'id' => 'apple-form', 
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-1\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-2 control-label'],
                ]
            ]);
            echo '<p>Создать случайное количество от 1 </p>';
            echo $form->field($model, 'maxNumbersApples')->Input('number', ['size' => 5, 'min' => 2, 'max' => 5])->label("до");
            echo '<p>яблок</p>';
            echo Html::submitButton('Выполнить', ['class' => 'btn btn-primary', 'name' => 'contact-button']);
            ActiveForm::end();
        ?>    
    </div>
    
    <div class="col-md-10">
        <div id="apple-report">
        <?php
            $apples = $model->getApples();
            $statuses = ['висит на дереве', 'упало/лежит на земле', 'пропало (сгнило)'];
            foreach($apples as $id => $apple) {
                echo '<div id="apple-'.$id.'">';
                    echo '<h3>Яблоко № '.$id.'</h3>';
                    echo '<p> Цвет '.$apple->color.'</p>';
                    echo '<p> Дата появления '.date('Y-m-d H:i:s', $apple->dateCreate).'</p>';
                    
                    if ($apple->dateFallDown) {
                        echo '<p class="dateFallDown"> Дата падения с дерева '.date('Y-m-d H:i:s', $apple->dateFallDown).'</p>';
                    } else {
                        echo '<p class="dateFallDown apple-hidden"> Дата падения с дерева </p>';
                    }
                    
                    echo '<p class="status"> Состояние '.$statuses[$apple->status].'</p>';
                    echo '<p class="eatingSize"> Сколько съели '.$apple->eatingSize.'%</p>';
                    echo '<p class="size"> Сколько осталось '.$apple->size.'%</p>';
                    echo '<h4>Действия над яблоком</h4>';
                    
                    echo '<button class="fallToGround">Упасть на землю</button><p></p>';
                    echo '<input type="text" size="5" name="eat"></input>% <button class="appleEat">Съесть</button>';
                    echo '<p></p><button class="appleDelete">Удалить</button>';
                    
                    echo '<p class="apple-hidden apple-error text-warning"></p>';
                echo '</div>';
            }
        ?>   
        </div>  
            
    </div>
    
</div>

<style>
    .apple-hidden {
        display: none;
    }
</style>

<script>
window.onload = function() {
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    
    let appleId;
    
    $("#apple-report").on("click", function(e){
        
        let btn = $(e.target);
        if (btn.hasClass('fallToGround')) {
            appleId = btn.closest('div').attr('id');
            let id = appleId.slice(6);
            let action = "fallToGround";
            let request = {
                "id":id,
                "action":action
            };
            sentToServer(request);
        }
        
        if (btn.hasClass('appleEat')) {
            appleId = btn.closest('div').attr('id');
            let id = appleId.slice(6);
            let action = "eat";
            let percent = $('div#' + appleId + ' input[name="eat"]').val();
            let request = {
                "id":id,
                "percent":percent,
                "action":action
            };
            sentToServer(request);
        }
        
        if (btn.hasClass('appleDelete')) {
            appleId = btn.closest('div').attr('id');
            let id = appleId.slice(6);
            let action = "delete";
            let request = {
                "id":id,
                "action":action
            };
            sentToServer(request);
        }
    });
    
    function sentToServer(request) {
        let url = document.location.href;
               
        $.post(
            url,
            request,
            handleResponse
        );
    }
    
    function handleResponse(response) {
        if ('error' in response) {
            let errorBox = $('#'+appleId+' p.apple-error');
            errorBox.removeClass('apple-hidden');
            errorBox.text(response.error);
        }
        
        if ("newState" in response) {
            console.log(response.newState);
            let newState = JSON.parse(response.newState);
            if ("dateFallDown" in newState) {
                let newDateFallDown = newState.dateFallDown;
                newDateFallDown = new Date(newDateFallDown * 1000).toLocaleString();
                let elem = $("div#" + appleId + " p.dateFallDown");
                let newElem = $('<p class="dateFallDown text-success"> Дата падения с дерева ' + newDateFallDown + '</p>');
                $(elem).replaceWith(newElem);
            }
            
            if ("status" in newState) {
                let statuses = ['висит на дереве', 'упало/лежит на земле', 'пропало (сгнило)'];
                let newStatus = statuses[newState.status];
                let elem = $("div#" + appleId + " p.status");
                let newElem = $('<p class="status text-success"> Состояние ' + newStatus + '</p>');
                $(elem).replaceWith(newElem);
            }
            
            if ("eatingSize" in newState) {
                let newEatingSize = newState.eatingSize;
                let elem = $("div#" + appleId + " p.eatingSize");
                let newElem = $('<p class="eatingSize text-success"> Сколько съели ' + newEatingSize + '%</p>');
                $(elem).replaceWith(newElem);
            }
            
            if ("size" in newState) {
                let newSize = newState.size;
                let elem = $("div#" + appleId + " p.size");
                let newElem = $('<p class="size text-success"> Сколько осталось ' + newSize + '%</p>');
                $(elem).replaceWith(newElem);
            }
        }
        
        if ('deleted' in response) {
            $('#'+appleId).remove();
        }
    }
}
</script>