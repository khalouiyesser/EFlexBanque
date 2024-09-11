<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327000306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE investissement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, montant INT NOT NULL, date_investissement DATE NOT NULL, description LONGTEXT NOT NULL, type_investissement VARCHAR(255) NOT NULL, duree INT NOT NULL, taux_rendement INT NOT NULL, statut VARCHAR(50) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_B8E64E01A76ED395 (user_id), UNIQUE INDEX UNIQ_B8E64E01166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE user ADD photo VARCHAR(255) DEFAULT NULL, DROP departement, DROP date_eambauche');
        $this->addSql('ALTER TABLE virement ADD phone_number VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01A76ED395');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01166D1F9C');
        $this->addSql('DROP TABLE investissement');
        $this->addSql('ALTER TABLE `user` ADD date_eambauche VARCHAR(255) DEFAULT NULL, CHANGE photo departement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE virement DROP phone_number');
    }
}
