<?php

use yozh\properties\models\PropertyModel;
use yii\widgets\ActiveForm;

ob_start();
$form = ActiveForm::begin();

$field = null;

/**
 * @var $PropertyModel \yii\db\ActiveRecord
 */
if( $PropertyModel->isNewRecord ) { //
	$attribute = '[new][]value';
}
else { //
	$attribute = '[' . $PropertyModel->primaryKey . ']value';
}

switch( $PropertyModel->widget ) {
	
	case 'text':
		
		$field = $form->field( $PropertyModel, $attribute ); // 'template'=>'{input}{foo}'
		
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

$field->label( Yii::t( 'app', ucfirst( !empty( $PropertyModel->name ) ? $PropertyModel->name : $PropertyModel->type ) ) );

$menu = $this->context->renderFile( '@yozh/properties/views/_inputMenu.php', [
	'PropertyModel' => $PropertyModel,
] );

$field->parts['{label}'] = preg_replace( '/>(.+)(<\/label>)/', '><span class="text">$1</span>' . $menu . '$2', $field->parts['{label}'] );

$output = $field->__toString();

print $field;