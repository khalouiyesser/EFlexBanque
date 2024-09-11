<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408035359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC40108A79');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E40108A79');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01166D1F9C');
        $this->addSql('ALTER TABLE investissement DROP FOREIGN KEY FK_B8E64E01A76ED395');
        $this->addSql('DROP TABLE investissement');
        $this->addSql('ALTER TABLE cheque DROP actions_c, DROP actions_e');
        $this->addSql('DROP INDEX IDX_67F068BC40108A79 ON commentaire');
        $this->addSql('ALTER TABLE commentaire CHANGE investissement_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCFD02F13 ON commentaire (evenement_id)');
        $this->addSql('ALTER TABLE demandestage CHANGE date date DATE NOT NULL');
        $this->addSql('DROP INDEX IDX_B26681E40108A79 ON evenement');
        $this->addSql('ALTER TABLE evenement CHANGE investissement_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_B26681E166D1F9C ON evenement (project_id)');
        $this->addSql('ALTER TABLE virement DROP actions_v, DROP actions_em');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE investissement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, montant INT NOT NULL, date_investissement DATE NOT NULL, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type_investissement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, duree INT NOT NULL, taux_rendement INT NOT NULL, statut VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_B8E64E01166D1F9C (project_id), INDEX IDX_B8E64E01A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE investissement ADD CONSTRAINT FK_B8E64E01A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cheque ADD actions_c INT DEFAULT NULL, ADD actions_e INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFD02F13');
        $this->addSql('DROP INDEX IDX_67F068BCFD02F13 ON commentaire');
        $this->addSql('ALTER TABLE commentaire CHANGE evenement_id investissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC40108A79 FOREIGN KEY (investissement_id) REFERENCES investissement (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC40108A79 ON commentaire (investissement_id)');
        $this->addSql('ALTER TABLE demandestage CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E166D1F9C');
        $this->addSql('DROP INDEX IDX_B26681E166D1F9C ON evenement');
        $this->addSql('ALTER TABLE evenement CHANGE project_id investissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E40108A79 FOREIGN KEY (investissement_id) REFERENCES investissement (id)');
        $this->addSql('CREATE INDEX IDX_B26681E40108A79 ON evenement (investissement_id)');
        $this->addSql('ALTER TABLE virement ADD actions_v INT NOT NULL, ADD actions_em INT DEFAULT NULL');
    }
}
