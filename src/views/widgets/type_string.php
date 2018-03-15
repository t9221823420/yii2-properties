<?php

use yii\widgets\ActiveForm;

ob_start();
$form = ActiveForm::begin();

$field   = null;
$options = [
	'inputOptions' => [
		'class' => 'form-control',
	],
];

/**
 * @var $PropertyModel \yii\db\ActiveRecord
 */
if( $PropertyModel->isNewRecord ) { //
	$attribute = '[new][]value';
}
else { //
	
	$attribute = '[' . $PropertyModel->primaryKey . ']value';
	
	$field = $PropertyModel->type;
	
	$options   = array_merge_recursive( $options, [
		'inputOptions' => [
			'data-id' => $PropertyModel->primaryKey,
			'value' => $PropertyModel->$field,
		],
	] );
}

$field = $form->field( $PropertyModel, $attribute, $options );

switch( $PropertyModel->widget ) {
	
	/*
	case PropertyModel::WIDGET_TYPE_TEXT :
		
		// 'template'=>'{input}{foo}'
		
		break;
	*/
}

ActiveForm::end();
ob_get_clean();

$field->label( Yii::t( 'app', !empty( $PropertyModel->name ) ? $PropertyModel->name : str_replace( 'type_', '', $PropertyModel->type ) ) );

$menu = $this->context->renderFile( '@yozh/properties/views/_inputMenu.php', [
	'PropertyModel' => $PropertyModel,
] );

$field->parts['{label}'] = preg_replace( '/>(.+)(<\/label>)/', '><span class="text">$1</span>' . $menu . '$2', $field->parts['{label}'] );

$output = $field->__toString();

print $field;