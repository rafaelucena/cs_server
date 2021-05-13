<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210513225310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO item (name, amount) VALUES ('Produkt 1', 4), ('Produkt 2', 12), ('Produkt 5', 0), ('Produkt 7', 6), ('Produkt 8', 2)");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("SET FOREIGN_KEY_CHECKS = 0");
        $this->addSql("TRUNCATE TABLE item");
        $this->addSql("SET FOREIGN_KEY_CHECKS = 1");
    }
}
