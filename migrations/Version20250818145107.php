<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250818145107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform_game DROP FOREIGN KEY FK_A72356A0E48FD905');
        $this->addSql('ALTER TABLE platform_game DROP FOREIGN KEY FK_A72356A0FFE6496F');
        $this->addSql('DROP TABLE platform_game');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE platform_game (platform_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_A72356A0FFE6496F (platform_id), INDEX IDX_A72356A0E48FD905 (game_id), PRIMARY KEY(platform_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE platform_game ADD CONSTRAINT FK_A72356A0E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE platform_game ADD CONSTRAINT FK_A72356A0FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE');
    }
}
