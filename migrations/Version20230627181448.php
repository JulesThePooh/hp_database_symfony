<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627181448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE house (id INT AUTO_INCREMENT NOT NULL, house_name VARCHAR(255) NOT NULL, founder_first_name VARCHAR(255) NOT NULL, founder_last_name VARCHAR(255) NOT NULL, primary_color VARCHAR(255) NOT NULL, secondary_color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE house_point (id INT AUTO_INCREMENT NOT NULL, house_id INT NOT NULL, year INT NOT NULL, total_point INT NOT NULL, INDEX IDX_6C4654DC6BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, house_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, year_enrolled INT NOT NULL, INDEX IDX_B723AF336BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_type_of_class (student_id INT NOT NULL, type_of_class_id INT NOT NULL, INDEX IDX_CA5D9F63CB944F1A (student_id), INDEX IDX_CA5D9F631D822A6A (type_of_class_id), PRIMARY KEY(student_id, type_of_class_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, subject_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_class (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, subject_id INT NOT NULL, year_taught INT NOT NULL, INDEX IDX_9FC167FD41807E1D (teacher_id), INDEX IDX_9FC167FD23EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE house_point ADD CONSTRAINT FK_6C4654DC6BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF336BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE student_type_of_class ADD CONSTRAINT FK_CA5D9F63CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_type_of_class ADD CONSTRAINT FK_CA5D9F631D822A6A FOREIGN KEY (type_of_class_id) REFERENCES type_of_class (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_of_class ADD CONSTRAINT FK_9FC167FD41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE type_of_class ADD CONSTRAINT FK_9FC167FD23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house_point DROP FOREIGN KEY FK_6C4654DC6BB74515');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF336BB74515');
        $this->addSql('ALTER TABLE student_type_of_class DROP FOREIGN KEY FK_CA5D9F63CB944F1A');
        $this->addSql('ALTER TABLE student_type_of_class DROP FOREIGN KEY FK_CA5D9F631D822A6A');
        $this->addSql('ALTER TABLE type_of_class DROP FOREIGN KEY FK_9FC167FD41807E1D');
        $this->addSql('ALTER TABLE type_of_class DROP FOREIGN KEY FK_9FC167FD23EDC87');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE house_point');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_type_of_class');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE type_of_class');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
