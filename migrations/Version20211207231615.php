<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207231615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar_file (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1E3B5DCB79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatar_file ADD CONSTRAINT FK_1E3B5DCB79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD id_avatar_id INT DEFAULT NULL, DROP avatar');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960D8737 FOREIGN KEY (id_avatar_id) REFERENCES avatar_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64960D8737 ON user (id_avatar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64960D8737');
        $this->addSql('DROP TABLE avatar_file');
        $this->addSql('DROP INDEX UNIQ_8D93D64960D8737 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD avatar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP id_avatar_id');
    }
}
