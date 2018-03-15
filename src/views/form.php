<?php

use kartik\helpers\Html;
use yozh\properties\models\AddPropertyModel;
use yozh\properties\AssetsBundle;
use yii\widgets\Pjax;

AssetsBundle::register( $this );

?>

<?= $form->field( $AddPropertyModel, 'model', [ 'template' => '{input}', 'options' => [ 'tag' => null ] ] )->hiddenInput(); ?>
<?= $form->field( $AddPropertyModel, 'owner_id', [ 'template' => '{input}', 'options' => [ 'tag' => null ] ] )->hiddenInput(); ?>

<div class="row new-property-form">
	
	<?= $form->field( $AddPropertyModel, 'name' )->label( Yii::t( 'properties', 'Name' ) ) ?>
	
	<?= $form->field( $AddPropertyModel, 'inputType' )
	         ->label( Yii::t( 'properties', 'Select Input' ) )
	         ->dropDownList( AddPropertyModel::inputsList(), [
		         'prompt' => Yii::t( 'properties', 'Select ...' ),
	         ] )
	; ?>
	
	<?= $form->field( $AddPropertyModel, 'widget' )
	         ->label( Yii::t( 'properties', 'Select Widget' ) )
	         ->dropDownList( [], [
		         'prompt' => Yii::t( 'properties', 'Select ...' ),
	         ] )
	; ?>

    <div class="form-group horizontal btn-group pull-bottom">
		<?= Html::button( Yii::t( 'properties', 'Add' ), [ 'class' => 'btn btn-success disabled', 'id' => 'addButton' ] ) ?>
    </div>

</div>

<?php
Pjax::begin( [ 'id' => 'pjax-container' ] );
print $this->context->renderFile( '@yozh/properties/views/properties.php' , [
	'properties' => $properties
]);
Pjax::end();
?>

<?php $this->registerJs( $this->render( '_js.php', [
	'section' => 'onload',
	'widgets' => implode( ',', AddPropertyModel::widgetsListOutput() ),
] ), $this::POS_READY );
?>
