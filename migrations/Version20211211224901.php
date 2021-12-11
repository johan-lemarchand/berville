<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211211224901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD filename_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64930335DEA FOREIGN KEY (filename_id) REFERENCES avatar_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64930335DEA ON user (filename_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64930335DEA');
        $this->addSql('DROP INDEX UNIQ_8D93D64930335DEA ON `user`');
        $this->addSql('ALTER TABLE `user` DROP filename_id');
    }
}
