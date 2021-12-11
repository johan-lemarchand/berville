<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211211230309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64930335DEA');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64960D8737');
        $this->addSql('DROP TABLE avatar_file');
        $this->addSql('DROP INDEX UNIQ_8D93D64960D8737 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64930335DEA ON user');
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) DEFAULT NULL, DROP id_avatar_id, DROP filename_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar_file (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_1E3B5DCB79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE avatar_file ADD CONSTRAINT FK_1E3B5DCB79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `user` ADD id_avatar_id INT DEFAULT NULL, ADD filename_id INT DEFAULT NULL, DROP avatar');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64930335DEA FOREIGN KEY (filename_id) REFERENCES avatar_file (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64960D8737 FOREIGN KEY (id_avatar_id) REFERENCES avatar_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64960D8737 ON `user` (id_avatar_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64930335DEA ON `user` (filename_id)');
    }
}
