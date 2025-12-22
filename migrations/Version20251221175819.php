<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create clip, user and video tables.
 */
final class Version20251221175819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create clip, user and video tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE clip (thumbnail VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, statuses JSON NOT NULL, id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, original_video_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD201467A9EE75F ON clip (original_video_id)');
        $this->addSql('CREATE INDEX IDX_AD201467A76ED395 ON clip (user_id)');
        $this->addSql('CREATE TABLE "user" (email VARCHAR(180) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE video (name VARCHAR(255) DEFAULT NULL, original_name VARCHAR(255) NOT NULL, url TEXT DEFAULT NULL, duration INT DEFAULT NULL, size INT DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, audio_files JSON DEFAULT NULL, id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE clip ADD CONSTRAINT FK_AD201467A9EE75F FOREIGN KEY (original_video_id) REFERENCES video (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE clip ADD CONSTRAINT FK_AD201467A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE clip DROP CONSTRAINT FK_AD201467A9EE75F');
        $this->addSql('ALTER TABLE clip DROP CONSTRAINT FK_AD201467A76ED395');
        $this->addSql('DROP TABLE clip');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE video');
    }
}
