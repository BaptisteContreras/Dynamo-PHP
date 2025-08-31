<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831124022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_object (id UUID NOT NULL, key TEXT NOT NULL, ring_key INT NOT NULL, data TEXT NOT NULL, version JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, owner UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN data_object.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN data_object.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN data_object.owner IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE data_object');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }
}
