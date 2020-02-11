<?php

namespace Ministra\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
class Version1535720168 extends \Doctrine\DBAL\Migrations\AbstractMigration
{
    public function up(\Doctrine\DBAL\Schema\Schema $schema)
    {
        $this->addSql(<<<EOL
--
ALTER TABLE `video_season_series` MODIFY `series_number` INT NOT NULL;
EOL
);
    }
    public function down(\Doctrine\DBAL\Schema\Schema $schema)
    {
    }
}
