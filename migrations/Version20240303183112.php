<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303183112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cheque (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, user_id INT DEFAULT NULL, beneficiaire VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, telephone INT NOT NULL, email VARCHAR(255) NOT NULL, cin INT NOT NULL, nom_prenom VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, decision VARCHAR(255) DEFAULT NULL, actions_c INT DEFAULT NULL, actions_e INT DEFAULT NULL, photo_cin VARCHAR(255) DEFAULT NULL, signature_id VARCHAR(255) DEFAULT NULL, document_id VARCHAR(255) DEFAULT NULL, signer_id VARCHAR(255) DEFAULT NULL, pdf_sans_signature VARCHAR(255) DEFAULT NULL, INDEX IDX_A0BBFDE9F2C56620 (compte_id), INDEX IDX_A0BBFDE9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, confirmation_email VARCHAR(255) NOT NULL, cin INT NOT NULL, date_delivrance_cin DATE NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, proffesion VARCHAR(255) NOT NULL, type_compte VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, statut_marital VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, numero_telephone INT NOT NULL, preference_communic VARCHAR(255) NOT NULL, type_cin VARCHAR(255) NOT NULL, rib BIGINT DEFAULT NULL, statut INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_compte (user_id INT NOT NULL, compte_id INT NOT NULL, INDEX IDX_AAA4495DA76ED395 (user_id), INDEX IDX_AAA4495DF2C56620 (compte_id), PRIMARY KEY(user_id, compte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virement (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nomet_prenom VARCHAR(255) DEFAULT NULL, type_virement VARCHAR(255) NOT NULL, transferez_a VARCHAR(255) NOT NULL, num_beneficiare INT NOT NULL, montant VARCHAR(255) NOT NULL, cin INT NOT NULL, rib INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, decision_v VARCHAR(255) NOT NULL, actions_v INT NOT NULL, actions_em INT DEFAULT NULL, photo_cin_v VARCHAR(255) DEFAULT NULL, INDEX IDX_2D4DCFA6F2C56620 (compte_id), INDEX IDX_2D4DCFA6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE9F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_compte ADD CONSTRAINT FK_AAA4495DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_compte ADD CONSTRAINT FK_AAA4495DF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA6F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE virement ADD CONSTRAINT FK_2D4DCFA6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE9F2C56620');
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE9A76ED395');
        $this->addSql('ALTER TABLE user_compte DROP FOREIGN KEY FK_AAA4495DA76ED395');
        $this->addSql('ALTER TABLE user_compte DROP FOREIGN KEY FK_AAA4495DF2C56620');
        $this->addSql('ALTER TABLE virement DROP FOREIGN KEY FK_2D4DCFA6F2C56620');
        $this->addSql('ALTER TABLE virement DROP FOREIGN KEY FK_2D4DCFA6A76ED395');
        $this->addSql('DROP TABLE cheque');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE user_compte');
        $this->addSql('DROP TABLE virement');
    }
}
