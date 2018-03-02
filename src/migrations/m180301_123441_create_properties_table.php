<?php

use yozh\base\components\Migration;
use yozh\properties\models\PropertiesModel;
use yozh\base\components\db\Schema;

/**
 * Handles the creation of table `properties`.
 */
class m180301_123441_create_properties_table extends Migration
{
	
	protected static $_table = 'properties';
	
	public function safeUp()
	{
		static::$_columns = [
			'id'      => $this->primaryKey(),
			'host'    => $this->string( 256 )->notNull(),
			'host_id' => $this->bigInteger( 20 )->notNull(),
			'type'    => $this->enum( Schema::getTypes() )->notNull()->defaultValue( 'string' ),
			'size'    => $this->integer()->defaultValue( null ),
		];
		
		$this->alterTable( );
		
	}
	
}
