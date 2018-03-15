<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 02.03.2018
 * Time: 17:15
 */

namespace yozh\properties;

use Yii;
use yii\base\Model;
use yozh\properties\models\PropertiesModel;
use yozh\properties\models\AddPropertyModel;

class PropertiesWidget extends \yozh\base\components\Widget
{
	
	public    $form;
	protected $_ownerModel;
	
	/**
	 * @return mixed
	 */
	public function getOwnerModel()
	{
		return $this->_ownerModel;
	}
	
	/**
	 * @param mixed $ownerModel
	 */
	public function setOwnerModel( Model $ownerModel )
	{
		
		if( !isset( $ownerModel->properties ) ) {
			
			$ownerModelName = ( new\ReflectionObject( $ownerModel ) )->getShortName();
			
			throw new \yii\base\InvalidParamException( "Model $ownerModelName does not have PropertiesBehavior" );
		}
		
		$this->_ownerModel = $ownerModel;
	}
	
	public function run()
	{
		$AddPropertyModel = new AddPropertyModel( [
			'model'    => $this->_ownerModel::className(),
			'owner_id' => $this->_ownerModel->primaryKey,
		] );
		
		return $this->render( 'form', [
			'form'       => $this->form,
			'AddPropertyModel'   => $AddPropertyModel,
			'properties' => PropertiesModel::find()->orderBy( 'order')->where( [
				'model'    => $this->_ownerModel::className(),
				'table_pk' => $this->_ownerModel->primaryKey,
			] )->all(),
		] );
	}
	
}