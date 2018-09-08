<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180907221909 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE articulos (id INT AUTO_INCREMENT NOT NULL, familia VARCHAR(255) NOT NULL, articulo VARCHAR(255) NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, detalle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codigos (id INT AUTO_INCREMENT NOT NULL, id_articulo INT NOT NULL, codigo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecabecera (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, destino VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elineas (id INT AUTO_INCREMENT NOT NULL, orden INT DEFAULT NULL, id_articulo INT NOT NULL, articulo VARCHAR(255) NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, cantidad INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entrega (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, dpto VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entrega_articulos (id INT AUTO_INCREMENT NOT NULL, id_entr INT NOT NULL, id_art INT NOT NULL, cantidad INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE icabecera (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, proveedor VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ilineas (id INT AUTO_INCREMENT NOT NULL, orden INT NOT NULL, id_articulo INT NOT NULL, cantidad INT NOT NULL, articulo VARCHAR(255) NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, familia VARCHAR(255) NOT NULL, articulo VARCHAR(255) NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, detalle VARCHAR(255) NOT NULL, cantidad INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE articulos');
        $this->addSql('DROP TABLE codigos');
        $this->addSql('DROP TABLE ecabecera');
        $this->addSql('DROP TABLE elineas');
        $this->addSql('DROP TABLE entrega');
        $this->addSql('DROP TABLE entrega_articulos');
        $this->addSql('DROP TABLE icabecera');
        $this->addSql('DROP TABLE ilineas');
        $this->addSql('DROP TABLE stock');
    }
}
