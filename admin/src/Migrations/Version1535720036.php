<?php

namespace Ministra\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
class Version1535720036 extends \Doctrine\DBAL\Migrations\AbstractMigration
{
    public function up(\Doctrine\DBAL\Schema\Schema $schema)
    {
        if (!$schema->getTable('adm_grp_action_access')->hasColumn('only_top_admin')) {
            $this->addSql('ALTER TABLE `adm_grp_action_access` ADD COLUMN `only_top_admin` TINYINT NOT NULL DEFAULT 0;');
        }
        if (!$schema->getTable('administrators')->hasColumn('reseller_id')) {
            $this->addSql('ALTER TABLE `administrators` ADD COLUMN `reseller_id` INT(11) NULL DEFAULT NULL;');
        }
        $this->addSql(<<<EOL
--
CREATE TABLE IF NOT EXISTS `reseller` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL DEFAULT '',
  `created` DATETIME,
  `modified` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)) DEFAULT CHARSET = utf8;

ALTER TABLE `users` ADD COLUMN `reseller_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `stb_groups` ADD COLUMN `reseller_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `admin_groups` ADD COLUMN `reseller_id` INT(11) NULL DEFAULT NULL;

INSERT INTO `adm_grp_action_access`
        (`controller_name`,             `action_name`,    `is_ajax`,  `description`, `hidden`, `only_top_admin`)
VALUES  ('admins',                   'resellers-list',            0, 'Resellers. List of resellers',  0, 1),
        ('admins',              'resellers-list-json',            1, 'Resellers. List of resellers  by page + filters',  0, 1),
        ('admins',                   'resellers-save',            1, 'Adding and editing resellers',  0, 1),
        ('admins',                 'resellers-delete',            1, 'Deleting resellers',  0, 1),
        ('admins',           'move-users-to-reseller',            1, 'Move all users of current reseller to another reseller',  0, 1),
        ('admins',           'move-admin-to-reseller',            1, 'Change reseller for current admin',  0, 1),
        ('admins',     'move-admin-group-to-reseller',            1, 'Change reseller for current group of admin',  0, 1),
        ('users',             'move-user-to-reseller',            1, 'Change reseller for current user',  0, 1),
        ('users',       'move-user-group-to-reseller',            1, 'Change reseller for current group',  0, 1);
EOL
);
    }
    public function down(\Doctrine\DBAL\Schema\Schema $schema)
    {
        $this->addSql(<<<EOL
DROP TABLE IF EXISTS `reseller`;
ALTER TABLE `administrators` DROP COLUMN `reseller_id`;
ALTER TABLE `users` DROP COLUMN `reseller_id`;
ALTER TABLE `adm_grp_action_access` DROP COLUMN `only_top_admin`;
--
EOL
);
    }
}
