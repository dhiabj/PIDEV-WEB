<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409124527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, promo INT NOT NULL, datelimite DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT DEFAULT NULL, reponse VARCHAR(255) NOT NULL, INDEX reclamation_id (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE commande CHANGE total total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE ingredients DROP FOREIGN KEY ingredients_ibfk_1');
        $this->addSql('DROP INDEX menu_id ON ingredients');
        $this->addSql('ALTER TABLE ingredients DROP menu_id');
        $this->addSql('CREATE UNIQUE INDEX nom ON ingredients (nom)');
        $this->addSql('ALTER TABLE menu ADD ingredients VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX titre ON menu (titre)');
        $this->addSql('ALTER TABLE menu_commande DROP date');
        $this->addSql('ALTER TABLE reservation CHANGE user_id user_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('ALTER TABLE commande CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE total total INT NOT NULL');
        $this->addSql('ALTER TABLE evenement CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('DROP INDEX nom ON ingredients');
        $this->addSql('ALTER TABLE ingredients ADD menu_id INT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE ingredients ADD CONSTRAINT ingredients_ibfk_1 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX menu_id ON ingredients (menu_id)');
        $this->addSql('DROP INDEX titre ON menu');
        $this->addSql('ALTER TABLE menu DROP ingredients, DROP image, CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE menu_commande ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison raison VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE event_id event_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE num_tel num_tel VARCHAR(8) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE roles roles VARCHAR(255) NOT NULL COLLATE `utf8mb4_bin`');
    }
}
