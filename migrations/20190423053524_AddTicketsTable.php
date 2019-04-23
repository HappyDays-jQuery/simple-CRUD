<?php

use Phpmig\Migration\Migration;

class AddTicketsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "create table `tickets` (`id` INTEGER unsigned NOT NULL auto_increment, `subject` varchar(255) NOT NULL, PRIMARY KEY(`id`));";
        $c = $this->getContainer();
        $c['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `tickets`;";
        $c = $this->getContainer();
        $c['db']->query($sql);
    }
}
