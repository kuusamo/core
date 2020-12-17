<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217113018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Folders entity';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vle_folders (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_4F62E4525E237E06 (name), INDEX IDX_4F62E452727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vle_folders ADD CONSTRAINT FK_4F62E452727ACA70 FOREIGN KEY (parent_id) REFERENCES vle_folders (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vle_folders DROP FOREIGN KEY FK_4F62E452727ACA70');
        $this->addSql('DROP TABLE vle_folders');
    }
}
