<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Romain Richard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add label
 */
class Version20160421165058 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE label_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE artist_label (artist_id INT NOT NULL, label_id INT NOT NULL, PRIMARY KEY(artist_id, label_id))');
        $this->addSql('CREATE INDEX IDX_6EAB60BBB7970CF8 ON artist_label (artist_id)');
        $this->addSql('CREATE INDEX IDX_6EAB60BB33B92F39 ON artist_label (label_id)');
        $this->addSql('CREATE TABLE label (id INT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA750E85E237E06 ON label (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA750E8989D9B62 ON label (slug)');
        $this->addSql('CREATE INDEX IDX_EA750E8B03A8386 ON label (created_by_id)');
        $this->addSql('ALTER TABLE artist_label ADD CONSTRAINT FK_6EAB60BBB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artist_label ADD CONSTRAINT FK_6EAB60BB33B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE label ADD CONSTRAINT FK_EA750E8B03A8386 FOREIGN KEY (created_by_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE artist_label DROP CONSTRAINT FK_6EAB60BB33B92F39');
        $this->addSql('DROP SEQUENCE label_id_seq CASCADE');
        $this->addSql('DROP TABLE artist_label');
        $this->addSql('DROP TABLE label');
    }
}
