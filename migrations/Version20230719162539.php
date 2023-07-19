<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719162539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_user_skill (user_id INT NOT NULL, user_skill_id INT NOT NULL, INDEX IDX_1B98D9C3A76ED395 (user_id), INDEX IDX_1B98D9C3B5082542 (user_skill_id), PRIMARY KEY(user_id, user_skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_user_skill ADD CONSTRAINT FK_1B98D9C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_skill ADD CONSTRAINT FK_1B98D9C3B5082542 FOREIGN KEY (user_skill_id) REFERENCES user_skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_user_skill DROP FOREIGN KEY FK_1B98D9C3A76ED395');
        $this->addSql('ALTER TABLE user_user_skill DROP FOREIGN KEY FK_1B98D9C3B5082542');
        $this->addSql('DROP TABLE user_user_skill');
    }
}
