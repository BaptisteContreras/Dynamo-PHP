<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523204425 extends AbstractMigration
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE history_event');
    }
}
