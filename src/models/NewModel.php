<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 03.03.2018
 * Time: 14:07
 */

namespace yozh\properties\models;

use Yii;
use yii\base\Model;
use yozh\properties\models\PropertyModel;
use yii\helpers\ArrayHelper;

class NewModel extends Model
{
	
	public $inputType;
	public $widget;
	public $name;
	public $value;
	public $model;
	public $owner_id;
	
	/**
	 * @return mixed
	 */
	public function getOid()
	{
		return $this->owner_id;
	}
	
	/**
	 * @param mixed $oid
	 */
	public function setOid( $value ): void
	{
		$this->owner_id = $value;
	}
	
	public static function inputsList()
	{
		$inputs = PropertyModel::getInputs();
		
		$output = [];
		foreach( $inputs as $inputType => $inputConfig ) {
			$output[ $inputType ] = $inputConfig['label'];
		}
		
		return $output;
	}
	
	public static function widgetsListOutput()
	{
		$inputs = PropertyModel::getInputs();
		
		$output = [];
		foreach( $inputs as $inputType => $inputConfig ) {
			
			$output[ $inputType ] = $inputType . ":'";
			
			foreach( $inputConfig['widgets'] as $widgetName => $widgetConfig ) {
				$output[ $inputType ] .= '<option value="' . $widgetConfig['name'] . '">' . $widgetConfig['label'] . '</option>';
			}
			
			$output[ $inputType ] .= "'";
		}
		
		return $output;
		
	}
	
	public function rules()
	{
		return [
			[ [ 'inputType', 'widget', 'model', 'owner_id',  ], 'required' ],
			[ [ 'inputType', 'widget', 'name', 'model' ], 'string', 'max' => 256 ],
			[ [ 'owner_id' ], 'integer' ],
			[ [ 'oid' ], 'safe' ],
		];
	}
	
	
	
}