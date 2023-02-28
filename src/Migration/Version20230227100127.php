<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227100127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'SSO tokens';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE vle_sso_tokens (`id` INT AUTO_INCREMENT NOT NULL, `user_id` INT NOT NULL, `token` VARCHAR(64) NOT NULL, `expires` DATETIME NOT NULL, INDEX UNIQ_105B8BCDBFF6842F (user_id), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vle_sso_tokens ADD CONSTRAINT FK_7E5535AB3DAEA5F9 FOREIGN KEY (user_id) REFERENCES vle_users (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE vle_sso_tokens');
    }
}
