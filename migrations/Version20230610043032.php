<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230610043032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE player ADD total_points INT NOT NULL');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE soldier ALTER ai TYPE TEXT');
        $this->addSql('ALTER TABLE soldier ALTER rank TYPE TEXT');
        $this->addSql('ALTER TABLE lobby ALTER speciality TYPE TEXT');
        $this->addSql('ALTER TABLE player DROP total_points');
        $this->addSql('ALTER TABLE player ALTER speciality TYPE TEXT');
    }
}
