<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201007142900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change profil to user table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career DROP FOREIGN KEY FK_B25B6C84275ED078');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE275ED078');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, work VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP INDEX IDX_B25B6C84275ED078 ON career');
        $this->addSql('ALTER TABLE career CHANGE profil_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE career ADD CONSTRAINT FK_B25B6C84A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B25B6C84A76ED395 ON career (user_id)');
        $this->addSql('DROP INDEX IDX_2FB3D0EE275ED078 ON project');
        $this->addSql('ALTER TABLE project CHANGE profil_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career DROP FOREIGN KEY FK_B25B6C84A76ED395');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, work VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_B25B6C84A76ED395 ON career');
        $this->addSql('ALTER TABLE career CHANGE user_id profil_id INT NOT NULL');
        $this->addSql('ALTER TABLE career ADD CONSTRAINT FK_B25B6C84275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('CREATE INDEX IDX_B25B6C84275ED078 ON career (profil_id)');
        $this->addSql('DROP INDEX IDX_2FB3D0EEA76ED395 ON project');
        $this->addSql('ALTER TABLE project CHANGE user_id profil_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE275ED078 ON project (profil_id)');
    }
}
