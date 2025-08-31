<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831183742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE item ALTER version TYPE TEXT');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E8A90ABA9BF1CD3C3 ON item (key, version) WHERE active IS true');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_1F1B251E8A90ABA9BF1CD3C3');
        $this->addSql('ALTER TABLE item DROP active');
        $this->addSql('ALTER TABLE item ALTER version TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER coordinators_ids TYPE JSON');
        $this->addSql('ALTER TABLE preference_list_entry ALTER others_nodes_ids TYPE JSON');
    }
}
