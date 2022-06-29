<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302220957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP picture');
        $this->addSql('ALTER TABLE images ADD main_article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A4A6CF2FF FOREIGN KEY (main_article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A4A6CF2FF ON images (main_article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A4A6CF2FF');
        $this->addSql('DROP INDEX IDX_E01FBE6A4A6CF2FF ON images');
        $this->addSql('ALTER TABLE images DROP main_article_id');
    }
}
