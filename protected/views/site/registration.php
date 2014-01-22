<?php
/**
 * @var $this SiteController
 * @var $users_model Users
 * @var $persons_model Persons
 * @var $registration_result boolean
 */
$this->setPageTitle(Yii::app()->name.' - Регистрация');
?>
<div class="b_start block1">
    <h1 class="logo">Дипломатия</h1>
<?php if(!$registration_result):?>
    <div class="b_reg block2">
        <h3>Регистрация</h3>
        <?php
        /** @var $form TbActiveForm */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'verticalForm',
            'htmlOptions'=>array('class'=>'well'),
            //'action'=> $this->createUrl('registration'),
        ));
        ?>
        <?=$form->textFieldRow($users_model, 'login', array('class'=>'span3')); ?>
        <?=$form->textFieldRow($persons_model, 'nickname', array('class'=>'span3')); ?>
        <?=$form->passwordFieldRow($users_model, 'password', array('class'=>'span3')); ?>
        <?=$form->passwordFieldRow($users_model, 'repeat_password', array('class'=>'span3')); ?>
        <br/>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Подтвердить')); ?>
        <?php $this->endWidget(); ?>
    </div>
<?php else: ?>
    <div class="b_reg block2">
        <h3>Регистрация успешна!</h3>
        <a href="<?=$this->createUrl('/cabinet'); ?>">Перейти в личный кабинет</a>
    </div>
<?php endif ?>
</div>