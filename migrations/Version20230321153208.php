<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321153208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE codeposte codeposte VARCHAR(255) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE mot_pass password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` CHANGE address address VARCHAR(255) NOT NULL, CHANGE codeposte codeposte VARCHAR(255) NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(20) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE password mot_pass VARCHAR(255) NOT NULL');
    }
}
