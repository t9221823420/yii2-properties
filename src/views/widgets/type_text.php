<?php

use yozh\properties\models\PropertyModel;
use yii\widgets\ActiveForm;
use powerkernel\tinymce\TinyMce;
use kartik\markdown\MarkdownEditor;

ob_start();
$form = ActiveForm::begin();

$field   = null;
$options = [
	'inputOptions' => [
		'class' => 'form-control',
	],
];

$type = $PropertyModel->type;

/**
 * @var $PropertyModel \yii\db\ActiveRecord
 */
if( $PropertyModel->isNewRecord ) { //
	$attribute = '[new][]value';
}
else { //
	
	$attribute = '[' . $PropertyModel->primaryKey . ']value';
	
	$options = array_merge_recursive( $options, [
		'inputOptions' => [
			'data-id' => $PropertyModel->primaryKey,
			'value'   => $PropertyModel->$type,
		],
	] );
}

$field = $form->field( $PropertyModel, $attribute, $options );

switch( $PropertyModel->widget ) {
	
	case PropertyModel::WIDGET_TYPE_TEXTAREA :
		
		$field->textarea( [ 'rows' => 3 ] );
		
		break;
	
	case PropertyModel::WIDGET_TYPE_TEXTEDITOR :
		
		$field->widget(
			TinyMce::className(),
			[
				'options' => [
					'id'   => 'editor-' . $PropertyModel->primaryKey,
					'rows' => 20,
					'data-id' => $PropertyModel->primaryKey,
					'value'   => $PropertyModel->$type,
				],
			]
		);
		
		break;
	
	case PropertyModel::WIDGET_TYPE_MARKUP :
		
		$field->widget(
			MarkdownEditor::className(),
			[
				'model' => $PropertyModel,
				'attribute' => $PropertyModel->type,
				'options' => [
					'id'   => 'editor-' . $PropertyModel->primaryKey,
					'data-id' => $PropertyModel->primaryKey,
					'value'   => $PropertyModel->$type,
				],
			]
		);

		break;
	
}

ActiveForm::end();
ob_get_clean();

$field->label( Yii::t( 'app', ucfirst( !empty( $PropertyModel->name ) ? $PropertyModel->name : str_replace( 'type_', '', $PropertyModel->type ) ) ) );

$menu = $this->context->renderFile( '@yozh/properties/views/_inputMenu.php', [
	'PropertyModel' => $PropertyModel,
] );

$field->parts['{label}'] = preg_replace( '/>(.+)(<\/label>)/', '><span class="text">$1</span>' . $menu . '$2', $field->parts['{label}'] );

$output = $field->__toString();

print $field;