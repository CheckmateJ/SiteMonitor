<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422112935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE site_test_results (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, site_test_id INT NOT NULL, created_at DATETIME NOT NULL, result INT NOT NULL, details LONGTEXT DEFAULT NULL, INDEX IDX_8868AD6FF6BD1646 (site_id), INDEX IDX_8868AD6FE5F9E692 (site_test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE site_test_results ADD CONSTRAINT FK_8868AD6FF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site_test_results ADD CONSTRAINT FK_8868AD6FE5F9E692 FOREIGN KEY (site_test_id) REFERENCES site_test (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE site_test_results');
    }
}
