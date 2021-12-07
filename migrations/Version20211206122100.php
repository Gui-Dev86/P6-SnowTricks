<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206122100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, path_image VARCHAR(255) DEFAULT NULL, INDEX IDX_C53D045F3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, link_video VARCHAR(255) DEFAULT NULL, INDEX IDX_7CC7DA2C3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('DROP TABLE upload');
        $this->addSql('ALTER TABLE tricks ADD main_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, main_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, path_upload VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type_upload TINYINT(1) NOT NULL, INDEX IDX_17BDE61F3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE upload ADD CONSTRAINT FK_17BDE61F3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE video');
        $this->addSql('ALTER TABLE tricks DROP main_image');
    }
}
