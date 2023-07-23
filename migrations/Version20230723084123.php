<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230723084123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, value JSON DEFAULT NULL, allowed_values JSON DEFAULT NULL, weight INT DEFAULT 500 NOT NULL, UNIQUE INDEX UNIQ_9F74B8985E237E06 (name), INDEX IDX_9F74B898C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898C54C8C93 FOREIGN KEY (type_id) REFERENCES setting_type (id)');
        $this->addSql('DROP INDEX `primary` ON article_tag');
        $this->addSql('ALTER TABLE article_tag ADD PRIMARY KEY (article_id, tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B898C54C8C93');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE setting_type');
        $this->addSql('DROP INDEX `PRIMARY` ON article_tag');
        $this->addSql('ALTER TABLE article_tag ADD PRIMARY KEY (tag_id, article_id)');
    }
}
