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
	public function getProperties()
	{
		if( !$this->_properties ){
			$this->_properties = PropertyModel::findAll([
				'model' => $this->owner::className(),
				'table_id' => $this->owner->primaryKey,
			]);
		}
		
		return $this->_properties;
	}
	
	/**
	 * @param mixed $PropertyModel
	 */
	public function setProperties( PropertyModel $PropertyModel ): void
	{
		$this->_PropertyModel = $PropertyModel;
	}
	
	
}