<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version4 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('settings_groups', 'description', 'string', '', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('settings_groups', 'description');
    }
}