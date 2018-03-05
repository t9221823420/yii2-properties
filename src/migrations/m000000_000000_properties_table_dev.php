<?php

use yozh\base\components\Migration;
use yozh\base\components\db\Schema;
use yozh\properties\models\PropertyModel;

/**
 * Class m180301_142414_add_column_to_properties_table
 */
class m000000_000000_properties_table_dev extends Migration
{
	protected static $_table = 'property';
	
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		static::$_columns = [
			'id'       => $this->primaryKey(),
			'table'    => $this->string()->notNull()->after( 'id' ),
			'table_pk' => $this->integer()->notNull()->after( 'table' ),
			'model'    => $this->string()->notNull()->after( 'table_pk' ),
			'name'     => $this->string()->notNull()->after( 'model' ),
			'type'     => $this->enum( PropertyModel::getInputsList() )->notNull()->defaultValue( PropertyModel::INPUT_TYPE_STRING )->after( 'name' ),
			'widget'   => $this->enum( PropertyModel::getWidgetsList() )->notNull()->defaultValue( PropertyModel::WIDGET_TYPE_TEXT )->after( 'type' ),
			'config'   => $this->text()->null()->after( 'widget' ),
			
			'validators' => $this->text()->null()->after( 'config' ),
			
			'order'  => $this->integer()->null()->after( 'validators' ),
			'parent' => $this->integer()->null()->after( 'order' ),
			
			PropertyModel::INPUT_TYPE_STRING => $this->string()->null()->after( 'validators' ),
			PropertyModel::INPUT_TYPE_TEXT   => $this->text()->null()->after( PropertyModel::INPUT_TYPE_STRING ),
			
			PropertyModel::INPUT_TYPE_INTEGER => $this->integer()->null()->after( PropertyModel::INPUT_TYPE_TEXT ),
			PropertyModel::INPUT_TYPE_DECIMAL => $this->double()->null()->after( PropertyModel::INPUT_TYPE_INTEGER ),
			
			PropertyModel::INPUT_TYPE_DATE     => $this->date()->null()->after( PropertyModel::INPUT_TYPE_DECIMAL ),
			PropertyModel::INPUT_TYPE_TIME     => $this->time()->null()->after( PropertyModel::INPUT_TYPE_DATE ),
			PropertyModel::INPUT_TYPE_DATETIME => $this->dateTime()->null()->after( PropertyModel::INPUT_TYPE_TIME ),
		];
		
		$this->alterTable( [
			'mode' => self::ALTER_MODE_IGNORE,
		] );
		
		return false;
		
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		return false;
	}
	
}
