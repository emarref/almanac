<?php

namespace Emarref\Almanac\Source;

use PDO;

class MysqlSource extends AbstractDatabaseSource
{
    /**
     * {@inheritdoc}
     */
    protected function getConnectionOptions()
    {
        return array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        );
    }
}