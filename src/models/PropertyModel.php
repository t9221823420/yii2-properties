<?php

namespace yozh\properties\models;

use Yii;
use yii\base\Model;
use yozh\base\models\Model as ActiveRecord;

class PropertyModel extends ActiveRecord
{
	
	const INPUT_TYPE_STRING   = 'string';
	const INPUT_TYPE_TEXT     = 'text';
	const INPUT_TYPE_NUMBER   = 'number';
	const INPUT_TYPE_DATE     = 'date';
	const INPUT_TYPE_TIME     = 'time';
	const INPUT_TYPE_DATETIME = 'datetime';
	const INPUT_TYPE_BOOLEAN  = 'boolean';
	const INPUT_TYPE_LIST     = 'list';
	
	const INPUT_TYPE_HASH = 'hash';
	const INPUT_TYPE_JSON = 'json';
	
	const WIDGET_TYPE_TEXT       = 'text';
	const WIDGET_TYPE_TEXTAREA   = 'textarea';
	const WIDGET_TYPE_TEXTEDITOR = 'texteditor';
	const WIDGET_TYPE_MARKUP     = 'markup';
	
	const WIDGET_TYPE_DATE     = 'date';
	const WIDGET_TYPE_TIME     = 'time';
	const WIDGET_TYPE_DATETIME = 'datetime';
	
	const WIDGET_TYPE_SWITCH   = 'switch';
	const WIDGET_TYPE_RADIO    = 'radio';
	const WIDGET_TYPE_CHECKBOX = 'checkbox';
	const WIDGET_TYPE_SELECT   = 'select';
	const WIDGET_TYPE_DROPDOWN = 'dropdown';
	
	protected static $_inputsConfig;
	
	protected $_value;
	protected $_model;
	
	public static function tableName()
	{
		return 'property';
	}
	
	public static function getInputs()
	{
		
		if( !static::$_inputsConfig ) {
			static::$_inputsConfig = static::_initConfig();
		}
		
		return static::$_inputsConfig;
	}
	
	protected static function _initConfig()
	{
		$config = [
			
			static::INPUT_TYPE_STRING => [
				'widgets' => [
					static::WIDGET_TYPE_TEXT,
				],
			],
			
			static::INPUT_TYPE_TEXT => [
				'widgets' => [
					static::WIDGET_TYPE_TEXTAREA,
					static::WIDGET_TYPE_TEXTEDITOR,
					static::WIDGET_TYPE_MARKUP,
				],
			],
			
			static::INPUT_TYPE_DATE,
			static::INPUT_TYPE_TIME,
			static::INPUT_TYPE_DATETIME,
			
			static::INPUT_TYPE_BOOLEAN => [
				'widgets' => [
					static::WIDGET_TYPE_SWITCH,
					static::WIDGET_TYPE_RADIO,
				],
			],
		
		];
		
		$inputResult = [];
		
		foreach( $config as $inputType => $inputConfig ) {
			
			if( !is_array( $inputConfig ) ) {
				$inputType   = $inputConfig;
				$inputConfig = [
					'widgets' => [
						$inputType,
					],
				];
			}
			
			if( !isset( $inputConfig['name'] ) ) { //
				$inputConfig['name'] = $inputType;
			}
			
			if( !isset( $inputConfig['label'] ) ) { //
				$inputConfig['label'] = ucfirst( $inputType );
			}
			
			$inputConfig['label'] = Yii::t( 'properties', $inputConfig['label'] );
			
			foreach( $inputConfig['widgets'] as $widgetName => $widgetConfig ) {
				
				unset( $inputConfig['widgets'][ $widgetName ] );
				
				if( !is_array( $widgetConfig ) ) {
					$widgetName   = $widgetConfig;
					$widgetConfig = [];
				}
				
				if( !isset( $widgetConfig['name'] ) ) { //
					$widgetConfig['name'] = $widgetName;
				}
				
				if( !isset( $widgetConfig['label'] ) ) { //
					$widgetConfig['label'] = ucfirst( $widgetName );
				}
				
				$inputConfig['widgets'][ $widgetName ] = $widgetConfig;
				
			}
			
			$inputResult[ $inputType ] = $inputConfig;
		}
		
		return $inputResult;
	}
	
	public function rules()
	{
		return [
			[ [ 'table', 'table_id', 'model' ], 'required' ],
			[ [ 'type' ], 'string' ],
			[ [ 'table', 'widget', 'name' ], 'string', 'max' => 256 ],
			[ [ 'inputType' ], 'in', 'range' => array_keys( static::$_inputsConfig ) ],
			[ [ 'table_id', 'size' ], 'integer' ],
			[ [ 'model' ], 'exist', 'skipOnError' => true, 'targetAttribute' => [ 'table_id' => 'id' ], 'targetClass' => $this->getModelClass() ],
		
		];
	}
	
	public function getModelClass()
	{
		if( $this->model ) { //
			return $this->model::className();
		}
		else if( ( $data = Yii::$app->request->post( 'NewModel' ) ) && isset( $data['model'] ) ) { //
			return $data['model'];
		}
		else {
			$this->addError( 'table_id', 'Model not set' );
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
					$this->_model = $modelClass::findOne( $this->getAttribute( 'table_id' ) );
				}
				
				return $this->_model;
			
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
					
					$model = $value;
					$value = $model::className();
					
				}
				else if( !( is_string( $value ) && class_exists( $value ) && ( $model = new $value() ) instanceof Model ) ) {
					throw new \yii\base\InvalidParamException( "$value have to be instance of Model" );
				}
				
				if( !$this->getAttribute('table' ) ) {
					$this->setAttribute( 'table', $model::tableName() );
				}
				
				if( !$this->getAttribute('table_id' ) ) {
					$this->setAttribute( 'table_id', $model->primaryKey );
				}
				
				parent::__set( $name, $value );
				
				break;
			
			case 'json' :
				
				parent::__set( $name, json_encode( $value ) );
				
				break;
			
			case 'value' :
				
				$this->_value = $value;
				
				break;
			
			case 'inputType' :
				
				parent::__set( 'type', $value );
				
				break;
			
			default:
				parent::__set( $name, $value );
			
		}
	}
	
	
}
