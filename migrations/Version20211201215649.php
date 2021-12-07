<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201215649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name_cat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, tricks_id INT NOT NULL, content_com VARCHAR(5000) NOT NULL, date_create_com DATETIME NOT NULL, is_active_com TINYINT(1) NOT NULL, INDEX IDX_5F9E962A67B3B43D (users_id), INDEX IDX_5F9E962A3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, categories_id INT NOT NULL, title_trick VARCHAR(255) NOT NULL, content_trick VARCHAR(5000) NOT NULL, date_create_trick DATETIME NOT NULL, date_update_trick DATETIME DEFAULT NULL, is_active_trick TINYINT(1) NOT NULL, INDEX IDX_E1D902C167B3B43D (users_id), INDEX IDX_E1D902C1A21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, main_image VARCHAR(255) DEFAULT NULL, path_upload VARCHAR(255) DEFAULT NULL, type_upload TINYINT(1) NOT NULL, INDEX IDX_17BDE61F3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, login_user VARCHAR(25) NOT NULL, email_user VARCHAR(255) NOT NULL, password_user VARCHAR(50) NOT NULL, avatar_user VARCHAR(255) DEFAULT NULL, role_user TINYINT(1) NOT NULL, date_create_user DATETIME NOT NULL, date_update_user DATETIME DEFAULT NULL, is_active_user TINYINT(1) NOT NULL, token_new_pass_user VARCHAR(255) DEFAULT NULL, date_new_pass_user DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C1A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE upload ADD CONSTRAINT FK_17BDE61F3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C1A21214B7');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A3B153154');
        $this->addSql('ALTER TABLE upload DROP FOREIGN KEY FK_17BDE61F3B153154');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A67B3B43D');
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C167B3B43D');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE tricks');
        $this->addSql('DROP TABLE upload');
        $this->addSql('DROP TABLE users');
    }
}
