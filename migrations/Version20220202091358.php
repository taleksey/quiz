<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202091358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz_session (id INT NOT NULL, customer_id INT DEFAULT NULL, quiz_id INT DEFAULT NULL, session JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C21E78749395C3F3 ON quiz_session (customer_id)');
        $this->addSql('CREATE INDEX IDX_C21E89850406D4F4 ON quiz_session (quiz_id)');
        $this->addSql('ALTER TABLE quiz_session ADD CONSTRAINT FK_C21E78749395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_session ADD CONSTRAINT FK_C21E89850406D4F4 FOREIGN KEY (quiz_id) REFERENCES quizzes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE quiz_session_id_seq CASCADE');
        $this->addSql('DROP TABLE quiz_session');
    }
}
