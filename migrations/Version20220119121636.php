<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119121636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update User';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE customer_type_id_seq');
        $this->addSql('ALTER TABLE customers drop column customer_type');
        $this->addSql('DROP TABLE customer_type');
        $this->addSql('ALTER TABLE customers add column email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE customers add column nickname VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE customers add column roles jsonb');
        $this->addSql('ALTER TABLE customers add column password VARCHAR');
        $this->addSql('ALTER TABLE customers rename column status to is_verified');
        $this->addSql('ALTER TABLE customers alter column first_name drop not null');
        $this->addSql('ALTER TABLE customers alter column last_name drop not null');
        $this->addSql('ALTER TABLE quizzes drop column customer_id');
        $this->addSql('ALTER TABLE quizzes add column  email VARCHAR(180)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE customer_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer_type (id INT NOT NULL, name VARCHAR(20) NOT NULL, show BOOLEAN DEFAULT \'true\' NOT NULL, add BOOLEAN DEFAULT \'true\' NOT NULL, edit BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE customers ADD COLUMN customer_type INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_62534E21D84FF35E ON customers (customer_type)');
        $this->addSql('ALTER TABLE customers drop column email');
        $this->addSql('ALTER TABLE customers drop column roles');
        $this->addSql('ALTER TABLE customers drop column password');
        $this->addSql('ALTER TABLE customers drop column nickname');
        $this->addSql('ALTER TABLE customers rename column is_verified to status');
        $this->addSql('ALTER TABLE customers alter column first_name set not null;');
        $this->addSql('ALTER TABLE customers alter column last_name set not null;');
        $this->addSql('ALTER TABLE quizzes add column customer_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_94DC9FB59395C3F3 ON quizzes (customer_id)');
    }
}
