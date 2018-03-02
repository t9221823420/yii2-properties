<?php

use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yozh\properties\models\PropertiesModel;

$inputs = PropertiesModel::getInputs();

$widget = $this->context;

$widgetsOutput = [];
foreach( $inputs as $inputType => $inputConfig ) {
	
	$widgetsOutput[ $inputType ] = $inputType . ":'";
	
	foreach( $inputConfig['widgets'] as $widgetName => $widgetConfig ) {
		$widgetsOutput[ $inputType ] .= '<option value="' . $widgetConfig['name'] . '">' . $widgetConfig['label'] . '</option>';
	}
	
	$widgetsOutput[ $inputType ] .= "'";
}

?>

<div class="row">

    <div class="form-group horizontal">
		<?= Html::label( Yii::t( 'properties', 'Select Input' ), 'inputType' ); ?>
		<?= Html::dropDownList( 'inputType', null, ArrayHelper::map( $inputs, 'name', 'label' ), [
			'class'  => 'form-control',
			'prompt' => Yii::t( 'properties', 'Select ...' ),
		] ); ?>
    </div>
    
    <div id="widgetType" class="form-group horizontal">
		<?= Html::label( Yii::t( 'properties', 'Select Widget' ), 'widgetType' ); ?>
		<?= Html::dropDownList( 'widgetType', null, [], [
			'class'  => 'form-control',
			'prompt' => Yii::t( 'properties', 'Select input' ),
		] ); ?>
    </div>

    <div class="form-group horizontal pull-bottom">
	    <?= Html::button( Yii::t('properties', 'Add'), ['class' => 'btn btn-success disabled', 'id' => 'addButton']) ?>
    </div>
    
</div>

<div id="inputs">

</div>


<?php $this->registerJs( $this->render( '_js.php', [
	'section' => 'onload',
	'widgets' => implode( ',', $widgetsOutput),
] ), $this::POS_READY );
?>
