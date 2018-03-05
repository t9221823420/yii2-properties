<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 02.03.2018
 * Time: 17:14
 */

namespace yozh\properties;

use yii\db\ActiveQuery;
use yozh\base\components\ArrayHelper;
use yozh\properties\models\PropertyModel;
use yii\base\Model;
use yii\base\InvalidParamException;

class PropertiesBehavior extends \yozh\base\components\Behavior
{
	
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
	public function getProperty( $id_name, $defaultValue = null )
	{
		return $this->getProperties( $id_name, true, $defaultValue );
	}
	
	/**
	 * @return mixed
	 */
	public function getProperties( $condition = null, $one = false, $defaultValue = null )
	{
		// if set simple numeric ID
		if( is_numeric( $condition ) ) {
			$query = PropertyModel::find()->where( [
				'id' => $condition,
			] )
			;
		}
		// in any other cases prepare $ActiveQuery
		else {
			
			/**
			 * @var $query ActiveQuery
			 */
			$query = PropertyModel::find()->where( [
				'model'    => $this->owner::className(),
				'table_pk' => $this->owner->primaryKey,
			] )
			;
			
		}
		
		// if set simple string for Name convert it to set of Names
		if( is_string( $condition ) ) {
			$condition = [ $condition ];
		}
		
		if( is_array( $condition ) && count( $condition ) ) {
			
			// if $condition is associative array, it's Condition for Query
			if( is_string( key( $condition ) ) ) { //
				$query->andWhere( $condition );
			}
			// if first element is Numeric - it's set of IDs
			else if( is_numeric( reset( $condition ) ) ) { //
				$query->andWhere( [ 'in', 'id', $condition ] );
			}
			// ... it's set of Names
			else {
				$query->andWhere( [ 'in', 'name', $condition ] );
			}
			
		}
		
		$result = $one ? $query->one() : $query->all();
		
		if( $one && !$result ) {
			
			if( !is_null( $defaultValue ) ) { //
				$result = new PropertyModel( [
					'model' => $this->owner,
					'type'  => PropertyModel::INPUT_TYPE_DEFAULT,
					'value' => $defaultValue,
				] );
			}
			else { //
				throw new \yii\base\InvalidParamException( "Trying to get non-existing property '$condition'" );
			}
			
		}
		
		return $result;
	}
	
	/**
	 * @param mixed $PropertyModel
	 */
	public function setProperties( $condition, $value, $create = false, $type = PropertyModel::INPUT_TYPE_DEFAULT )
	{
		
		if( $properties = $this->owner->getProperties( $condition ) ) {
			
			foreach( $properties as $PropertyModel ) {
				$PropertyModel->value = $value;
				
				if( !$PropertyModel->save() ) {
					throw new InvalidParamException( "Cannot save PropertyModel" );
				}
			}
			
		}
		// create new if $create = true
		else if( $create ) {
			
			$this->addProperties($condition, [
				'model' => $this->owner,
				'type'  => $type,
				'value' => $value,
			]);
			
		}
		
		return $this->owner;
	}
	
	public function addProperties( $name, $config = null )
	{
		// if set Name & Value Or Config
		if( is_string( $name ) && !empty( $config ) ) {
			$data = [ $name => $config ];
		}
		else if( is_array( $name ) ) {
			$data = $name;
		}
		
		if( is_array( $data ) ) {
			
			$inputs = PropertyModel::getInputs();
			
			foreach( $data as $name => $config ) {
				
				if( is_string( $config ) ) {
					
					/*
					 * We need to set this just before create PropertyModel
					 * because to set value we need to know inputType
					 */
					$value = $config;
					
					$config = [
						'model' => $this->owner,
					];
					
				}
				else {
					unset( $value );
				}
				
				if( !isset( $config['type'] ) || empty( $config['type'] ) || !in_array( $config['type'], array_keys( $inputs ) ) ) {
					$config['type'] = PropertyModel::INPUT_TYPE_DEFAULT;
				}
				
				if( !isset( $config['widget'] ) || empty( $config['widget'] )
					|| !in_array( $config['widget'], array_keys( $inputs[ $config['type'] ]['widgets'] ) )
				) {
					$config['widget'] = PropertyModel::getDefaultWidget( PropertyModel::INPUT_TYPE_DEFAULT )['name'];
				}
				
				if( !isset( $config['value'] ) && isset( $value ) ) {
					$config['value'] = $value;
				}
				
				if( !isset( $config['name'] ) || empty( $config['name'] ) ) {
					
					if( is_numeric( $name ) ) { //
						$config['name'] = PropertyModel::getLabel( $config['type'] );
					}
					else { //
						$config['name'] = $name;
					}
					
				}
				
				$PropertyModel = new PropertyModel( $config );
				
				if( !$PropertyModel->save() ) {
					throw new InvalidParamException( "Cannot save PropertyModel" );
				}
				
				return $this->owner;
			}
		}
		
		throw new InvalidParamException( 'Wrong config' );
	}
	
	public function deleteProperties( $condition = null )
	{
		if( $properties = $this->owner->getProperties( $condition ) ) {
			
			foreach( $properties as $PropertyModel ) {
				if( !$PropertyModel->delete() ) {
					throw new InvalidParamException( "Cannot delete PropertyModel" );
				}
			}
			
		}
		
	}
	
}