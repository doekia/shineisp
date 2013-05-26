<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version94 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('customers', 'customers_language_id_languages_language_id', array(
             'name' => 'customers_language_id_languages_language_id',
             'local' => 'language_id',
             'foreign' => 'language_id',
             'foreignTable' => 'languages',
             ));
        $this->addIndex('customers', 'customers_language_id', array(
             'fields' => 
             array(
              0 => 'language_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('customers', 'customers_language_id_languages_language_id');
        $this->removeIndex('customers', 'customers_language_id', array(
             'fields' => 
             array(
              0 => 'language_id',
             ),
             ));
    }
}