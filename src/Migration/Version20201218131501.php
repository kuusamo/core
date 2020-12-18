<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201218131501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add welcome text to course entity';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vle_courses ADD welcome_text LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vle_courses DROP welcome_text');
    }
}
