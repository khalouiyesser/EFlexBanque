<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306202758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, nom_aut_art VARCHAR(255) NOT NULL, adr_aut_art VARCHAR(255) NOT NULL, date_pub_art DATETIME NOT NULL, duree_art INT NOT NULL, categorie_art VARCHAR(255) NOT NULL, titre_art VARCHAR(255) NOT NULL, contenu_art VARCHAR(255) NOT NULL, piecejointe_art VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, investissement_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, nomuser VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, INDEX IDX_67F068BC40108A79 (investissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, investissement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, organisateur VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_B26681E40108A79 (investissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE investissement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, montant INT NOT NULL, date_investissement DATE NOT NULL, description LONGTEXT NOT NULL, type_investissement VARCHAR(255) NOT NULL, duree INT NOT NULL, taux_rendement INT NOT NULL, statut VARCHAR(50) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_B8E64E01A76ED395 (user_id), UNIQUE INDEX UNIQ_B8E64E01166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nomprojet VARCHAR(100) NOT NULL, img VARCHAR(100) NOT NULL, categorie VARCHAR(100) NOT NULL, descriptionprojet VARCHAR(100) NOT NULL, budgetprojet DOUBLE PRECISION NOT NULL, datecreation DATETIME NOT NULL, dureeprojet INT NOT NULL, statutprojet INT NOT NULL, INDEX IDX_2FB3D0EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC40108A79 FOREIGN KEY (investissement_id) REFERENCES investissement (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E40108A79 FOREIGN KEY (investissement_id) REFERENCES investissement (id)');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE demandestage CHANGE score score DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC40108A79');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E40108A79');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01A76ED395');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01166D1F9C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE investissement');
        $this->addSql('DROP TABLE project');
        $this->addSql('ALTER TABLE demandestage CHANGE score score DOUBLE PRECISION NOT NULL');
    }
}
