<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223061137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE investissement ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8E64E01166D1F9C ON investissement (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01166D1F9C');
        $this->addSql('DROP INDEX UNIQ_B8E64E01166D1F9C ON investissement');
        $this->addSql('ALTER TABLE investissement DROP project_id');
    }
}
