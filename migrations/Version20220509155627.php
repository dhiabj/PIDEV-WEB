<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509155627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation_admin MODIFY idr INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation_admin DROP FOREIGN KEY reclamation_admin_ibfk_1');
        $this->addSql('DROP INDEX id ON reclamation_admin');
        $this->addSql('ALTER TABLE reclamation_admin DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE reclamation_admin CHANGE idr idr INT NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE reponse reponse LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation_admin ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE reclamation_user DROP FOREIGN KEY reclamation_user_ibfk_2');
        $this->addSql('ALTER TABLE reclamation_user DROP FOREIGN KEY reclamation_user_ibfk_1');
        $this->addSql('DROP INDEX user_id ON reclamation_user');
        $this->addSql('DROP INDEX idr ON reclamation_user');
        $this->addSql('ALTER TABLE reclamation_user ADD idrep INT NOT NULL, ADD userid INT DEFAULT NULL, ADD status VARCHAR(255) DEFAULT NULL, DROP user_id, DROP idr, CHANGE titre titre LONGTEXT DEFAULT NULL, CHANGE texte texte LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE evenement CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE livraison CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reclamation_admin MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation_admin DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE reclamation_admin CHANGE id id INT DEFAULT NULL, CHANGE idr idr INT AUTO_INCREMENT NOT NULL, CHANGE reponse reponse TEXT NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reclamation_admin ADD CONSTRAINT reclamation_admin_ibfk_1 FOREIGN KEY (id) REFERENCES reclamation_user (id)');
        $this->addSql('CREATE INDEX id ON reclamation_admin (id)');
        $this->addSql('ALTER TABLE reclamation_admin ADD PRIMARY KEY (idr)');
        $this->addSql('ALTER TABLE reclamation_user ADD idr INT DEFAULT NULL, DROP idrep, DROP status, CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE texte texte TEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE userid user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation_user ADD CONSTRAINT reclamation_user_ibfk_2 FOREIGN KEY (idr) REFERENCES reclamation_admin (idr)');
        $this->addSql('ALTER TABLE reclamation_user ADD CONSTRAINT reclamation_user_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX user_id ON reclamation_user (user_id)');
        $this->addSql('CREATE INDEX idr ON reclamation_user (idr)');
        $this->addSql('ALTER TABLE reservation CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE num_Tel num_Tel VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
    }
}
