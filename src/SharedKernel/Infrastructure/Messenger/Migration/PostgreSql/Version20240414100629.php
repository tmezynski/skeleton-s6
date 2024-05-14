<?php

declare(strict_types=1);

namespace SharedKernel\Infrastructure\Messenger\Migration\PostgreSql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414100629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates messenger async table';
    }

    public function up(Schema $schema): void //phpcs:ignore
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS queues');

        $this->addSql(
            <<<SQL
            CREATE TABLE IF NOT EXISTS queues.messages (
              id bigserial primary key,
              body text NOT NULL,
              headers text NOT NULL,
              queue_name character varying(190) NOT NULL,
              created_at timestamp NOT NULL,
              available_at timestamp NOT NULL,
              delivered_at timestamp DEFAULT NULL
            )
        SQL
        );

        $this->addSql('CREATE INDEX messages_queue_name_idx ON queues.messages USING btree (queue_name);');
        $this->addSql('CREATE INDEX messages_available_at_idx ON queues.messages USING btree (available_at);');
        $this->addSql('CREATE INDEX messages_delivered_at_idx ON queues.messages USING btree (delivered_at);');

        $this->addSql(
            <<<SQL
            CREATE TABLE IF NOT EXISTS queues.messages_log (
              id bigserial primary key,
              body text NOT NULL,
              headers text NOT NULL,
              queue_name character varying(190) NOT NULL,
              created_at timestamp NOT NULL,
              available_at timestamp NOT NULL,
              delivered_at timestamp DEFAULT NULL
            )
        SQL
        );
    }

    public function down(Schema $schema): void //phpcs:ignore
    {
        $this->addSql('DROP TABLE IF EXISTS queues.messages;');
        $this->addSql('DROP TABLE IF EXISTS queues.messages_log;');
        $this->addSql('DROP SCHEMA IF EXISTS queues;');
    }
}
