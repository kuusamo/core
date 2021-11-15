<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211115162008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add privacy to course entity.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vle_courses ADD privacy VARCHAR(16) NOT NULL');
        $this->addSql("UPDATE vle_courses SET privacy = 'PRIVATE'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vle_courses DROP privacy');
    }
}
