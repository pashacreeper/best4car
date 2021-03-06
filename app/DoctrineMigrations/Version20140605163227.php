<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140605163227 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE feed_items (id INT AUTO_INCREMENT NOT NULL, deal_id INT DEFAULT NULL, company_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updatedt_at DATETIME NOT NULL, INDEX IDX_1491BAF1F60E2305 (deal_id), INDEX IDX_1491BAF1979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE feed_items ADD CONSTRAINT FK_1491BAF1F60E2305 FOREIGN KEY (deal_id) REFERENCES deals (id)");
        $this->addSql("ALTER TABLE feed_items ADD CONSTRAINT FK_1491BAF1979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE feed_items");
    }
}
