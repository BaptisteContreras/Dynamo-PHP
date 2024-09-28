<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240928122013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE node RENAME COLUMN state TO membership_state');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE node RENAME COLUMN membership_state TO state');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }
}
