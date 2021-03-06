<?php
/**
 * @var $this CabinetController
 * @var $model Games
 * @var $master_model Users
 * @var $players_model Users
 * @var $claimers_model Users
 * @var $user_role Users2games
 */
?>
<div id="b_game_info" data-id="<?=CHtml::encode($model->id); ?>">
    <h3><?=CHtml::encode($model->title); ?></h3>
    <p class="point_title">Ведущий:</p>
    <div class="point_content">
        <div class="point_row">
            <?=CHtml::encode(($master_model->person ? $master_model->person->nickname : "").
                (Yii::app()->user->uid == $master_model->id ? ' (Вы)' : '')); ?>
        </div>
    </div>
    <p class="point_title">Участники:</p>
    <div class="point_content" id="game_participants">
        <?php if($players_model): ?>
            <?php foreach($players_model as $player): ?>
                <div class="point_row">
                    <?=CHtml::encode($player->person ? $player->person->nickname : ""); ?>
                </div>
            <?php endforeach ?>
        <?php else:?>
            <div class="point_row">
                <p>Нет участников</p>
            </div>
        <?php endif ?>
    </div>
    <p class="point_title">Заявки:</p>
    <div class="point_content" id="game_participants">
    <?php if($claimers_model): ?>
        <?php foreach($claimers_model as $claimer): ?>
            <div class="point_row" data-id="<?=CHtml::encode($claimer->id); ?>">
                <?=CHtml::encode($claimer->person ? $claimer->person->nickname : ""); ?>
                <?php if($user_role && $user_role->role_id == Game_roles::GM_ROLE){
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Принять',
                        'type' => 'secondary',
                        'size' => 'small',
                        'url' => $this->createUrl('cabinet/accept_claim', array('game_id' => $model->id, 'user_id' => $claimer->id)),
                    ));
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Отклонить',
                        'type' => 'secondary',
                        'size' => 'small',
                        'url' => $this->createUrl('cabinet/reject_claim', array('game_id' => $model->id, 'user_id' => $claimer->id)),
                    ));
                } ?>
            </div>
        <?php endforeach ?>
    <?php else:?>
        <div class="point_row">
            <p>Нет заявок</p>
        </div>
    <?php endif ?>
    </div>
    <div id="game_actions">
        <?php
            if(!$user_role){
                $this->widget('bootstrap.widgets.TbButton',array(
                    'label' => 'Подать заявку',
                    'type' => 'primary',
                    'url' => $this->createUrl('cabinet/make_claim', array('game_id' => $model->id)),
                ));
            }
            elseif($user_role->role_id == Game_roles::GM_ROLE){
                if($model->status_id == Game_statuses::OPEN_GAME){
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Начать игру',
                        'type' => 'success',
                        'url' => $this->createUrl('cabinet/start_game', array('id' => $model->id)),
                    ));
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Отменить игру',
                        'type' => 'danger',
                        'url' => $this->createUrl('cabinet/cancel_game', array('id' => $model->id)),
                    ));
                }
                elseif($model->status_id == Game_statuses::ACTIVE){
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Перейти в кабинет Ведущего',
                        'type' => 'primary',
                        'url' => $this->createUrl(($model->module ? $model->module->system_name : '').'/game', array('id' => $model->id))
                    ));
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Завершить игру',
                        'type' => 'danger',
                        'url' => $this->createUrl('cabinet/end_game', array('id' => $model->id)),
                    ));
                }
            }
            elseif($user_role->role_id == Game_roles::PLAYER_ROLE){
                if($model->status_id == Game_statuses::ACTIVE){
                    $this->widget('bootstrap.widgets.TbButton',array(
                        'label' => 'Перейти в кабинет Игрока',
                        'type' => 'primary',
                        'url' => $this->createUrl(($model->module ? $model->module->system_name : '').'/game', array('id' => $model->id))
                    ));
                }
            }
        ?>
    </div>
</div>