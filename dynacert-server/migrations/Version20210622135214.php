<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622135214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX name ON category');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE form CHANGE title title VARCHAR(255) NOT NULL, CHANGE config config JSON NOT NULL, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE formMap DROP date, CHANGE map map MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE formPdfImage CHANGE images images MEDIUMTEXT DEFAULT NULL, CHANGE date date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE category CHANGE name name TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('CREATE UNIQUE INDEX name ON category (name)');
        $this->addSql('ALTER TABLE form CHANGE title title TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci` COMMENT \'title of form\', CHANGE config config LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date date DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE formmap ADD date DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE map map LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci` COMMENT \'title of form\'');
        $this->addSql('ALTER TABLE formpdfimage CHANGE images images LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date date DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
