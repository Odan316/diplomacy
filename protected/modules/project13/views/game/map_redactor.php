<?php
/**
 * @var $this GameController
 * @var $map_info array() Массив с информацией о карте
 * @var $map_object_types CDbCommand
 */
$this->setPageTitle('Проект13 - Редактор карты');
?>
<? if($map_info):?>
    <div id="redactor_map_outer">
        <div id="redactor_map"
            data-game-id="<?=Yii::app()->request->cookies['game_id']->value;?>"
            style="width:<?=($map_info['width']*16+1).'px;'; ?>">
            <?for($y = 1; $y <= $map_info['height']; $y++):?>
                <div class="map_row" id="y<?=$y?>">
                    <?for($x = 1; $x <= $map_info['width']; $x++):?>
                        <div class="map_cell" id="y<?=$y?>x<?=$x?>" data-y="<?=$y?>" data-x="<?=$x?>"></div>
                    <?endfor?>
                </div>
            <?endfor?>
        </div>
    </div>
    <div id="redactor_side_menu">
        <h3>Типы объектов</h3>
        <div id="object_types_list">
            <div class="object_type_row"
                 data-category="eraser">
                <img class="object_type_icon"
                     src="<?=$this->module->assetsBase?>/images/design/eraser.png" />
                Ластик
            </div>
            <? foreach($map_object_types as $object_type): ?>
                <div class="object_type_row"
                     data-type="<?=$object_type['id']?>"
                     data-category="<?=$object_type['category']?>">
                    <? if(($object_type['category'] == 'landtype')): ?>
                        <div class="object_type_icon"
                             style="background-color:<?=current($object_type['gfx'])?>"></div>
                    <? else: ?>
                        <img class="object_type_icon"
                             src="<?=$this->module->assetsBase?>/images/map_icons/<?=current($object_type['gfx'])?>.png" />
                    <? endif ?>
                    <?=$object_type['name_rus']?>
                </div>
            <? endforeach ?>
        </div>
        <h3>Варианты</h3>
        <div id="object_gfx_list"></div>
    </div>
    <div id="b_save_map">
        <?
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'htmlOptions' => array('id' => 'save_map'),
                'label' => 'Сохранить',
                'type' => 'primary',
            )
        );
        ?>
    </div>
<? else: ?>
    <?
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'htmlOptions' => array('id' => 'create_blank_map'),
                'label' => 'Создать карту с нуля',
            )
        );
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'url' => $this->createUrl('game/create_default_map'),
                'label' => 'Создать карту из базовой карты',
            )
        );
    ?>
    <div id="b_blank_map_creation">
        <?php
        /** @var $form TbActiveForm */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'verticalForm',
            'htmlOptions'=>array('class'=>'well'),
        ));
        ?>
        <?=CHtml::textField('map_width', '100'); ?>
        <?=CHtml::textField('map_height', '60'); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'label'=>'Создать',
            'htmlOptions' => array(
                'name' => 'create_map',
                'style' => 'margin-bottom: 10px;')
        )); ?>
        <? $this->endWidget(); ?>
    </div>
<? endif; ?>