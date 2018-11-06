<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025171855 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE elineas ADD ecabecera_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE elineas ADD CONSTRAINT FK_8FF995BAAD46B287 FOREIGN KEY (ecabecera_id) REFERENCES ecabecera (id)');
        $this->addSql('CREATE INDEX IDX_8FF995BAAD46B287 ON elineas (ecabecera_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE elineas DROP FOREIGN KEY FK_8FF995BAAD46B287');
        $this->addSql('DROP INDEX IDX_8FF995BAAD46B287 ON elineas');
        $this->addSql('ALTER TABLE elineas DROP ecabecera_id');
    }
}
