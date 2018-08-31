<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180830194942 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE elineas ADD CONSTRAINT FK_8FF995BA3932A204 FOREIGN KEY (id_articulo) REFERENCES articulos (id)');
        $this->addSql('CREATE INDEX IDX_8FF995BA3932A204 ON elineas (id_articulo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE elineas DROP FOREIGN KEY FK_8FF995BA3932A204');
        $this->addSql('DROP INDEX IDX_8FF995BA3932A204 ON elineas');
    }
}
