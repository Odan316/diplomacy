<?php
/**
 * @var $this SiteController
 * @var $model Users
 */
$this->setPageTitle(Yii::app()->name.' - Онлайн-клиент');
?>

<div class="b_start block1">
    <h1 class="logo">Дипломатия</h1>
    <div class="b_auth block2">
        <h3>Войти в личный кабинет</h3>
        <?php
        /** @var $form TbActiveForm */
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id'=>'verticalForm',
                'htmlOptions'=>array('class'=>'well'),
                'action'=> $this->createUrl('site/login'),
            ));
        ?>
        <?=$form->textFieldRow($model, 'login', array('class'=>'span3')); ?>
        <?=$form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?><br/>

        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Войти')); ?>

        <?php $this->endWidget(); ?>
        <div class="b_reg_start">
            <a href="<?=$this->createUrl('registration') ?>">Регистрация</a>
        </div>
    </div>
</div>
