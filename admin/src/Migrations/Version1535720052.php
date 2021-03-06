<?php

namespace Ministra\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
class Version1535720052 extends \Doctrine\DBAL\Migrations\AbstractMigration
{
    public function up(\Doctrine\DBAL\Schema\Schema $schema)
    {
        if (!$schema->getTable('apps')->hasColumn('autoupdate')) {
            $this->addSql('ALTER TABLE `apps` ADD COLUMN `autoupdate` TINYINT NOT NULL DEFAULT 0;');
        }
    }
    public function down(\Doctrine\DBAL\Schema\Schema $schema)
    {
        $this->addSql(<<<EOL
ALTER TABLE `apps` DROP COLUMN `autoupdate`;
--
EOL
);
    }
}
