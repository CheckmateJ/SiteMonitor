<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505092604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE site_notification_channel (site_id INT NOT NULL, notification_channel_id INT NOT NULL, INDEX IDX_406F2C3CF6BD1646 (site_id), INDEX IDX_406F2C3C89870488 (notification_channel_id), PRIMARY KEY(site_id, notification_channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE site_notification_channel ADD CONSTRAINT FK_406F2C3CF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_notification_channel ADD CONSTRAINT FK_406F2C3C89870488 FOREIGN KEY (notification_channel_id) REFERENCES notification_channel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE site_notification_channel');
    }
}
