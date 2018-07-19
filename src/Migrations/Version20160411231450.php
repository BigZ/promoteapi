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
 * Add users & artists
 */
class Version20160411231450 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE artist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE api_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE artist (id INT NOT NULL, name VARCHAR(255) NOT NULL, bio TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE api_user (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, api_key VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BAF85E0677 ON api_user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BAC912ED9D ON api_user (api_key)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BAE7927C74 ON api_user (email)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE artist_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE api_user_id_seq CASCADE');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE api_user');
    }
}
