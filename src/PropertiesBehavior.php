<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 02.03.2018
 * Time: 17:14
 */

namespace yozh\properties;

use yozh\properties\models\PropertyModel;
use yii\base\Model;
use yii\base\InvalidParamException;

class PropertiesBehavior extends \yozh\base\components\Behavior
{
	protected $_properties;
	
	/**
	 * PropertiesBehavior constructor.
	 * @param $_PropertyModel
	 */
	/*
	public function __construct( $config = [] )
	{
		parent::__construct( $config );
		
		if( $this->owner instanceof Model ) {
			
			$modelName = ( new\ReflectionObject( $this->owner ) )->getShortName();
			
			throw new \yii\base\InvalidParamException( "$modelName have to be instance of Model" );
		}
		
		$this->$_PropertyModel = new PropertyModel( [
			'model' => $this->owner,
		] );
	}
	*/
	
	/**
	 * @return mixed
	 */
	public function getProperties( $names = null, $one = false )
	{
		$query = PropertyModel::find()->where( [
			'model'    => $this->owner::className(),
			'table_pk' => $this->owner->primaryKey,
		] )
		;
		
		if( is_string( $names ) ) {
			$names = [ $names ];
		}
		
		if( is_array( $names ) && count( $names ) ) {
			$query->andWhere( [ 'in', 'name', $names ] );
		}
		
		return $one ? $query->one() : $query->all() ;
	}
	
	/**
	 * @param mixed $PropertyModel
	 */
	
	public function setProperty( $id, $value )
	{
		if( $PropertyModel = PropertyModel::findOne($id) ){
			
			$PropertyModel->value = $value;
			
			if( $PropertyModel->save() ){
				return $this->owner;
			}
			
			throw new InvalidParamException( "Cannot save PropertyModel" );
		}
		
		throw new InvalidParamException( "Cannot find PropertyModel" );
	}
	
	public function setProperties( $data = [] )
	{
		foreach( $data as $name => $value ) {
			
			if( is_string($name) &&  ( $PropertyCollection = $this->getProperties( $name)) ){
				
				foreach( $PropertyCollection as $PropertyModel ) {

					$PropertyModel->value = $value;
					
					if( !$PropertyModel->save() ){
						throw new InvalidParamException( "Cannot save PropertyModel" );
					}
					
				}
				
			}
			
		}
		
	}
}