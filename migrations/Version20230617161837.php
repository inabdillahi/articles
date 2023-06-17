<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617161837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_CB988C6FFB88E14F ON annonces (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD status TINYINT(1) NOT NULL, ADD username VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FFB88E14F');
        $this->addSql('DROP INDEX IDX_CB988C6FFB88E14F ON annonces');
        $this->addSql('ALTER TABLE annonces DROP utilisateur_id');
        $this->addSql('ALTER TABLE utilisateur DROP created_at, DROP status, DROP username');
    }
}
