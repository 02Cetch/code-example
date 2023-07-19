<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719162438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_skill_user DROP FOREIGN KEY FK_6DB58C17A76ED395');
        $this->addSql('ALTER TABLE user_skill_user DROP FOREIGN KEY FK_6DB58C17B5082542');
        $this->addSql('DROP TABLE user_skill_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_skill_user (user_skill_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6DB58C17A76ED395 (user_id), INDEX IDX_6DB58C17B5082542 (user_skill_id), PRIMARY KEY(user_skill_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_skill_user ADD CONSTRAINT FK_6DB58C17A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_skill_user ADD CONSTRAINT FK_6DB58C17B5082542 FOREIGN KEY (user_skill_id) REFERENCES user_skill (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
