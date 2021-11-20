<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211120153324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Image original filenames';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vle_images ADD original_filename VARCHAR(128) NOT NULL');
        $this->addSql('UPDATE vle_images SET original_filename = filename');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vle_images DROP original_filename');
    }
}
