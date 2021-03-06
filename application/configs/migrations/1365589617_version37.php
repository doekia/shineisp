<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version37 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('orders_items_servers', array(
             'relationship_id' => 
             array(
              'type' => 'integer',
              'fixed' => '0',
              'unsigned' => '',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '4',
             ),
             'server_id' => 
             array(
              'type' => 'int',
              'notnull' => '1',
              'length' => '4',
             ),
             'order_id' => 
             array(
              'type' => 'int',
              'notnull' => '1',
              'length' => '4',
             ),
             'orderitem_id' => 
             array(
              'type' => 'int',
              'notnull' => '1',
              'length' => '4',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'relationship_id',
             ),
             'charset' => 'UTF8',
             ));
    }

    public function down()
    {
        $this->dropTable('orders_items_servers');
    }
}