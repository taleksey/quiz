<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131120340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE statistic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE statistic (id INT NOT NULL, customer_id INT DEFAULT NULL, quiz_id INT DEFAULT NULL, total_correct_answers INT NOT NULL, total_questions INT NOT NULL, spend_seconds_quiz INT NOT NULL, raw_answers JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_649B469C9395C3F3 ON statistic (customer_id)');
        $this->addSql('CREATE INDEX IDX_649B469C853CD175 ON statistic (quiz_id)');
        $this->addSql('ALTER TABLE statistic ADD CONSTRAINT FK_649B469C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE statistic ADD CONSTRAINT FK_649B469C853CD175 FOREIGN KEY (quiz_id) REFERENCES quizzes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62534E21E7927C74 ON customers (email)');
        $this->addSql('ALTER TABLE quizzes ALTER email SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE statistic_id_seq CASCADE');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP INDEX UNIQ_62534E21E7927C74');
        $this->addSql('ALTER TABLE quizzes ALTER email DROP NOT NULL');
    }
}
