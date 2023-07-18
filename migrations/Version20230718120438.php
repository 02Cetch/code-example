<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718120438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD main_image_id INT DEFAULT NULL, ADD cover_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E4873418 FOREIGN KEY (main_image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E5A0E336 FOREIGN KEY (cover_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66E4873418 ON article (main_image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66E5A0E336 ON article (cover_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E4873418');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E5A0E336');
        $this->addSql('DROP INDEX UNIQ_23A0E66E4873418 ON article');
        $this->addSql('DROP INDEX UNIQ_23A0E66E5A0E336 ON article');
        $this->addSql('ALTER TABLE article DROP main_image_id, DROP cover_image_id');
    }
}
