<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201101214304 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, mail_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_FEC530A9C8776F01 (mail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail (id INT AUTO_INCREMENT NOT NULL, recipient_id INT NOT NULL, subject VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, sent_at DATETIME, UNIQUE INDEX UNIQ_5126AC48FBCE3E7A (subject), UNIQUE INDEX UNIQ_5126AC48E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipient (id INT AUTO_INCREMENT NOT NULL, mail_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6804FB49C8776F01 (mail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9C8776F01 FOREIGN KEY (mail_id) REFERENCES mail (id)');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC48E92F8F78 FOREIGN KEY (recipient_id) REFERENCES recipient (id)');
        $this->addSql('ALTER TABLE recipient ADD CONSTRAINT FK_6804FB49C8776F01 FOREIGN KEY (mail_id) REFERENCES mail (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9C8776F01');
        $this->addSql('ALTER TABLE recipient DROP FOREIGN KEY FK_6804FB49C8776F01');
        $this->addSql('ALTER TABLE mail DROP FOREIGN KEY FK_5126AC48E92F8F78');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE mail');
        $this->addSql('DROP TABLE recipient');
    }
}
