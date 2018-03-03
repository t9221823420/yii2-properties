<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
    <div class="dropdown input-menu">
		<?= Html::button( '<i class="glyphicon glyphicon-pencil"></i>', [
			'class'         => 'btn btn-primary btn-xs dropdown-toggle edit-label',
			'data-toggle'   => 'dropdown',
			'aria-haspopup' => 'true',
			'aria-expanded' => 'false',
		] ) ?>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <div class="form-group">
				<?= Html::textInput( 'label', '', [
					'class' => 'form-control',
				] ); ?>
            </div>
        </div>
    </div>


<?= Html::a( '<i class="glyphicon glyphicon-trash"></i>',
	Url::to( [
		'/properties/delete',
		'id'       => $PropertyModel->primaryKey,
		'model'    => $PropertyModel->model::className(),
		'model_id' => $PropertyModel->model->primaryKey,
	] ),
	[
		'class'        => 'btn btn-danger btn-xs btn-delete',
		'data-pjax'    => '#pjax-container',
		'data-confirm' => Yii::t( 'yii', 'Are you sure you want to delete this item?' ),
		'data-method'  => 'post',
	]
) ?>