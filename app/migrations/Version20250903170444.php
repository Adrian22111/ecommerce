<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903170444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F03E00EE68D');
        $this->addSql('DROP INDEX IDX_64617F03E00EE68D ON product_image');
        $this->addSql('ALTER TABLE product_image ADD add_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE id_product_id product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F03DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_64617F03DE18E50B ON product_image (product_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F03DE18E50B');
        $this->addSql('DROP INDEX IDX_64617F03DE18E50B ON product_image');
        $this->addSql('ALTER TABLE product_image DROP add_date, DROP last_update, CHANGE product_id_id id_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F03E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_64617F03E00EE68D ON product_image (id_product_id)');
    }
}
