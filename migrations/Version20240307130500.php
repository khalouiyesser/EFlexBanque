<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307130500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire_hadhemi (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, user_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, nom_aut_com VARCHAR(255) NOT NULL, image_u VARCHAR(255) DEFAULT NULL, INDEX IDX_AD5458EC7294869C (article_id), INDEX IDX_AD5458ECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, objet_rec VARCHAR(255) NOT NULL, contenu_rec VARCHAR(255) NOT NULL, adr_rec VARCHAR(255) NOT NULL, nom_aut_rec VARCHAR(255) NOT NULL, dep_rec VARCHAR(255) NOT NULL, statut_rec VARCHAR(255) DEFAULT NULL, piece_jrec VARCHAR(255) NOT NULL, date_rec DATETIME NOT NULL, INDEX IDX_CE606404A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT DEFAULT NULL, user_id INT DEFAULT NULL, adr_rep VARCHAR(255) NOT NULL, date_rep DATETIME NOT NULL, contenu_rep VARCHAR(255) NOT NULL, piece_jrep VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC72D6BA2D9 (reclamation_id), INDEX IDX_5FB6DEC7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_commentaire (id INT AUTO_INCREMENT NOT NULL, commentaire_id INT DEFAULT NULL, user_id INT DEFAULT NULL, contenu_rep_com LONGTEXT NOT NULL, nom_rep_com VARCHAR(255) NOT NULL, date_rep_com DATETIME NOT NULL, INDEX IDX_6E5B5DB9BA9CD190 (commentaire_id), INDEX IDX_6E5B5DB9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire_hadhemi ADD CONSTRAINT FK_AD5458EC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire_hadhemi ADD CONSTRAINT FK_AD5458ECA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reponse_commentaire ADD CONSTRAINT FK_6E5B5DB9BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire_hadhemi (id)');
        $this->addSql('ALTER TABLE reponse_commentaire ADD CONSTRAINT FK_6E5B5DB9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE article ADD user_id INT DEFAULT NULL, ADD image_art VARCHAR(255) DEFAULT NULL, ADD likes INT NOT NULL, ADD dislikes INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('ALTER TABLE evenement ADD likes INT NOT NULL, ADD dislikes INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire_hadhemi DROP FOREIGN KEY FK_AD5458EC7294869C');
        $this->addSql('ALTER TABLE commentaire_hadhemi DROP FOREIGN KEY FK_AD5458ECA76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7A76ED395');
        $this->addSql('ALTER TABLE reponse_commentaire DROP FOREIGN KEY FK_6E5B5DB9BA9CD190');
        $this->addSql('ALTER TABLE reponse_commentaire DROP FOREIGN KEY FK_6E5B5DB9A76ED395');
        $this->addSql('DROP TABLE commentaire_hadhemi');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_commentaire');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        $this->addSql('ALTER TABLE article DROP user_id, DROP image_art, DROP likes, DROP dislikes');
        $this->addSql('ALTER TABLE evenement DROP likes, DROP dislikes');
    }
}
