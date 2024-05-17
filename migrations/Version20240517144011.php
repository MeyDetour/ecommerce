<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517144011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE issu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE issu (id INT NOT NULL, product_id INT DEFAULT NULL, message TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15A01E1A4584665A ON issu (product_id)');
        $this->addSql('COMMENT ON COLUMN issu.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE issu ADD CONSTRAINT FK_15A01E1A4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE issu_id_seq CASCADE');
        $this->addSql('ALTER TABLE issu DROP CONSTRAINT FK_15A01E1A4584665A');
        $this->addSql('DROP TABLE issu');
    }
}
