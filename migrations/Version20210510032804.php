<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510032804 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE site_test DROP FOREIGN KEY FK_E3E5C3F5F6BD1646');
        $this->addSql('ALTER TABLE site_test CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site_test ADD CONSTRAINT FK_E3E5C3F5F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE site_test DROP FOREIGN KEY FK_E3E5C3F5F6BD1646');
        $this->addSql('ALTER TABLE site_test CHANGE site_id site_id INT NOT NULL');
        $this->addSql('ALTER TABLE site_test ADD CONSTRAINT FK_E3E5C3F5F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }
}
