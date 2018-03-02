<?php

namespace yozh\properties\models;

use Yii;
use yozh\base\models\Model as ActiveRecord;

class PropertiesModel extends ActiveRecord
{
	
	protected static $_inputs;
	
	const TYPES = [
	
	];
	
	protected static function _initConfig()
	{
		$config = [
			
			'input' => [
				'widgets' => [
					'text',
					'textarea',
					'texteditor',
					'markup',
				],
			],
			
			'datetime',
			'date',
			'time',
			
			'boolean' => [
				'widgets' => [
					'switch',
					'radio',
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
	
	public static function getInputs()
	{
		
		if( !static::$_inputs ){
			static::$_inputs = static::_initConfig();
		}
		
		return static::$_inputs;
	}
	
}
