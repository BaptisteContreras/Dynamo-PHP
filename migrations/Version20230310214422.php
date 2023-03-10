<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310214422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE label_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE worker_node_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE label (id INT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, sub_division VARCHAR(255) DEFAULT NULL, position DOUBLE PRECISION NOT NULL, cover_zone_length DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA750E8462CE4F5 ON label (position)');
        $this->addSql('CREATE INDEX IDX_EA750E87E3C61F9 ON label (owner_id)');
        $this->addSql('CREATE TABLE worker_node (id INT NOT NULL, network_address VARCHAR(15) NOT NULL, network_port INT NOT NULL, worker_state VARCHAR(32) NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, label_name VARCHAR(4) NOT NULL, weight INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4EBA5E14921BEBCA ON worker_node (label_name)');
        $this->addSql('COMMENT ON COLUMN worker_node.joined_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE label ADD CONSTRAINT FK_EA750E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES worker_node (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE label_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE worker_node_id_seq CASCADE');
        $this->addSql('ALTER TABLE label DROP CONSTRAINT FK_EA750E87E3C61F9');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE worker_node');
    }
}
