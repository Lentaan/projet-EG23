<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610044022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ADD CONSTRAINT FK_B04F2D0299E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B04F2D0299E6F5DF ON soldier (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE soldier DROP CONSTRAINT FK_B04F2D0299E6F5DF');
        $this->addSql('DROP INDEX IDX_B04F2D0299E6F5DF');
        $this->addSql('ALTER TABLE soldier DROP player_id');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
    }
}
