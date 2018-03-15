<?php

use yozh\base\components\Migration as BaseMigration;

/**
 * Class m180301_142414_add_column_to_properties_table
 */
class Migration extends BaseMigration
{
	
	public function _getColumns( $columns = [] )
	{
		return array_merge_recursive( parent::_getColumns( $columns ), [
			'table'    => $this->string()->notNull()->after( 'id' ),
			'table_pk' => $this->integer()->notNull()->after( 'table' ),
			'model'    => $this->string()->notNull()->after( 'table_pk' ),
		] );
	}
	
}
