<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610160256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE zone_control_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE zone_control (id INT NOT NULL, game_id INT DEFAULT NULL, controlling_player_id INT DEFAULT NULL, zone TEXT NOT NULL, is_controlled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_53EF2F73E48FD905 ON zone_control (game_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53EF2F739743F63C ON zone_control (controlling_player_id)');
        $this->addSql('ALTER TABLE zone_control ADD CONSTRAINT FK_53EF2F73E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zone_control ADD CONSTRAINT FK_53EF2F739743F63C FOREIGN KEY (controlling_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE player ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_98197A65E48FD905 ON player (game_id)');
        $this->addSql('ALTER TABLE soldier ADD zone_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ADD CONSTRAINT FK_B04F2D02125FD4EE FOREIGN KEY (zone_control_id) REFERENCES zone_control (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B04F2D02125FD4EE ON soldier (zone_control_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65E48FD905');
        $this->addSql('ALTER TABLE soldier DROP CONSTRAINT FK_B04F2D02125FD4EE');
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE zone_control_id_seq CASCADE');
        $this->addSql('ALTER TABLE zone_control DROP CONSTRAINT FK_53EF2F73E48FD905');
        $this->addSql('ALTER TABLE zone_control DROP CONSTRAINT FK_53EF2F739743F63C');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE zone_control');
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('DROP INDEX IDX_B04F2D02125FD4EE');
        $this->addSql('ALTER TABLE soldier DROP zone_control_id');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
        $this->addSql('DROP INDEX IDX_98197A65E48FD905');
        $this->addSql('ALTER TABLE player DROP game_id');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
    }
}
