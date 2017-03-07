<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">Let's start parsing site!</p>

        <p><a class="btn btn-lg btn-success" href="<?php echo Url::toRoute('page/index')?>">Get rate</a></p>
    </div>

</div>
