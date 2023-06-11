<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230611195409 extends AbstractMigration
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
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
        $this->addSql('DROP INDEX uniq_53ef2f739743f63c');
        $this->addSql('ALTER TABLE zone_control ALTER zone TYPE TEXT');
        $this->addSql('CREATE INDEX IDX_53EF2F739743F63C ON zone_control (controlling_player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('DROP INDEX IDX_53EF2F739743F63C');
        $this->addSql('ALTER TABLE zone_control ALTER zone TYPE TEXT');
        $this->addSql('CREATE UNIQUE INDEX uniq_53ef2f739743f63c ON zone_control (controlling_player_id)');
    }
}
