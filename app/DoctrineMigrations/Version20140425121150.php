<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140425121150 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE user_cars (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, mark_id INT DEFAULT NULL, model_id INT DEFAULT NULL, modification_id INT DEFAULT NULL, transmission VARCHAR(255) DEFAULT NULL, vin VARCHAR(255) DEFAULT NULL, drive2 VARCHAR(255) DEFAULT NULL, INDEX IDX_EF4651DDA76ED395 (user_id), INDEX IDX_EF4651DD4290F12B (mark_id), INDEX IDX_EF4651DD7975B7E7 (model_id), INDEX IDX_EF4651DD4A605127 (modification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE user_cars ADD CONSTRAINT FK_EF4651DDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)");
        $this->addSql("ALTER TABLE user_cars ADD CONSTRAINT FK_EF4651DD4290F12B FOREIGN KEY (mark_id) REFERENCES catalog_marks (id)");
        $this->addSql("ALTER TABLE user_cars ADD CONSTRAINT FK_EF4651DD7975B7E7 FOREIGN KEY (model_id) REFERENCES catalog_models (id)");
        $this->addSql("ALTER TABLE user_cars ADD CONSTRAINT FK_EF4651DD4A605127 FOREIGN KEY (modification_id) REFERENCES catalog_modifications (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE user_cars");
    }
}
