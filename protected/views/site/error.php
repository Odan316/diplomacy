<?php
/**
 * @var $this SiteController
 * @var $error array
 * @var $code string
 * @var $message string
 */

$this->pageTitle=Yii::app()->name . ' - Ошибка';
$this->breadcrumbs=array(
	'Ошибка',
);
?>

<h2>Ошибка <?php echo $code; ?></h2>

<div class="error">
<?=CHtml::encode($message); ?>
</div>