<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240607172056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE history_event (id UUID NOT NULL, node UUID NOT NULL, type INT NOT NULL, event_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, source_node UUID DEFAULT NULL, received_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN history_event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN history_event.node IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN history_event.event_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN history_event.source_node IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN history_event.received_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE node (id UUID NOT NULL, host VARCHAR(255) NOT NULL, network_port INT NOT NULL, state SMALLINT NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, weight SMALLINT NOT NULL, self_entry BOOLEAN NOT NULL, seed BOOLEAN NOT NULL, label VARCHAR(10) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_857FE845EA750E8 ON node (label)');
        $this->addSql('COMMENT ON COLUMN node.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN node.joined_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN node.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE virtual_node (id UUID NOT NULL, node_id UUID NOT NULL, sub_label VARCHAR(255) NOT NULL, slot INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6CF7F57D460D9FD7 ON virtual_node (node_id)');
        $this->addSql('COMMENT ON COLUMN virtual_node.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN virtual_node.node_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN virtual_node.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE virtual_node ADD CONSTRAINT FK_6CF7F57D460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE virtual_node DROP CONSTRAINT FK_6CF7F57D460D9FD7');
        $this->addSql('DROP TABLE history_event');
        $this->addSql('DROP TABLE node');
        $this->addSql('DROP TABLE virtual_node');
    }
}
