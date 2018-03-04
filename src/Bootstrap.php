<?php

namespace yozh\properties;

use yozh\base\Bootstrap as BaseBootstrap;

class Bootstrap extends BaseBootstrap
{
	public function bootstrap( $app )
	{
		
		parent::bootstrap( $app );
		
		$app->setModule( 'markdown', 'kartik\markdown\Module' );
		
	}
}