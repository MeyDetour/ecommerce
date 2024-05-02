<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428142256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f09a80ef684');
        $this->addSql('DROP INDEX idx_52ea1f09a80ef684');
        $this->addSql('ALTER TABLE order_item DROP product_variant_id');
        $this->addSql('ALTER TABLE product_variant ADD order_item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41DE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_209AA41DE415FB15 ON product_variant (order_item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_variant DROP CONSTRAINT FK_209AA41DE415FB15');
        $this->addSql('DROP INDEX IDX_209AA41DE415FB15');
        $this->addSql('ALTER TABLE product_variant DROP order_item_id');
        $this->addSql('ALTER TABLE order_item ADD product_variant_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f09a80ef684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_52ea1f09a80ef684 ON order_item (product_variant_id)');
    }
}
