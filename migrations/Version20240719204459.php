<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719204459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE age_name (id INT AUTO_INCREMENT NOT NULL, age_range VARCHAR(255) NOT NULL, male_count INT NOT NULL, female_count INT NOT NULL, other_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(255) NOT NULL, male_count INT NOT NULL, female_count INT NOT NULL, other_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operative_system (id INT AUTO_INCREMENT NOT NULL, os VARCHAR(255) NOT NULL, total_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE summary (id INT AUTO_INCREMENT NOT NULL, total_records INT NOT NULL, male_count INT NOT NULL, female_count INT NOT NULL, other_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE age_name');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE operative_system');
        $this->addSql('DROP TABLE summary');
    }
}
