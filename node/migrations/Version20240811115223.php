<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811115223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE preference_list_entry (id UUID NOT NULL, slot INT NOT NULL, owner_id UUID NOT NULL, coordinators_ids JSON NOT NULL, others_nodes_ids JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN preference_list_entry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN preference_list_entry.owner_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE preference_list_entry');
    }
}
