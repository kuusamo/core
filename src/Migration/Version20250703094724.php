<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703094724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add login tokens';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vle_login_tokens (token VARCHAR(255) NOT NULL, user_id INT NOT NULL, ip_address VARCHAR(255) NOT NULL, expires DATETIME NOT NULL, INDEX IDX_67E88ED4A76ED395 (user_id), PRIMARY KEY(token)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vle_login_tokens ADD CONSTRAINT FK_67E88ED4A76ED395 FOREIGN KEY (user_id) REFERENCES vle_users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vle_login_tokens');
    }
}
