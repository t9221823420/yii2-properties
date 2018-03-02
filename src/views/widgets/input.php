<?php

use yii\base\DynamicModel;
use yii\widgets\ActiveForm;

ob_start();
$form = ActiveForm::begin();

$model = new DynamicModel([
	'foo' => 'bar',
]);

$output = false;

switch( $widgetType ) {
	
	case 'text':
		
		$output = $form->field( $model, 'foo', [] );
		
		break;
	
	case 'textarea':
		
		break;
	
	case 'texteditor':
		
		break;
	
	case 'markup':
		
		break;
	
}

ActiveForm::end();
ob_get_clean();

print $output;