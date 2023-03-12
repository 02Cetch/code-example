<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312184144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BC82D268');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E5225F30');
        $this->addSql('DROP INDEX UNIQ_23A0E66E5225F30 ON article');
        $this->addSql('DROP INDEX UNIQ_23A0E66BC82D268 ON article');
        $this->addSql('ALTER TABLE article ADD main_image_id INT DEFAULT NULL, ADD cover_image_id INT DEFAULT NULL, DROP main_image_id_id, DROP cover_image_id_id');
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
        $this->addSql('ALTER TABLE article ADD main_image_id_id INT DEFAULT NULL, ADD cover_image_id_id INT DEFAULT NULL, DROP main_image_id, DROP cover_image_id');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BC82D268 FOREIGN KEY (main_image_id_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E5225F30 FOREIGN KEY (cover_image_id_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66E5225F30 ON article (cover_image_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66BC82D268 ON article (main_image_id_id)');
    }
}
