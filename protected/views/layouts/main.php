<?php
/**
 * @var $this DiploController
 * @var string $content
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?= CHtml::encode( $this->pageTitle ); ?></title>
    <link href="<?= Yii::app()->request->baseUrl; ?>/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <script type="text/javascript">
        <?php if(strpos($_SERVER['HTTP_HOST'], 'local') !== false){ ?>
        window.url_root = "/";
        <?php } else { ?>
        window.url_root = "/diplomacy/";
        <?php } ?>
    </script>
</head>

<body>
<div id="page">

    <?php if (isset( $this->breadcrumbs )): ?>
        <?php $this->widget( 'bootstrap.widgets.TbBreadcrumbs', array(
            'links' => $this->breadcrumbs,
        ) ); ?><!-- breadcrumbs -->
    <?php endif ?>

    <?= $content; ?>

    <div class="clearfix"></div>

    <div id="footer">
        Copyright by Onad &copy; <?= date( 'Y' ); ?>. No Rights Reserved.<br/>
        <?= Yii::powered(); ?>
    </div>
    <!-- footer -->

</div>
</body>
</html>
