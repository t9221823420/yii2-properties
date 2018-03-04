<?php

namespace yozh\properties\controllers;

use Yii;
use yozh\properties\models\PropertyModel;
use yozh\properties\models\NewModel;
use yozh\base\controllers\DefaultController as Controller;
use yii\web\Response;

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
			
			$PropertyModel = new PropertyModel( $NewModel->attributes );
			
			if( empty($PropertyModel->name) ){
				$PropertyModel->name = $PropertyModel->type;
			}
			
			$PropertyModel->name = str_replace( 'type_', '', $PropertyModel->name );
			
			$inputsConfig = PropertyModel::getInputs();
			extract( $NewModel->getAttributes() );
			
			$model = $PropertyModel->model;
			
			if( isset( $inputsConfig[ $inputType ]['widgets'][ $widget ] )
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
