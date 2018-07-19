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
 * Harmonize createdat & updatedat
 */
class Version20170926004336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE artist ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE artist ALTER updated_at SET NOT NULL');
        $this->addSql('ALTER TABLE gig ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE gig RENAME COLUMN createdat TO created_at');
        $this->addSql('ALTER TABLE label ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE label ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE artist DROP created_at');
        $this->addSql('ALTER TABLE artist ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE label DROP updated_at');
        $this->addSql('ALTER TABLE label DROP created_at');
        $this->addSql('ALTER TABLE gig ADD createdat TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE gig DROP created_at');
        $this->addSql('ALTER TABLE gig DROP updated_at');
    }
}
