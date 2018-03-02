<?php

namespace yozh\properties\controllers;

use yozh\properties\models\PropertiesModel;
use yozh\base\controllers\DefaultController as Controller;

class DefaultController extends Controller
{
	protected static function primaryModel()
	{
		return PropertiesModel::className();
	}
	
	public function actionGetInput( $inputType, $widgetType )
	{
		
		$inputsConfig = PropertiesModel::getInputs();
		
		if( isset( $inputsConfig[ $inputType ]['widgets'][ $widgetType ] ) ) {
			
			return $this->renderPartial( '@yozh/properties/views/widgets/' . $inputType, [
				'inputConfig'  => $inputsConfig[ $inputType ],
				'widgetType' => $widgetType,
			] );
			
		}
		
		throw new \yii\web\NotFoundHttpException();
		
	}
	
}
