<?php

namespace yozh\properties;

use yozh\base\Module as BaseModule;

class Module extends BaseModule
{

	const MODULE_ID = 'properties';
	
	public $controllerNamespace = 'yozh\\' . self::MODULE_ID . '\controllers';
	
}
