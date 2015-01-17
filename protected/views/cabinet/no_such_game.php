<?php
/**
 * @var $this GameController
 */
$this->setPageTitle( Yii::app()->name . ' - Нет такой игры' );
?>
<div class="b_page_wrap">
    <div id="error_page_block">
        <h2>Игра с указанным ID не существует</h2>
        <a id="error_cabinet_link" href="/cabinet">Назад в кабинет</a>
    </div>
</div>