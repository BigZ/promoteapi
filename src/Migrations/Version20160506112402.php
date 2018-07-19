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
 * Add gigs
 */
class Version20160506112402 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE gig_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE gig (id INT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, startDate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, endDate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, venue VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, facebookLink VARCHAR(255) DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D53020A25E237E06 ON gig (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D53020A24E6EB42D ON gig (facebookLink)');
        $this->addSql('CREATE INDEX IDX_D53020A2B03A8386 ON gig (created_by_id)');
        $this->addSql('ALTER TABLE gig ADD CONSTRAINT FK_D53020A2B03A8386 FOREIGN KEY (created_by_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE gig_id_seq CASCADE');
        $this->addSql('DROP TABLE gig');
    }
}
