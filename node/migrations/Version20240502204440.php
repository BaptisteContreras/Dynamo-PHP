<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502204440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE node (id UUID NOT NULL, host VARCHAR(255) NOT NULL, network_port INT NOT NULL, state SMALLINT NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, weight SMALLINT NOT NULL, self_entry BOOLEAN NOT NULL, seed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN node.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN node.joined_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE node');
    }
}
