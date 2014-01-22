<?php
/**
 * @var $this GameController
 * @var string $content
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div id="page">
    <?
    $is_admin = (Yii::app()->user->getState('game_role') == Game_roles::GM_ROLE);
    $is_player = (Yii::app()->user->getState('game_role') == Game_roles::PLAYER_ROLE);
    $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => 'Проект13',
            'brandUrl' => $this->createUrl('game/', array('id' => Yii::app()->user->getState('game_id'))),
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => array(
                        array(
                            'label' => 'ГМ',
                            'visible' => $is_admin,
                            'url' => $this->createUrl('game/gm'),
                            'active' => $this->action->id == 'gm'),
                        array(
                            'label' => 'Редактор карты',
                            'visible' => $is_admin,
                            'url' => $this->createUrl('game/map_redactor'),
                            'active' => $this->action->id == 'map_redactor'),
                        array(
                            'label' => 'Племя',
                            'url' => $this->createUrl('game/tribe'),
                            'active' => $this->action->id == 'tribe'),
                        array(
                            'label' => 'Технологии',
                            'url' => $this->createUrl('game/tech'),
                            'active' => $this->action->id == 'tech'),
                        array(
                            'label' => 'Заявка',
                            'visible' => $is_player,
                            'url' => $this->createUrl('game/request'),
                            'active' => $this->action->id == 'request'),
                        array(
                            'label' => 'Карта',
                            'url' => $this->createUrl('game/map'),
                            'active' => $this->action->id == 'map'),
                        array(
                            'label' => 'Статистика',
                            'url' => $this->createUrl('game/statistic'),
                            'active' => $this->action->id == 'statistic'),
                    )
                ),
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'items' => array(
                        array(
                            'label' => 'Назад в Кабинет',
                            'url' => $this->createUrl('/cabinet'),
                        )
                    )
                )
            )
        )
    );
    ?>
    <div id="inner_content">
        <?php echo $content; ?>
    </div>

    <div class="clear"></div>

    <div id="footer">
        "Первобытность" Copyright by Onad &copy; <?php echo date('Y'); ?>. No Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div>

</div>
</body>
</html>
