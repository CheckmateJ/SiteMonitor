<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505092152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE site_notification_channel');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE site_notification_channel (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, notification_channel_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_406F2C3CF6BD1646 (site_id), INDEX IDX_406F2C3C89870488 (notification_channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE site_notification_channel ADD CONSTRAINT FK_406F2C3C89870488 FOREIGN KEY (notification_channel_id) REFERENCES notification_channel (id)');
        $this->addSql('ALTER TABLE site_notification_channel ADD CONSTRAINT FK_406F2C3CF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }
}
