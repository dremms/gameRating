<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250818145339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_game ADD platform_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_game ADD CONSTRAINT FK_59AA7D45FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('CREATE INDEX IDX_59AA7D45FFE6496F ON user_game (platform_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_game DROP FOREIGN KEY FK_59AA7D45FFE6496F');
        $this->addSql('DROP INDEX IDX_59AA7D45FFE6496F ON user_game');
        $this->addSql('ALTER TABLE user_game DROP platform_id');
    }
}
