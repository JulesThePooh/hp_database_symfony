<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627184750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house DROP primary_color, DROP secondary_color');
        $this->addSql('ALTER TABLE student ADD name VARCHAR(255) NOT NULL, ADD is_alive TINYINT(1) NOT NULL, DROP first_name, DROP last_name, CHANGE year_enrolled year_of_birth INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house ADD primary_color VARCHAR(255) NOT NULL, ADD secondary_color VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD last_name VARCHAR(255) NOT NULL, DROP is_alive, CHANGE name first_name VARCHAR(255) NOT NULL, CHANGE year_of_birth year_enrolled INT NOT NULL');
    }
}
