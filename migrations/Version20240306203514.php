<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306203514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, id_client INT NOT NULL, montant DOUBLE PRECISION NOT NULL, statusclient VARCHAR(255) NOT NULL, mensualite DOUBLE PRECISION NOT NULL, datedebut DATE NOT NULL, duree INT NOT NULL, taux DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, fraisretard DOUBLE PRECISION NOT NULL, fichesalire VARCHAR(255) DEFAULT NULL, INDEX IDX_1CC16EFEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE propretyseach (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, credit_id INT DEFAULT NULL, idclient INT NOT NULL, heure TIME NOT NULL, daterdv DATE NOT NULL, methode VARCHAR(255) NOT NULL, employename VARCHAR(255) NOT NULL, INDEX IDX_10C31F86CE062FF9 (credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F86CE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEA76ED395');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F86CE062FF9');
        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE propretyseach');
        $this->addSql('DROP TABLE rdv');
    }
}
