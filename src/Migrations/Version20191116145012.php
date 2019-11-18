<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191116145012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE public.user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE public."user" (id INT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN public."user".roles IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE refresh_token (id VARCHAR(255) NOT NULL, access_token_id VARCHAR(255) NOT NULL, revoked BOOLEAN NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, redirect VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE access_token (id VARCHAR(255) NOT NULL, user_id INT NOT NULL, client_id VARCHAR(255) NOT NULL, scopes JSON NOT NULL, revoked BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN access_token.scopes IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE public.user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP TABLE public."user"');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE task');
    }
}
