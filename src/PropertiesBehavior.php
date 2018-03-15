<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 02.03.2018
 * Time: 17:14
 */

namespace yozh\properties;

use yozh\properties\traits\PropertiesBehaviorTrait;
use yozh\properties\models\PropertiesModel;

class PropertiesBehavior extends \yozh\base\components\Behavior
{
	use PropertiesBehaviorTrait;
	
	protected static $_baseModel = PropertiesModel::class;
	
	
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
	
}