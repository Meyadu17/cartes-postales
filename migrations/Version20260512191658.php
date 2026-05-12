<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512191658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte_postale ADD departement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carte_postale ADD CONSTRAINT FK_3EC4EB3FCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_3EC4EB3FCCF9E01E ON carte_postale (departement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte_postale DROP FOREIGN KEY FK_3EC4EB3FCCF9E01E');
        $this->addSql('DROP INDEX IDX_3EC4EB3FCCF9E01E ON carte_postale');
        $this->addSql('ALTER TABLE carte_postale DROP departement_id');
    }
}
