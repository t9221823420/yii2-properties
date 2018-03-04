<?php

use kartik\helpers\Html;
use yozh\properties\models\NewModel;
use yozh\properties\AssetsBundle;
use yii\widgets\Pjax;

AssetsBundle::register( $this );

?>

<?= $form->field( $NewModel, 'model', [ 'template' => '{input}', 'options' => [ 'tag' => null ] ] )->hiddenInput(); ?>
<?= $form->field( $NewModel, 'owner_id', [ 'template' => '{input}', 'options' => [ 'tag' => null ] ] )->hiddenInput(); ?>

<div class="row new-property-form">
	
	<?= $form->field( $NewModel, 'name' )->label( Yii::t( 'properties', 'Name' ) ) ?>
	
	<?= $form->field( $NewModel, 'inputType' )
	         ->label( Yii::t( 'properties', 'Select Input' ) )
	         ->dropDownList( NewModel::inputsList(), [
		         'prompt' => Yii::t( 'properties', 'Select ...' ),
	         ] )
	; ?>
	
	<?= $form->field( $NewModel, 'widget' )
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
	'widgets' => implode( ',', NewModel::widgetsListOutput() ),
] ), $this::POS_READY );
?>
