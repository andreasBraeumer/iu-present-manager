<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260825224021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anhang CHANGE typ typ VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE anhang ADD CONSTRAINT FK_CB4778591A99C4C FOREIGN KEY (geschenk_id) REFERENCES geschenk (id)');
        $this->addSql('CREATE INDEX IDX_CB4778591A99C4C ON anhang (geschenk_id)');
        $this->addSql('ALTER TABLE anlass CHANGE datum datum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE aufgabe CHANGE faellig_am faellig_am DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE aufgabe ADD CONSTRAINT FK_78C774EF1A99C4C FOREIGN KEY (geschenk_id) REFERENCES geschenk (id)');
        $this->addSql('CREATE INDEX IDX_78C774EF1A99C4C ON aufgabe (geschenk_id)');
        $this->addSql('ALTER TABLE benachrichtigung ADD CONSTRAINT FK_3EA84296217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_3EA84296217BBB47 ON benachrichtigung (person_id)');
        $this->addSql('ALTER TABLE freigabe ADD CONSTRAINT FK_41BDCEDD217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41BDCEDD5F37A13B ON freigabe (token)');
        $this->addSql('CREATE INDEX IDX_41BDCEDD217BBB47 ON freigabe (person_id)');
        $this->addSql('ALTER TABLE geschenk CHANGE beschreibung beschreibung VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE geschaetzter_preis geschaetzter_preis NUMERIC(10, 2) DEFAULT NULL, CHANGE datum datum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE geschenk ADD CONSTRAINT FK_A4364FA5217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE geschenk ADD CONSTRAINT FK_A4364FA5B93640E1 FOREIGN KEY (anlass_id) REFERENCES anlass (id)');
        $this->addSql('CREATE INDEX IDX_A4364FA5217BBB47 ON geschenk (person_id)');
        $this->addSql('CREATE INDEX IDX_A4364FA5B93640E1 ON geschenk (anlass_id)');
        $this->addSql('ALTER TABLE person ADD user_id INT NOT NULL, CHANGE geburtsdatum geburtsdatum DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_34DCD176A76ED395 ON person (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anhang DROP FOREIGN KEY FK_CB4778591A99C4C');
        $this->addSql('DROP INDEX IDX_CB4778591A99C4C ON anhang');
        $this->addSql('ALTER TABLE anhang CHANGE typ typ VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE anlass CHANGE datum datum DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE aufgabe DROP FOREIGN KEY FK_78C774EF1A99C4C');
        $this->addSql('DROP INDEX IDX_78C774EF1A99C4C ON aufgabe');
        $this->addSql('ALTER TABLE aufgabe CHANGE faellig_am faellig_am DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE benachrichtigung DROP FOREIGN KEY FK_3EA84296217BBB47');
        $this->addSql('DROP INDEX IDX_3EA84296217BBB47 ON benachrichtigung');
        $this->addSql('ALTER TABLE freigabe DROP FOREIGN KEY FK_41BDCEDD217BBB47');
        $this->addSql('DROP INDEX UNIQ_41BDCEDD5F37A13B ON freigabe');
        $this->addSql('DROP INDEX IDX_41BDCEDD217BBB47 ON freigabe');
        $this->addSql('ALTER TABLE geschenk DROP FOREIGN KEY FK_A4364FA5217BBB47');
        $this->addSql('ALTER TABLE geschenk DROP FOREIGN KEY FK_A4364FA5B93640E1');
        $this->addSql('DROP INDEX IDX_A4364FA5217BBB47 ON geschenk');
        $this->addSql('DROP INDEX IDX_A4364FA5B93640E1 ON geschenk');
        $this->addSql('ALTER TABLE geschenk CHANGE beschreibung beschreibung VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(32) NOT NULL, CHANGE geschaetzter_preis geschaetzter_preis NUMERIC(5, 2) NOT NULL, CHANGE datum datum DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176A76ED395');
        $this->addSql('DROP INDEX IDX_34DCD176A76ED395 ON person');
        $this->addSql('ALTER TABLE person DROP user_id, CHANGE geburtsdatum geburtsdatum DATETIME NOT NULL');
    }
}
