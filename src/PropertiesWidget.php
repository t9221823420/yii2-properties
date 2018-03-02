<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 02.03.2018
 * Time: 17:15
 */

namespace yozh\properties;

use Yii;

class PropertiesWidget extends \yozh\base\components\Widget
{
	
	public $model;
	public $form;
	
	public function run()
	{
		return $this->render( 'form' );
	}
	
}