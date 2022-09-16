<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912210033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag ADD colors_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7835C002039 FOREIGN KEY (colors_id) REFERENCES colors (id)');
        $this->addSql('CREATE INDEX IDX_389B7835C002039 ON tag (colors_id)');
        $this->addSql('INSERT INTO colors (name) VALUES (\'red\'), (\'pink\'), (\'purple\'), (\'indigo\'), (\'blue\'),(\'green\'),(\'yellow\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7835C002039');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP INDEX IDX_389B7835C002039 ON tag');
        $this->addSql('ALTER TABLE tag DROP colors_id');
    }
}
