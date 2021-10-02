<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907201956 extends AbstractMigration
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
        $this->addSql('CREATE TABLE `tricks` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, image_url VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E1D902C15E237E06 (name), UNIQUE INDEX UNIQ_E1D902C1AC9C95FD (image_url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `activation_tokens` ADD CONSTRAINT FK_C1DFC359A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
        $this->addSql('ALTER TABLE `ask_new_password_tokens` ADD CONSTRAINT FK_A6F3AEB9A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `activation_tokens` DROP FOREIGN KEY FK_C1DFC359A76ED395');
        $this->addSql('ALTER TABLE `ask_new_password_tokens` DROP FOREIGN KEY FK_A6F3AEB9A76ED395');
        $this->addSql('DROP TABLE `activation_tokens`');
        $this->addSql('DROP TABLE `ask_new_password_tokens`');
        $this->addSql('DROP TABLE `tricks`');
        $this->addSql('DROP TABLE `users`');
    }
}
