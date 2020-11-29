<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129100158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Move the images limitation form project to user';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP images_limit');
        $this->addSql('ALTER TABLE user ADD images_limit INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD images_limit INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP images_limit');
    }
}
