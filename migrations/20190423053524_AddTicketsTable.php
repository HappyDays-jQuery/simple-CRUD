<?php

use Phpmig\Migration\Migration;

class AddTicketsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = "CREATE TABLE `tickets` (`id` INTEGER, `subject` TEXT, PRIMARY KEY (`id`));";
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
