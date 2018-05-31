<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 11.03.2018
 * Time: 22:00
 */

namespace yozh\properties\models;

use yozh\base\models\BaseModel as ActiveRecord;

class PropertyModel extends ActiveRecord
{
	protected $_value;
	protected $_model;
	
	public static function tableName()
	{
		return 'property';
	}
	
	
}