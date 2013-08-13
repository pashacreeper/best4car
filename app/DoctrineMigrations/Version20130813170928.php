<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130813170928 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $this->addSql("UPDATE `dictionaries` SET `short_name`='evaquate' WHERE `short_name`='evaqu'");
        $this->addSql("UPDATE `dictionaries` SET `short_name`='waiting' WHERE `short_name`='waiti'");
        $this->addSql("UPDATE `dictionaries` SET `short_name`='credit_card' WHERE `short_name`='credi'");
        $this->addSql("UPDATE `dictionaries` SET `short_name`='coffee' WHERE `short_name`='coffe'");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
    }
}
