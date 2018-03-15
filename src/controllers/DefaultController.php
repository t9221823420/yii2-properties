<?php

namespace yozh\properties\controllers;

use Yii;
use yozh\properties\models\PropertiesModel;
use yozh\properties\models\PropertyModel;
use yozh\properties\models\AddPropertyModel;
use yozh\base\controllers\DefaultController as Controller;
use yii\web\Response;

class DefaultController extends Controller
{
	protected static function defaultModel()
	{
		return PropertiesModel::className();
	}
	
	public function actionGetInput()
	{
		$AddPropertyModel = new AddPropertyModel();
		
		if( $AddPropertyModel->load( Yii::$app->request->post() ) ) {
			
			$PropertyModel = new PropertyModel( $AddPropertyModel->attributes );
			
			if( empty($PropertyModel->name) ){
				$PropertyModel->name = $PropertyModel->type;
			}
			
			$PropertyModel->name = PropertyModel::getLabel( $PropertyModel->name );
			
			$inputsConfig = PropertiesModel::getInputs();
			extract( $AddPropertyModel->getAttributes() );
			
			/*
			$testModel = $PropertyModel->model;
			$foo =  $testModel->getProperties('some prop', true, 'some default value')->value;
			*/
			
			if( !isset($testModel) && isset( $inputsConfig[ $inputType ]['widgets'][ $widget ] )
				&& $PropertyModel->validate( null, false ) // because ->save() clears errors,
				&& $PropertyModel->save( false )
			) {
				
				Yii::$app->response->format = Response::FORMAT_JSON;
				
				$response = [
					'id'     => $PropertyModel->primaryKey,
					'widget' => $PropertyModel->widget,
				];
				
				$response['html'] = $this->renderPartial( '@yozh/properties/views/widgets/' . $inputType, [
					'inputConfig'   => $inputsConfig[ $inputType ],
					'PropertyModel' => $PropertyModel,
				] );
				
				return $response;
				
			}
			
		}
		
		throw new \yii\web\NotFoundHttpException();
		
	}
	
	public function actionUpdate( $id )
	{
		/**
		 * @var $PropertyModel PropertyModel
		 */
		$PropertyModel = $this->_findModel( [
			'id' => $id,
		] );
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		if( $data = Yii::$app->request->post( 'value' ) ) { //
			
			$field = $PropertyModel->type;
			
			$PropertyModel->$field = $data;
			if( $PropertyModel->save( false, [ $field ] ) ) {
				return true;
			}
			
		}
		
		return false;
	}
	
	public function actionNameUpdate( $id, $name )
	{
		/**
		 * @var $PropertyModel PropertyModel
		 */
		$PropertyModel = $this->_findModel( [
			'id' => $id,
		] );
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$PropertyModel->name = $name;
		if( $PropertyModel->save( false, [ 'name' ] ) ) {
			return true;
		}
		
		return false;
	}
	
	public function actionDelete( $id, $model, $oid )
	{
		$this->_findModel( [
			'id' => $id,
		] )->delete()
		;
		
		return $this->renderFile( '@yozh/properties/views/properties.php', [
			'properties' => PropertyModel::findAll( [
				'model'    => $model,
				'table_pk' => $oid,
			] ),
		] );
	}
	
}
