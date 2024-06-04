<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603170331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, datetime_publication DATETIME NOT NULL, name VARCHAR(255) NOT NULL, content MEDIUMTEXT NOT NULL, INDEX IDX_BA5AE01D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, num_order INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_param_type (category_id INT NOT NULL, param_type_id INT NOT NULL, INDEX IDX_18C2356012469DE2 (category_id), INDEX IDX_18C23560E7164A69 (param_type_id), PRIMARY KEY(category_id, param_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_type (id INT AUTO_INCREMENT NOT NULL, type ENUM(\'number\',\'numbering\',\'color\'), name VARCHAR(255) NOT NULL, unit VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_value (id INT AUTO_INCREMENT NOT NULL, param_type_id INT NOT NULL, number_value DOUBLE PRECISION DEFAULT NULL, string_value VARCHAR(4096) DEFAULT NULL, INDEX IDX_A578FF09E7164A69 (param_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, brand_id INT NOT NULL, product_state_id INT NOT NULL, num_order INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 4) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD44F5D008 (brand_id), INDEX IDX_D34A04ADCE685656 (product_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_param_value (product_id INT NOT NULL, param_value_id INT NOT NULL, INDEX IDX_95761164584665A (product_id), INDEX IDX_9576116A46AFA66 (param_value_id), PRIMARY KEY(product_id, param_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_param_type ADD CONSTRAINT FK_18C2356012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_param_type ADD CONSTRAINT FK_18C23560E7164A69 FOREIGN KEY (param_type_id) REFERENCES param_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE param_value ADD CONSTRAINT FK_A578FF09E7164A69 FOREIGN KEY (param_type_id) REFERENCES param_type (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCE685656 FOREIGN KEY (product_state_id) REFERENCES product_state (id)');
        $this->addSql('ALTER TABLE product_param_value ADD CONSTRAINT FK_95761164584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_param_value ADD CONSTRAINT FK_9576116A46AFA66 FOREIGN KEY (param_value_id) REFERENCES param_value (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('ALTER TABLE category_param_type DROP FOREIGN KEY FK_18C2356012469DE2');
        $this->addSql('ALTER TABLE category_param_type DROP FOREIGN KEY FK_18C23560E7164A69');
        $this->addSql('ALTER TABLE param_value DROP FOREIGN KEY FK_A578FF09E7164A69');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCE685656');
        $this->addSql('ALTER TABLE product_param_value DROP FOREIGN KEY FK_95761164584665A');
        $this->addSql('ALTER TABLE product_param_value DROP FOREIGN KEY FK_9576116A46AFA66');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_param_type');
        $this->addSql('DROP TABLE param_type');
        $this->addSql('DROP TABLE param_value');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_param_value');
        $this->addSql('DROP TABLE product_state');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
