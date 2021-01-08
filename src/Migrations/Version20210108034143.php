<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210108034143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial Migration';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('CREATE SEQUENCE artist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE gig_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE label_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE artist (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, bio TEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15996875E237E06 ON artist (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1599687989D9B62 ON artist (slug)');
        $this->addSql('CREATE TABLE artist_label (artist_id INT NOT NULL, label_id INT NOT NULL, PRIMARY KEY(artist_id, label_id))');
        $this->addSql('CREATE INDEX IDX_6EAB60BBB7970CF8 ON artist_label (artist_id)');
        $this->addSql('CREATE INDEX IDX_6EAB60BB33B92F39 ON artist_label (label_id)');
        $this->addSql('CREATE TABLE artist_gig (artist_id INT NOT NULL, gig_id INT NOT NULL, PRIMARY KEY(artist_id, gig_id))');
        $this->addSql('CREATE INDEX IDX_D35A513B7970CF8 ON artist_gig (artist_id)');
        $this->addSql('CREATE INDEX IDX_D35A513FE058E5 ON artist_gig (gig_id)');
        $this->addSql('CREATE TABLE gig (id INT NOT NULL, name VARCHAR(255) NOT NULL, startDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, endDate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, venue VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, facebookLink VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D53020A25E237E06 ON gig (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D53020A24E6EB42D ON gig (facebookLink)');
        $this->addSql('CREATE TABLE label (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA750E85E237E06 ON label (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA750E8989D9B62 ON label (slug)');
        $this->addSql('ALTER TABLE artist_label ADD CONSTRAINT FK_6EAB60BBB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artist_label ADD CONSTRAINT FK_6EAB60BB33B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artist_gig ADD CONSTRAINT FK_D35A513B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artist_gig ADD CONSTRAINT FK_D35A513FE058E5 FOREIGN KEY (gig_id) REFERENCES gig (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE artist_label DROP CONSTRAINT FK_6EAB60BBB7970CF8');
        $this->addSql('ALTER TABLE artist_gig DROP CONSTRAINT FK_D35A513B7970CF8');
        $this->addSql('ALTER TABLE artist_gig DROP CONSTRAINT FK_D35A513FE058E5');
        $this->addSql('ALTER TABLE artist_label DROP CONSTRAINT FK_6EAB60BB33B92F39');
        $this->addSql('DROP SEQUENCE artist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE gig_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE label_id_seq CASCADE');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE artist_label');
        $this->addSql('DROP TABLE artist_gig');
        $this->addSql('DROP TABLE gig');
        $this->addSql('DROP TABLE label');
    }
}
