<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927183519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE worker_informations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE worker_informations (id INT NOT NULL, network_address VARCHAR(15) NOT NULL, network_port INT NOT NULL, worker_state VARCHAR(32) NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, label_name VARCHAR(4) NOT NULL, weight INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE worker_informations_id_seq CASCADE');
        $this->addSql('DROP TABLE worker_informations');
    }
}
