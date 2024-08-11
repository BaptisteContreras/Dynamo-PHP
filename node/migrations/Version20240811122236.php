<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811122236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0B018FFAC0E2067 ON preference_list_entry (slot)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_C0B018FFAC0E2067');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }
}
