<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220106102623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Database';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE customer_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE customers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quizzes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answers (id INT NOT NULL, question_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, correct BOOLEAN DEFAULT \'false\' NOT NULL, queue INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50D0C6061E27F6BF ON answers (question_id)');
        $this->addSql('CREATE TABLE customer_type (id INT NOT NULL, name VARCHAR(20) NOT NULL, show BOOLEAN DEFAULT \'true\' NOT NULL, add BOOLEAN DEFAULT \'true\' NOT NULL, edit BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE customers (id INT NOT NULL, customer_type INT DEFAULT NULL, first_name VARCHAR(150) NOT NULL, last_name VARCHAR(150) NOT NULL, status BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62534E21D84FF35E ON customers (customer_type)');
        $this->addSql('CREATE TABLE questions (id INT NOT NULL, quiz_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, queue INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8ADC54D5853CD175 ON questions (quiz_id)');
        $this->addSql('CREATE TABLE quizzes (id INT NOT NULL, customer_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, active BOOLEAN DEFAULT \'false\' NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, queue INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94DC9FB59395C3F3 ON quizzes (customer_id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21D84FF35E FOREIGN KEY (customer_type) REFERENCES customer_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5853CD175 FOREIGN KEY (quiz_id) REFERENCES quizzes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quizzes ADD CONSTRAINT FK_94DC9FB59395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customers DROP CONSTRAINT FK_62534E21D84FF35E');
        $this->addSql('ALTER TABLE quizzes DROP CONSTRAINT FK_94DC9FB59395C3F3');
        $this->addSql('ALTER TABLE answers DROP CONSTRAINT FK_50D0C6061E27F6BF');
        $this->addSql('ALTER TABLE questions DROP CONSTRAINT FK_8ADC54D5853CD175');
        $this->addSql('DROP SEQUENCE answers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE customer_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE customers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quizzes_id_seq CASCADE');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE customer_type');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE quizzes');
    }
}
