<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505061317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_log (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, notification_channel_id INT NOT NULL, site_test_id INT DEFAULT NULL, details LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_ED15DF2F6BD1646 (site_id), INDEX IDX_ED15DF289870488 (notification_channel_id), INDEX IDX_ED15DF2E5F9E692 (site_test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF289870488 FOREIGN KEY (notification_channel_id) REFERENCES notification_channel (id)');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2E5F9E692 FOREIGN KEY (site_test_id) REFERENCES site_test (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notification_log');
    }
}
