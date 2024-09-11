<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304234537 extends AbstractMigration
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
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_debut DATE NOT NULL, dure VARCHAR(255) NOT NULL, datefin DATE NOT NULL, UNIQUE INDEX UNIQ_60349993A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demandestage (id INT AUTO_INCREMENT NOT NULL, offre_stage_id INT DEFAULT NULL, nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) NOT NULL, email VARCHAR(40) NOT NULL, numerotelephone INT NOT NULL, lettremotivation LONGTEXT DEFAULT NULL, cv VARCHAR(50) DEFAULT NULL, domaine VARCHAR(50) DEFAULT NULL, etat VARCHAR(10) NOT NULL, date DATE NOT NULL, score DOUBLE PRECISION NOT NULL, INDEX IDX_F8FC91A7195A2A28 (offre_stage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_stage (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(80) NOT NULL, domaine VARCHAR(50) NOT NULL, type_offre VARCHAR(30) NOT NULL, poste_propose INT NOT NULL, experience VARCHAR(255) DEFAULT NULL, niveau JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', language JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', description LONGTEXT NOT NULL, exigence_offre LONGTEXT NOT NULL, date_postu DATE DEFAULT NULL, mots_cles JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', pfe_book VARCHAR(255) DEFAULT NULL, INDEX IDX_955674F2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, sujet LONGTEXT NOT NULL, date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, cin VARCHAR(255) DEFAULT NULL, date_naissance VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, salaire VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, poste VARCHAR(255) DEFAULT NULL, departement VARCHAR(255) DEFAULT NULL, date_eambauche VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_blocked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_stage (user_id INT NOT NULL, stage_id INT NOT NULL, INDEX IDX_20BE6831A76ED395 (user_id), INDEX IDX_20BE68312298D193 (stage_id), PRIMARY KEY(user_id, stage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_compte (user_id INT NOT NULL, compte_id INT NOT NULL, INDEX IDX_AAA4495DA76ED395 (user_id), INDEX IDX_AAA4495DF2C56620 (compte_id), PRIMARY KEY(user_id, compte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virement (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nomet_prenom VARCHAR(255) DEFAULT NULL, type_virement VARCHAR(255) NOT NULL, transferez_a VARCHAR(255) NOT NULL, num_beneficiare INT NOT NULL, montant VARCHAR(255) NOT NULL, cin INT NOT NULL, rib INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, decision_v VARCHAR(255) NOT NULL, actions_v INT NOT NULL, actions_em INT DEFAULT NULL, photo_cin_v VARCHAR(255) DEFAULT NULL, INDEX IDX_2D4DCFA6F2C56620 (compte_id), INDEX IDX_2D4DCFA6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE9F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE demandestage ADD CONSTRAINT FK_F8FC91A7195A2A28 FOREIGN KEY (offre_stage_id) REFERENCES offre_stage (id)');
        $this->addSql('ALTER TABLE offre_stage ADD CONSTRAINT FK_955674F2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_stage ADD CONSTRAINT FK_20BE6831A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_stage ADD CONSTRAINT FK_20BE68312298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
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
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993A76ED395');
        $this->addSql('ALTER TABLE demandestage DROP FOREIGN KEY FK_F8FC91A7195A2A28');
        $this->addSql('ALTER TABLE offre_stage DROP FOREIGN KEY FK_955674F2A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user_stage DROP FOREIGN KEY FK_20BE6831A76ED395');
        $this->addSql('ALTER TABLE user_stage DROP FOREIGN KEY FK_20BE68312298D193');
        $this->addSql('ALTER TABLE user_compte DROP FOREIGN KEY FK_AAA4495DA76ED395');
        $this->addSql('ALTER TABLE user_compte DROP FOREIGN KEY FK_AAA4495DF2C56620');
        $this->addSql('ALTER TABLE virement DROP FOREIGN KEY FK_2D4DCFA6F2C56620');
        $this->addSql('ALTER TABLE virement DROP FOREIGN KEY FK_2D4DCFA6A76ED395');
        $this->addSql('DROP TABLE cheque');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE demandestage');
        $this->addSql('DROP TABLE offre_stage');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_stage');
        $this->addSql('DROP TABLE user_compte');
        $this->addSql('DROP TABLE virement');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
