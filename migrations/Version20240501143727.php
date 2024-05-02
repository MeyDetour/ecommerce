<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501143727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE action (id INT NOT NULL, author_id INT NOT NULL, command_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, type TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47CC8C92F675F31B ON action (author_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47CC8C9233E1689A ON action (command_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47CC8C92F8697D13 ON action (comment_id)');
        $this->addSql('COMMENT ON COLUMN action.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9233E1689A FOREIGN KEY (command_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE action_id_seq CASCADE');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C92F675F31B');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C9233E1689A');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C92F8697D13');
        $this->addSql('DROP TABLE action');
    }
}
