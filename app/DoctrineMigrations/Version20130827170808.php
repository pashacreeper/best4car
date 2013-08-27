<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130827170808 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3A8BAC62AF");
        $this->addSql("ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A8BAC62AF FOREIGN KEY (city_id) REFERENCES Country (id) ON DELETE SET NULL");
        $this->addSql("ALTER TABLE users DROP FOREIGN KEY FK_1483A5E98BAC62AF");
        $this->addSql("ALTER TABLE users ADD CONSTRAINT FK_1483A5E98BAC62AF FOREIGN KEY (city_id) REFERENCES Country (id) ON DELETE SET NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3A8BAC62AF");
        $this->addSql("ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A8BAC62AF FOREIGN KEY (city_id) REFERENCES country (id)");
        $this->addSql("ALTER TABLE users DROP FOREIGN KEY FK_1483A5E98BAC62AF");
        $this->addSql("ALTER TABLE users ADD CONSTRAINT FK_1483A5E98BAC62AF FOREIGN KEY (city_id) REFERENCES country (id)");
    }
}
