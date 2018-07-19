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
 * artist gigs
 */
class Version20160506185205 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE artist_gig (artist_id INT NOT NULL, gig_id INT NOT NULL, PRIMARY KEY(artist_id, gig_id))');
        $this->addSql('CREATE INDEX IDX_D35A513B7970CF8 ON artist_gig (artist_id)');
        $this->addSql('CREATE INDEX IDX_D35A513FE058E5 ON artist_gig (gig_id)');
        $this->addSql('ALTER TABLE artist_gig ADD CONSTRAINT FK_D35A513B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artist_gig ADD CONSTRAINT FK_D35A513FE058E5 FOREIGN KEY (gig_id) REFERENCES gig (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE artist_gig');
    }
}
