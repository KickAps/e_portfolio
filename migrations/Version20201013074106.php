<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201013074106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update Career and Project tables';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE project ADD techno VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE career DROP description');
        $this->addSql('ALTER TABLE project DROP techno');
    }
}
