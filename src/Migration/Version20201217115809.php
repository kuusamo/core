<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217115809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add folders to file entity';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vle_files ADD folder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vle_files ADD CONSTRAINT FK_6B57521162CB942 FOREIGN KEY (folder_id) REFERENCES vle_folders (id)');
        $this->addSql('CREATE INDEX IDX_6B57521162CB942 ON vle_files (folder_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vle_files DROP FOREIGN KEY FK_6B57521162CB942');
        $this->addSql('DROP INDEX IDX_6B57521162CB942 ON vle_files');
        $this->addSql('ALTER TABLE vle_files DROP folder_id');
    }
}
