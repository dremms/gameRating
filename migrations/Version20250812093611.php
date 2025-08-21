<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250812093611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating_scale (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, max_scale INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD rating_scale_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64916AAD9B3 FOREIGN KEY (rating_scale_id_id) REFERENCES rating_scale (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64916AAD9B3 ON user (rating_scale_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64916AAD9B3');
        $this->addSql('DROP TABLE rating_scale');
        $this->addSql('DROP INDEX IDX_8D93D64916AAD9B3 ON user');
        $this->addSql('ALTER TABLE user DROP rating_scale_id_id');
    }
}
