<?php

namespace yozh\properties\models;

use Yii;
use yii\base\Model;
use yozh\base\models\BaseActiveRecord as ActiveRecord;
use yozh\form\ActiveField;
use yozh\widget\widgets\BaseWidget as Widget;

class PropertiesModel extends ActiveRecord
{
	
	public static function tableName()
	{
		return 'property';
	}
	
	/*
	static public function getInputsLabels()
	{
		return [
			ActiveField::INPUT_TYPE_STRING   => Yii::t( 'properties', 'String' ),
			ActiveField::INPUT_TYPE_TEXT     => Yii::t( 'properties', 'Text' ),
			ActiveField::INPUT_TYPE_INTEGER  => Yii::t( 'properties', 'Integer' ),
			ActiveField::INPUT_TYPE_DECIMAL  => Yii::t( 'properties', 'Decimal' ),
			ActiveField::INPUT_TYPE_DATE     => Yii::t( 'properties', 'Date' ),
			ActiveField::INPUT_TYPE_TIME     => Yii::t( 'properties', 'Time' ),
			ActiveField::INPUT_TYPE_DATETIME => Yii::t( 'properties', 'Datetime' ),
			ActiveField::INPUT_TYPE_BOOLEAN  => Yii::t( 'properties', 'Boolean' ),
			ActiveField::INPUT_TYPE_LIST     => Yii::t( 'properties', 'List' ),
		
		];
	}
	*/
	

	
	public function rules( $rules = [] )
	{
		return [
			[ [ 'table', 'owner_id', 'Mmdel', 'type', 'widget' ], 'required' ],
			[ [ 'table', 'name' ], 'string', 'max' => 255 ],
			[ [ 'inputType', 'type' ], 'in', 'range' => ActiveField::getConstants('INPUT_TYPE_') ],
			[ [ 'widgetType', 'widget' ], 'in', 'range' => ActiveField::getConstants('WIDGET_TYPE_') ],
			[ [ 'owner_id' ], 'integer' ],
			[ [ 'model' ], 'exist', 'skipOnError' => true, 'targetAttribute' => [ 'owner_id' => 'id' ], 'targetClass' => $this->getModelClass() ],
		
		];
	}
	
	public function getModelClass()
	{
		if( $this->model ) { //
			return $this->model::className();
		}
		else if( ( $data = Yii::$app->request->post( 'AddPropertyModel' ) ) && isset( $data['Model'] ) ) { //
			return $data['Model'];
		}
		else {
			$this->addError( 'owner_id', 'Model not set' );
		}
	}
	
	public function __get( $name )
	{
		
		switch( $name ) {
			
			case 'value' :
				
				return $this->_value;
			
			case 'inputType' :
				
				return parent::__get( 'type' );
			
			case 'model' :
				
				$modelClass = parent::__get( $name );
				
				if( is_null( $this->_model ) && class_exists( $modelClass ) ) {
					$this->_model = $modelClass::findOne( $this->getAttribute( 'table_pk' ) );
				}
				
				return $this->_model;
			
			case 'table_pk' :
			case 'owner_id' :
			case 'oid' :
				
				return parent::__get( 'table_pk' );
			
			default:
				
				return parent::__get( $name );
		}
	}
	
	public function __set( $name, $value )
	{
		switch( $name ) {
			
			case 'table' :
				
				$tableNames = Yii::$app->cache->getOrSet( 'Properties.tableNames', function() {
					return Yii::$app->db->getSchema()->getTableNames();
				} );
				
				if( !is_string( $value ) || !in_array( $value, $tableNames ) ) { //
					throw new \yii\base\InvalidParamException( "Table $value does not exists" );
				}
				
				parent::__set( $name, $value );
				
				break;
			
			case 'model' :
				
				if( $value instanceof Model ) { //
					
					$Model = $value;
					$value = $Model::className();
					
				}
				else if( !( is_string( $value ) && class_exists( $value ) && ( $Model = new $value() ) instanceof Model ) ) {
					throw new \yii\base\InvalidParamException( "$value have to be instance of Model" );
				}
				
				if( !$this->getAttribute( 'table' ) ) {
					$this->setAttribute( 'table', $Model::tableName() );
				}
				
				if( !$this->getAttribute( 'table_pk' ) ) {
					$this->setAttribute( 'table_pk', $Model->primaryKey );
				}
				
				parent::__set( $name, $value );
				
				break;
			
			case 'inputType' :
			case 'type' :
				
				parent::__set( 'type', $value );
				
				if( empty( $this->getAttribute( 'widget' ) ) ) {
					
					$this->setAttribute( 'widget', static::getDefaultWidget( $value )['name'] );
					
				}
				
				break;
			
			case 'json' :
				
				parent::__set( $name, json_encode( $value ) );
				
				break;
			
			case 'value' :
				
				$this->_value = $value;
				
				$field = $this->getAttribute( 'type' );
				
				$this->setAttribute( $field, $value );
				
				break;
			
			case 'table_pk' :
			case 'owner_id' :
			case 'oid' :
				
				parent::__set( 'table_pk', $value );
				
				break;
			
			default:
				parent::__set( $name, $value );
			
		}
	}
	
	
}
