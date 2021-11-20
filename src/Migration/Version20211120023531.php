<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211120023531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Toggle course certificates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vle_courses ADD certificate_available TINYINT(1) NOT NULL DEFAULT true');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vle_courses DROP certificate_available');
    }
}
