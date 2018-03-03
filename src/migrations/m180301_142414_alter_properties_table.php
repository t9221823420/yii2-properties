<?php

use yozh\base\components\Migration;
use yozh\base\components\db\Schema;

/**
 * Class m180301_142414_add_column_to_properties_table
 */
class m180301_142414_alter_properties_table extends Migration
{
	protected static $_table = 'property';
	
	public function safeUp()
	{
		static::$_columns = [
			'id'       => $this->primaryKey(),
			'table'    => $this->string( 256 )->notNull()->after( 'id' ),
			'table_id' => $this->bigInteger( 20 )->notNull()->after( 'table' ),
			'model'    => $this->string( 256 )->notNull()->after( 'table_id' ),
			'type'     => $this->enum( Schema::getTypes() )->notNull()->defaultValue( 'string' ),
			'size'     => $this->integer()->defaultValue( null )->after( 'type' ),
			'name'     => $this->string( 256 )->defaultValue( null )->after( 'size' ),
			
			'validators'     => $this->text()->defaultValue( null )->after( 'name' ),
			'widget'     => $this->string( 256 )->defaultValue( null )->after( 'validators' ),
		];
		
		$this->alterTable([
			'mode' => self::ALTER_MODE_IGNORE,
		]);
		
		return false;
		
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m180301_142414_add_column_to_properties_table cannot be reverted.\n";
		
		return false;
	}
	
}
