<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510033053 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_channel DROP FOREIGN KEY FK_B7E704F0A76ED395');
        $this->addSql('ALTER TABLE notification_channel CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification_channel ADD CONSTRAINT FK_B7E704F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_log DROP FOREIGN KEY FK_ED15DF289870488');
        $this->addSql('ALTER TABLE notification_log DROP FOREIGN KEY FK_ED15DF2E5F9E692');
        $this->addSql('ALTER TABLE notification_log DROP FOREIGN KEY FK_ED15DF2F6BD1646');
        $this->addSql('ALTER TABLE notification_log CHANGE site_id site_id INT DEFAULT NULL, CHANGE notification_channel_id notification_channel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF289870488 FOREIGN KEY (notification_channel_id) REFERENCES notification_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2E5F9E692 FOREIGN KEY (site_test_id) REFERENCES site_test (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_checks DROP FOREIGN KEY FK_EF22AE26F6BD1646');
        $this->addSql('ALTER TABLE site_checks CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site_checks ADD CONSTRAINT FK_EF22AE26F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_test_results DROP FOREIGN KEY FK_8868AD6FE5F9E692');
        $this->addSql('ALTER TABLE site_test_results DROP FOREIGN KEY FK_8868AD6FF6BD1646');
        $this->addSql('ALTER TABLE site_test_results CHANGE site_id site_id INT DEFAULT NULL, CHANGE site_test_id site_test_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site_test_results ADD CONSTRAINT FK_8868AD6FE5F9E692 FOREIGN KEY (site_test_id) REFERENCES site_test (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_test_results ADD CONSTRAINT FK_8868AD6FF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_channel DROP FOREIGN KEY FK_B7E704F0A76ED395');
        $this->addSql('ALTER TABLE notification_channel CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification_channel ADD CONSTRAINT FK_B7E704F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification_log DROP FOREIGN KEY FK_ED15DF2F6BD1646');
        $this->addSql('ALTER TABLE notification_log DROP FOREIGN KEY FK_ED15DF289870488');
        $this->addSql('ALTER TABLE notification_log DROP FOREIGN KEY FK_ED15DF2E5F9E692');
        $this->addSql('ALTER TABLE notification_log CHANGE site_id site_id INT NOT NULL, CHANGE notification_channel_id notification_channel_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF289870488 FOREIGN KEY (notification_channel_id) REFERENCES notification_channel (id)');
        $this->addSql('ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2E5F9E692 FOREIGN KEY (site_test_id) REFERENCES site_test (id)');
        $this->addSql('ALTER TABLE site_checks DROP FOREIGN KEY FK_EF22AE26F6BD1646');
        $this->addSql('ALTER TABLE site_checks CHANGE site_id site_id INT NOT NULL');
        $this->addSql('ALTER TABLE site_checks ADD CONSTRAINT FK_EF22AE26F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site_test_results DROP FOREIGN KEY FK_8868AD6FF6BD1646');
        $this->addSql('ALTER TABLE site_test_results DROP FOREIGN KEY FK_8868AD6FE5F9E692');
        $this->addSql('ALTER TABLE site_test_results CHANGE site_id site_id INT NOT NULL, CHANGE site_test_id site_test_id INT NOT NULL');
        $this->addSql('ALTER TABLE site_test_results ADD CONSTRAINT FK_8868AD6FF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site_test_results ADD CONSTRAINT FK_8868AD6FE5F9E692 FOREIGN KEY (site_test_id) REFERENCES site_test (id)');
    }
}
