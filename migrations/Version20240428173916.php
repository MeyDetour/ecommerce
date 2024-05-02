<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428173916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD product_variant_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526C4584665A ON comment (product_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9474526CA80EF684 ON comment (product_variant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4584665A');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA80EF684');
        $this->addSql('DROP INDEX IDX_9474526C4584665A');
        $this->addSql('DROP INDEX UNIQ_9474526CA80EF684');
        $this->addSql('ALTER TABLE comment DROP product_id');
        $this->addSql('ALTER TABLE comment DROP product_variant_id');
    }
}
