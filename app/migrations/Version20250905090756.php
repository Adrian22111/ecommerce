<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905090756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image CHANGE size size INT DEFAULT NULL, CHANGE add_date add_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_update last_update DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image CHANGE size size VARCHAR(255) DEFAULT NULL, CHANGE add_date add_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE last_update last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
