<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200106115426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice CHANGE pdf pdf VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD slug VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE zip zip INT DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE tel tel INT DEFAULT NULL, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL, CHANGE company_number company_number INT DEFAULT NULL, CHANGE birthday birthday DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice CHANGE pdf pdf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP slug, CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE zip zip INT DEFAULT NULL, CHANGE country country VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE tel tel INT DEFAULT NULL, CHANGE company_name company_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE company_number company_number INT DEFAULT NULL, CHANGE birthday birthday DATE DEFAULT \'NULL\'');
    }
}
