<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180929001007 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE nros_identificacion ADD CONSTRAINT FK_250F9A7D35AEE23E FOREIGN KEY (id_articulo_id) REFERENCES stock (id)');
        $this->addSql('CREATE INDEX IDX_250F9A7D35AEE23E ON nros_identificacion (id_articulo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE nros_identificacion DROP FOREIGN KEY FK_250F9A7D35AEE23E');
        $this->addSql('DROP INDEX IDX_250F9A7D35AEE23E ON nros_identificacion');
    }
}
