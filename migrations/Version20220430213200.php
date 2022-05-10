<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430213200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, INDEX menu_id (menu_id), INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, pourcentage INT NOT NULL, datelimite DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, rating INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE ingredients_menu DROP FOREIGN KEY FK_497CE8043EC4DCE');
        $this->addSql('ALTER TABLE ingredients_menu DROP FOREIGN KEY FK_497CE804CCD7E912');
        $this->addSql('ALTER TABLE ingredients_menu ADD CONSTRAINT FK_497CE8043EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients_menu ADD CONSTRAINT FK_497CE804CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_1');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_2');
        $this->addSql('DROP INDEX livraison_ibfk_1 ON livraison');
        $this->addSql('CREATE INDEX user_id ON livraison (user_id)');
        $this->addSql('DROP INDEX livraison_ibfk_2 ON livraison');
        $this->addSql('CREATE INDEX livreur_id ON livraison (livreur_id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT livraison_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT livraison_ibfk_2 FOREIGN KEY (livreur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP role, DROP etat, DROP is_verified, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX email_unique ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hashed_token VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE commande CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE evenement CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE categorie categorie VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE ingredients_menu DROP FOREIGN KEY FK_497CE8043EC4DCE');
        $this->addSql('ALTER TABLE ingredients_menu DROP FOREIGN KEY FK_497CE804CCD7E912');
        $this->addSql('ALTER TABLE ingredients_menu ADD CONSTRAINT FK_497CE8043EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE ingredients_menu ADD CONSTRAINT FK_497CE804CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FA76ED395');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FF8646701');
        $this->addSql('ALTER TABLE livraison CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('DROP INDEX user_id ON livraison');
        $this->addSql('CREATE INDEX livraison_ibfk_1 ON livraison (user_id)');
        $this->addSql('DROP INDEX livreur_id ON livraison');
        $this->addSql('CREATE INDEX livraison_ibfk_2 ON livraison (livreur_id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FF8646701 FOREIGN KEY (livreur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation_admin CHANGE reponse reponse TEXT NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reclamation_user CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE texte texte TEXT NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, ADD etat VARCHAR(25) DEFAULT \'not verified\' NOT NULL COLLATE `utf8mb4_general_ci`, ADD is_verified TINYINT(1) NOT NULL, DROP roles, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password VARCHAR(16) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE num_tel num_tel VARCHAR(8) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON user');
        $this->addSql('CREATE UNIQUE INDEX email_unique ON user (email)');
    }
}
