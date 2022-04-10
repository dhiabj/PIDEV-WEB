<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409113722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, pourcentage INT NOT NULL, datelimite DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_commande CHANGE command_id command_id INT DEFAULT NULL, CHANGE menu_id menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE reclamation_id reclamation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE user_id user_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotion');
        $this->addSql('ALTER TABLE commande CHANGE user_id user_id INT NOT NULL, CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE evenement CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE ingredients CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE menu CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE ingredients ingredients VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE menu_commande CHANGE menu_id menu_id INT NOT NULL, CHANGE command_id command_id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE user_id user_id INT NOT NULL, CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison raison VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reponse CHANGE reclamation_id reclamation_id INT NOT NULL, CHANGE reponse reponse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE event_id event_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE num_tel num_tel VARCHAR(8) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE roles roles VARCHAR(255) NOT NULL COLLATE `utf8mb4_bin`');
    }
}
