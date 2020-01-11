<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111162350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744AE5D0B3D');
        $this->addSql('DROP INDEX IDX_90651744AE5D0B3D ON invoice');
        $this->addSql('ALTER TABLE invoice CHANGE pdf pdf VARCHAR(255) NOT NULL, CHANGE monthly_id invoice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517442989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('CREATE INDEX IDX_906517442989F1FD ON invoice (invoice_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517442989F1FD');
        $this->addSql('DROP INDEX IDX_906517442989F1FD ON invoice');
        $this->addSql('ALTER TABLE invoice CHANGE pdf pdf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE invoice_id monthly_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744AE5D0B3D FOREIGN KEY (monthly_id) REFERENCES invoice (id)');
        $this->addSql('CREATE INDEX IDX_90651744AE5D0B3D ON invoice (monthly_id)');
    }
}
