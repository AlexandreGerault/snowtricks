<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428092929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `activation_tokens` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token LONGTEXT NOT NULL, INDEX IDX_C1DFC359A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ask_new_password_tokens` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token LONGTEXT NOT NULL, INDEX IDX_A6F3AEB9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_categories` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, UNIQUE INDEX UNIQ_B37BD6F55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_illustrations` (id INT AUTO_INCREMENT NOT NULL, trick_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_E4E33E0FB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_videos` (id INT AUTO_INCREMENT NOT NULL, trick_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_72BFE52FB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `tricks` (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, thumbnail_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_E1D902C15E237E06 (name), INDEX IDX_E1D902C112469DE2 (category_id), UNIQUE INDEX UNIQ_E1D902C1FDFF2E92 (thumbnail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `activation_tokens` ADD CONSTRAINT FK_C1DFC359A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE `ask_new_password_tokens` ADD CONSTRAINT FK_A6F3AEB9A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE `trick_illustrations` ADD CONSTRAINT FK_E4E33E0FB281BE2E FOREIGN KEY (trick_id) REFERENCES `tricks` (id)');
        $this->addSql('ALTER TABLE `trick_videos` ADD CONSTRAINT FK_72BFE52FB281BE2E FOREIGN KEY (trick_id) REFERENCES `tricks` (id)');
        $this->addSql('ALTER TABLE `tricks` ADD CONSTRAINT FK_E1D902C112469DE2 FOREIGN KEY (category_id) REFERENCES `trick_categories` (id)');
        $this->addSql('ALTER TABLE `tricks` ADD CONSTRAINT FK_E1D902C1FDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES `trick_illustrations` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `tricks` DROP FOREIGN KEY FK_E1D902C112469DE2');
        $this->addSql('ALTER TABLE `tricks` DROP FOREIGN KEY FK_E1D902C1FDFF2E92');
        $this->addSql('ALTER TABLE `trick_illustrations` DROP FOREIGN KEY FK_E4E33E0FB281BE2E');
        $this->addSql('ALTER TABLE `trick_videos` DROP FOREIGN KEY FK_72BFE52FB281BE2E');
        $this->addSql('ALTER TABLE `activation_tokens` DROP FOREIGN KEY FK_C1DFC359A76ED395');
        $this->addSql('ALTER TABLE `ask_new_password_tokens` DROP FOREIGN KEY FK_A6F3AEB9A76ED395');
        $this->addSql('DROP TABLE `activation_tokens`');
        $this->addSql('DROP TABLE `ask_new_password_tokens`');
        $this->addSql('DROP TABLE `trick_categories`');
        $this->addSql('DROP TABLE `trick_illustrations`');
        $this->addSql('DROP TABLE `trick_videos`');
        $this->addSql('DROP TABLE `tricks`');
        $this->addSql('DROP TABLE `users`');
    }
}
