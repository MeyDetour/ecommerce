<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428131012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item ADD product_variant_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_52EA1F09A80EF684 ON order_item (product_variant_id)');
        $this->addSql('ALTER TABLE product_variant ADD command_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_variant ADD is_sell BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D33E1689A FOREIGN KEY (command_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_209AA41D33E1689A ON product_variant (command_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09A80EF684');
        $this->addSql('DROP INDEX IDX_52EA1F09A80EF684');
        $this->addSql('ALTER TABLE order_item DROP product_variant_id');
        $this->addSql('ALTER TABLE product_variant DROP CONSTRAINT FK_209AA41D33E1689A');
        $this->addSql('DROP INDEX IDX_209AA41D33E1689A');
        $this->addSql('ALTER TABLE product_variant DROP command_id');
        $this->addSql('ALTER TABLE product_variant DROP is_sell');
    }
}
