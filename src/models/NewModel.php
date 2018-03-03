<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 03.03.2018
 * Time: 14:07
 */

namespace yozh\properties\models;

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
	public $table_id;
	
	public function rules()
	{
		return [
			[['inputType', 'widget'], 'required'],
			[['inputType', 'widget', 'name', 'model'], 'string', 'max' => 256],
			[['table_id'], 'integer'],
		];
	}
	
	public static function inputsList()
	{
		return ArrayHelper::map( PropertyModel::getInputs(), 'name', 'label' );
	}
	
	public static function widgetsListOutput( )
	{
		$inputs = PropertyModel::getInputs();
		
		$widgetsOutput = [];
		foreach( $inputs as $inputType => $inputConfig ) {
			
			$widgetsOutput[ $inputType ] = $inputType . ":'";
			
			foreach( $inputConfig['widgets'] as $widgetName => $widgetConfig ) {
				$widgetsOutput[ $inputType ] .= '<option value="' . $widgetConfig['name'] . '">' . $widgetConfig['label'] . '</option>';
			}
			
			$widgetsOutput[ $inputType ] .= "'";
		}
		
		return $widgetsOutput;
		
	}
	
}