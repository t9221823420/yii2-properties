<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 04.03.2018
 * Time: 1:21
 */

use yozh\properties\models\PropertyModel;

$inputsConfig = PropertyModel::getInputs();

?>

<div id="inputs">
<?php foreach( $properties as $PropertyModel ) {
	
	print $this->context->renderFile( '@yozh/properties/views/widgets/' . $PropertyModel->inputType . '.php', [
		'inputConfig'   => $inputsConfig[ $PropertyModel->inputType ],
		'PropertyModel' => $PropertyModel,
	] );
	
}?>
</div>
