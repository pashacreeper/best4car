<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130723184602 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE deals_auto DROP FOREIGN KEY FK_D2E53B0F60E2305");
        $this->addSql("ALTER TABLE deals_auto DROP FOREIGN KEY FK_D2E53B01D55B925");
        $this->addSql("ALTER TABLE deals_auto DROP PRIMARY KEY");
        $this->addSql("ALTER TABLE deals_auto ADD CONSTRAINT FK_D2E53B0F60E2305 FOREIGN KEY (deal_id) REFERENCES deals (id)");
        $this->addSql("ALTER TABLE deals_auto ADD CONSTRAINT FK_D2E53B01D55B925 FOREIGN KEY (auto_id) REFERENCES auto_catalog (id)");
        $this->addSql("ALTER TABLE deals_auto ADD PRIMARY KEY (deal_id, auto_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE deals_auto DROP FOREIGN KEY FK_D2E53B0F60E2305");
        $this->addSql("ALTER TABLE deals_auto DROP FOREIGN KEY FK_D2E53B01D55B925");
        $this->addSql("ALTER TABLE deals_auto DROP PRIMARY KEY");
        $this->addSql("ALTER TABLE deals_auto ADD CONSTRAINT FK_D2E53B0F60E2305 FOREIGN KEY (deal_id) REFERENCES auto_catalog (id)");
        $this->addSql("ALTER TABLE deals_auto ADD CONSTRAINT FK_D2E53B01D55B925 FOREIGN KEY (auto_id) REFERENCES deals (id)");
        $this->addSql("ALTER TABLE deals_auto ADD PRIMARY KEY (auto_id, deal_id)");
    }
}
