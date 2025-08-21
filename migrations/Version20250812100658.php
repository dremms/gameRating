<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250812100658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_game (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, game_id INT NOT NULL, play_start_date DATE DEFAULT NULL, play_end_date DATE DEFAULT NULL, completed_story TINYINT(1) NOT NULL, completed_full TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, score_percent INT DEFAULT NULL, INDEX IDX_59AA7D45A76ED395 (user_id), INDEX IDX_59AA7D45E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_59AA7D45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_59AA7D45E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64916AAD9B3');
        $this->addSql('DROP INDEX IDX_8D93D64916AAD9B3 ON user');
        $this->addSql('ALTER TABLE user CHANGE rating_scale_id_id rating_scale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A90CF735 FOREIGN KEY (rating_scale_id) REFERENCES rating_scale (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A90CF735 ON user (rating_scale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_game DROP FOREIGN KEY FK_59AA7D45A76ED395');
        $this->addSql('ALTER TABLE user_game DROP FOREIGN KEY FK_59AA7D45E48FD905');
        $this->addSql('DROP TABLE user_game');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A90CF735');
        $this->addSql('DROP INDEX IDX_8D93D649A90CF735 ON user');
        $this->addSql('ALTER TABLE user CHANGE rating_scale_id rating_scale_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64916AAD9B3 FOREIGN KEY (rating_scale_id_id) REFERENCES rating_scale (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64916AAD9B3 ON user (rating_scale_id_id)');
    }
}
