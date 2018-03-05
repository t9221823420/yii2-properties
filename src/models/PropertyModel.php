<?php

namespace yozh\properties\models;

use Yii;
use yii\base\Model;
use yozh\base\models\Model as ActiveRecord;

class PropertyModel extends ActiveRecord
{
	
	const INPUT_TYPE_STRING = 'type_string';
	const INPUT_TYPE_TEXT   = 'type_text';
	
	const INPUT_TYPE_INTEGER = 'type_integer';
	const INPUT_TYPE_DECIMAL = 'type_decimal';
	
	const INPUT_TYPE_DATE     = 'type_date';
	const INPUT_TYPE_TIME     = 'type_time';
	const INPUT_TYPE_DATETIME = 'type_datetime';
	
	const INPUT_TYPE_BOOLEAN = 'type_boolean';
	const INPUT_TYPE_LIST    = 'type_list';
	
	const INPUT_TYPE_HASH = 'type_hash';
	const INPUT_TYPE_JSON = 'type_json';
	
	const INPUT_TYPE_DEFAULT = self::INPUT_TYPE_STRING;
	
	const WIDGET_TYPE_TEXT       = 'widget_text';
	const WIDGET_TYPE_TEXTAREA   = 'widget_textarea';
	const WIDGET_TYPE_TEXTEDITOR = 'widget_texteditor';
	const WIDGET_TYPE_MARKUP     = 'widget_markup';
	const WIDGET_TYPE_PASSWORD   = 'widget_password';
	
	const WIDGET_TYPE_DATE     = 'widget_date';
	const WIDGET_TYPE_TIME     = 'widget_time';
	const WIDGET_TYPE_DATETIME = 'widget_datetime';
	
	const WIDGET_TYPE_SWITCH   = 'widget_switch';
	const WIDGET_TYPE_RADIO    = 'widget_radio';
	const WIDGET_TYPE_CHECKBOX = 'widget_checkbox';
	const WIDGET_TYPE_SELECT   = 'widget_select';
	const WIDGET_TYPE_DROPDOWN = 'widget_dropdown';
	
	protected static $_inputsConfig;
	
	protected $_value;
	protected $_model;
	
	public static function tableName()
	{
		return 'property';
	}
	
	static public function getInputsLabels()
	{
		return [
			static::INPUT_TYPE_STRING   => Yii::t( 'properties', 'String' ),
			static::INPUT_TYPE_TEXT     => Yii::t( 'properties', 'Text' ),
			static::INPUT_TYPE_INTEGER  => Yii::t( 'properties', 'Integer' ),
			static::INPUT_TYPE_DECIMAL  => Yii::t( 'properties', 'Decimal' ),
			static::INPUT_TYPE_DATE     => Yii::t( 'properties', 'Date' ),
			static::INPUT_TYPE_TIME     => Yii::t( 'properties', 'Time' ),
			static::INPUT_TYPE_DATETIME => Yii::t( 'properties', 'Datetime' ),
			static::INPUT_TYPE_BOOLEAN  => Yii::t( 'properties', 'Boolean' ),
			static::INPUT_TYPE_LIST     => Yii::t( 'properties', 'List' ),
		
		];
	}
	
	public function rules()
	{
		return [
			[ [ 'table', 'owner_id', 'model', 'type', 'widget' ], 'required' ],
			[ [ 'table', 'name' ], 'string', 'max' => 255 ],
			[ [ 'inputType', 'type' ], 'in', 'range' => PropertyModel::getInputsList() ],
			[ [ 'widget' ], 'in', 'range' => PropertyModel::getWidgetsList() ],
			[ [ 'owner_id' ], 'integer' ],
			[ [ 'model' ], 'exist', 'skipOnError' => true, 'targetAttribute' => [ 'owner_id' => 'id' ], 'targetClass' => $this->getModelClass() ],
		
		];
	}
	
	static public function getInputsList()
	{
		return static::_getConstList( 'INPUT_TYPE_' );
	}
	
	static protected function _getConstList( $prefix )
	{
		$list = ( new \ReflectionClass( static::class ) )->getConstants();
		
		foreach( $list as $key => $const ) {
			if( strpos( $key, $prefix ) === false ) {
				unset( $list[ $key ] );
			}
		}
		
		return $list;
	}
	
	static public function getWidgetsList()
	{
		return static::_getConstList( 'WIDGET_TYPE_' );
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
					
					$model = $value;
					$value = $model::className();
					
				}
				else if( !( is_string( $value ) && class_exists( $value ) && ( $model = new $value() ) instanceof Model ) ) {
					throw new \yii\base\InvalidParamException( "$value have to be instance of Model" );
				}
				
				if( !$this->getAttribute( 'table' ) ) {
					$this->setAttribute( 'table', $model::tableName() );
				}
				
				if( !$this->getAttribute( 'table_pk' ) ) {
					$this->setAttribute( 'table_pk', $model->primaryKey );
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
	
	static public function getDefaultWidget( $inputType )
	{
		$inputs = static::getInputs();
		
		if( isset( $inputs[ $inputType ]['widgets'] ) ) {
			return reset( $inputs[ $inputType ]['widgets'] );
		}
		
		throw new \yii\base\InvalidParamException( "Default widget for inputType '$inputType' not found." );
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
					static::WIDGET_TYPE_TEXT => [
						'rules'  => [
						
						],
						'config' => [],
					],
				],
			],
			
			static::INPUT_TYPE_TEXT => [
				'widgets' => [
					static::WIDGET_TYPE_TEXTAREA,
					static::WIDGET_TYPE_TEXTEDITOR,
					static::WIDGET_TYPE_MARKUP,
				],
			],
			
			/*
			static::INPUT_TYPE_DATE     => [
				'widgets' => [
					static::WIDGET_TYPE_DATE,
				],
			],
			static::INPUT_TYPE_TIME     => [
				'widgets' => [
					static::WIDGET_TYPE_TIME,
				],
			],
			static::INPUT_TYPE_DATETIME => [
				'widgets' => [
					static::WIDGET_TYPE_DATETIME,
				],
			],
			
			static::INPUT_TYPE_BOOLEAN => [
				'widgets' => [
					static::WIDGET_TYPE_SWITCH,
					static::WIDGET_TYPE_RADIO,
				],
			],
			*/
		
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
				$inputConfig['label'] = static::getLabel( $inputType );
			}
			
			$inputConfig['label'] = Yii::t( 'app', ucfirst( $inputConfig['label'] ) );
			
			foreach( $inputConfig['widgets'] as $widgetName => $widgetConfig ) {
				
				unset( $inputConfig['widgets'][ $widgetName ] );
				
				if( !is_array( $widgetConfig ) ) {
					$widgetName   = $widgetConfig;
					$widgetConfig = [
						'rules'  => [],
						'config' => [],
					];
				}
				
				if( !isset( $widgetConfig['name'] ) ) { //
					$widgetConfig['name'] = $widgetName;
				}
				
				if( !isset( $widgetConfig['label'] ) ) { //
					$widgetConfig['label'] = static::getLabel( $widgetName );
				}
				
				$widgetConfig['label'] = Yii::t( 'app', ucfirst( $widgetConfig['label'] ) );
				
				$inputConfig['widgets'][ $widgetName ] = $widgetConfig;
				
			}
			
			$inputResult[ $inputType ] = $inputConfig;
		}
		
		return $inputResult;
	}
	
	public static function getLabel( $name )
	{
		return preg_replace( '/^(type_|widget_)/', '', $name );
		
	}
	
	
}
