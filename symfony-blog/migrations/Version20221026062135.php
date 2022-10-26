<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026062135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD COLUMN lastName VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD COLUMN email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD COLUMN subject VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD COLUMN message text NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD COLUMN lastName VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD COLUMN email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD COLUMN subject VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact ADD COLUMN message text NOT NULL');
    }
}
