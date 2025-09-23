<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923124524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE acces ADD nom_acces VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acces DROP numero_badgeuse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD is_principal BOOLEAN DEFAULT false NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE travailler DROP is_principal
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD date_derniere_connexion TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD date_derniere_modification TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD compte_actif BOOLEAN NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD date_suppression_prevue DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP adresse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_badge ADD date_attribution TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE travailler ADD is_principal BOOLEAN DEFAULT false NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_badge DROP date_attribution
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP is_principal
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD adresse VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP date_derniere_connexion
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP date_derniere_modification
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP compte_actif
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP date_suppression_prevue
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acces ADD numero_badgeuse INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acces DROP nom_acces
        SQL);
    }
}
