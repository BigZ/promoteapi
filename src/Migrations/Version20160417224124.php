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
 * Add slug to Artist
 */
class Version20160417224124 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE artist ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_15996875E237E06 ON artist (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1599687989D9B62 ON artist (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX UNIQ_15996875E237E06');
        $this->addSql('DROP INDEX UNIQ_1599687989D9B62');
        $this->addSql('ALTER TABLE artist DROP slug');
    }
}
