<?php
/**
 * @var $this CabinetController
 * @var $user_model Users
 * @var $master_games Games
 * @var $player_games Games
 * @var $open_games Games
 * @var $claimed_games Games
 * @var $game_info_html string
 * @var $modules_list array
 * */
$this->setPageTitle(Yii::app()->name.' - Кабинет пользователя');
?>

<div class="b_page_wrap">
    <div class="cabinet_left block2">
        <h2>
            Участник <?=$user_model->person->nickname ;?> &nbsp;&nbsp;
            <a class="active_span" id="cabinet_logout" href="<?=$this->createUrl('site/logout');?>">Выход</a>
        </h2>
        <h3>Вы ведете:</h3>
        <div class="games_list">
            <?php foreach($master_games as $game):?>
                <p class="game_select" data-id="<?=CHtml::encode($game->id)?>"><?=CHtml::encode($game->title)."(".CHtml::encode($game->tag).")"?></p>
            <? endforeach ?>
        </div>
        <h3>Вы играете в:</h3>
        <div class="games_list">
            <?php foreach($player_games as $game):?>
                <p class="game_select" data-id="<?=CHtml::encode($game->id)?>"><?=CHtml::encode($game->title)."(".CHtml::encode($game->tag).")"?></p>
            <? endforeach ?>
        </div>
        <h3>Открыты для вступления:</h3>
        <div id="open_list" class="games_list">
            <?php foreach($open_games as $game):?>
                <p class="game_select" data-id="<?=CHtml::encode($game->id)?>"><?=CHtml::encode($game->title)."(".CHtml::encode($game->tag).")"?></p>
            <? endforeach ?>
       </div>
        <h3>Вы подали заявку:</h3>
        <div id="claimed_list" class="games_list">
            <?php foreach($claimed_games as $game):?>
                <p class="game_select" data-id="<?=CHtml::encode($game->id)?>"><?=CHtml::encode($game->title)."(".CHtml::encode($game->tag).")"?></p>
            <? endforeach ?>
       </div>
        <?php
            $this->widget('bootstrap.widgets.TbButton',array(
            'label' => 'Создать новую игру',
            'htmlOptions' => array('id'=>'create_game')
            ));
        ?>
    </div>
    <div class="cabinet_right block2">
        <h2>Информация об игре</h2>
        <div id="cabinet_game_info">
            <?=$game_info_html; ?>
        </div>
    </div>
</div>
<div class="cabinet_game_creation block2 popup">
    <h2>Создание новой игры</h2>
    <span id="game_creation_result"></span>
    <h3>Название:</h3>
        <?=CHtml::textField('new_game_title'); ?>
    <h3>Доступные модули:</h3>
    <div class="cabinet_game_modules">
        <?=CHtml::dropDownList('new_game_module', '', $modules_list); ?>
    </div>
    <?php
        $this->widget('bootstrap.widgets.TbButton',array(
            'label' => 'Создать',
            'htmlOptions' => array('id'=>'action_game_create')
        ));
        $this->widget('bootstrap.widgets.TbButton',array(
            'label' => 'Отмена',
            'htmlOptions' => array('class'=>'popup_close')
        ));
    ?>
</div>
