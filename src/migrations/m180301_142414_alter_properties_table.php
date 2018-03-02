<?php

use yozh\base\components\Migration;
use yozh\base\components\db\Schema;

/**
 * Class m180301_142414_add_column_to_properties_table
 */
class m180301_142414_alter_properties_table extends Migration
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
		
		$this->alterTable();
		
		throw new \yii\base\InvalidParamException( "Break" );
		
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
