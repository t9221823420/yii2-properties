<?php

use yozh\properties\models\PropertiesModel;
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

$type = $PropertiesModel->type;

/**
 * @var $PropertiesModel \yii\db\ActiveRecord
 */
if( $PropertiesModel->isNewRecord ) { //
	$attribute = '[new][]value';
}
else { //
	
	$attribute = '[' . $PropertiesModel->primaryKey . ']value';
	
	$options = array_merge_recursive( $options, [
		'inputOptions' => [
			'data-id' => $PropertiesModel->primaryKey,
			'value'   => $PropertiesModel->$type,
		],
	] );
}

$field = $form->field( $PropertiesModel, $attribute, $options );

switch( $PropertiesModel->widget ) {
	
	case PropertiesModel::WIDGET_TYPE_TEXTAREA :
		
		$field->textarea( [ 'rows' => 3 ] );
		
		break;
	
	case PropertiesModel::WIDGET_TYPE_TEXTEDITOR :
		
		$field->widget(
			TinyMce::className(),
			[
				'options' => [
					'id'   => 'editor-' . $PropertiesModel->primaryKey,
					'rows' => 20,
					'data-id' => $PropertiesModel->primaryKey,
					'value'   => $PropertiesModel->$type,
				],
			]
		);
		
		break;
	
	case PropertiesModel::WIDGET_TYPE_MARKUP :
		
		$field->widget(
			MarkdownEditor::className(),
			[
				'model' => $PropertiesModel,
				'attribute' => $PropertiesModel->type,
				'options' => [
					'id'   => 'editor-' . $PropertiesModel->primaryKey,
					'data-id' => $PropertiesModel->primaryKey,
					'value'   => $PropertiesModel->$type,
				],
			]
		);

		break;
	
}

ActiveForm::end();
ob_get_clean();

$field->label( Yii::t( 'app', ucfirst( !empty( $PropertiesModel->name ) ? $PropertiesModel->name : str_replace( 'type_', '', $PropertiesModel->type ) ) ) );

$menu = $this->context->renderFile( '@yozh/properties/views/_inputMenu.php', [
	'PropertiesModel' => $PropertiesModel,
] );

$field->parts['{label}'] = preg_replace( '/>(.+)(<\/label>)/', '><span class="text">$1</span>' . $menu . '$2', $field->parts['{label}'] );

$output = $field->__toString();

print $field;