<?php

namespace yozh\properties\controllers;

use Yii;
use yozh\properties\models\PropertyModel;
use yozh\properties\models\NewModel;
use yozh\base\controllers\DefaultController as Controller;

class DefaultController extends Controller
{
	protected static function primaryModel()
	{
		return PropertyModel::className();
	}
	
	public function actionGetInput()
	{
		$NewModel = new NewModel();
		
		if( $NewModel->load( Yii::$app->request->post() ) ) {
			
			extract( $NewModel->getAttributes() );
			
			$inputsConfig = PropertyModel::getInputs();
			
			$PropertyModel = new PropertyModel();
			
			$PropertyModel->setAttributes( $NewModel->attributes );
			
			if( isset( $inputsConfig[ $inputType ]['widgets'][ $widget ] )
				&& $PropertyModel->validate( null,false) // because ->save() clears errors,
				&& $PropertyModel->save( false )
			) {
				
				return $this->renderPartial( '@yozh/properties/views/widgets/' . $inputType, [
					'inputConfig'   => $inputsConfig[ $inputType ],
					'PropertyModel' => $PropertyModel,
				] );
				
			}
			
		}
		
		throw new \yii\web\NotFoundHttpException();
		
	}
	
	public function actionDelete( $id, $model, $model_id )
	{
		$this->_findModel([
			'id' => $id,
			'model' => $model,
		])->delete();
		
		return $this->renderFile( '@yozh/properties/views/properties.php' , [
			'properties' => PropertyModel::findAll([
				'model'    => $model,
				'table_id' => $model_id,
			]),
		]);;
	}
	
}
