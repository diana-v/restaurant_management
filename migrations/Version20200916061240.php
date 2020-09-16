<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916061240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics DROP FOREIGN KEY topics_ibfk_1');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY posts_ibfk_1');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY posts_ibfk_2');
        $this->addSql('ALTER TABLE topics DROP FOREIGN KEY topics_ibfk_2');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (cat_id INT AUTO_INCREMENT NOT NULL, cat_name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, cat_description VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, UNIQUE INDEX cat_name_unique (cat_name), PRIMARY KEY(cat_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE posts (post_id INT AUTO_INCREMENT NOT NULL, post_topic INT NOT NULL, post_by INT NOT NULL, post_content TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, post_date DATETIME NOT NULL, INDEX post_by (post_by), INDEX post_topic (post_topic), PRIMARY KEY(post_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE topics (topic_id INT AUTO_INCREMENT NOT NULL, topic_cat INT NOT NULL, topic_by INT NOT NULL, topic_subject VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, topic_date DATETIME NOT NULL, INDEX topic_by (topic_by), INDEX topic_cat (topic_cat), PRIMARY KEY(topic_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (user_id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(30) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, user_pass VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, user_email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, user_date DATETIME NOT NULL, user_level INT NOT NULL, UNIQUE INDEX user_name_unique (user_name), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT posts_ibfk_1 FOREIGN KEY (post_topic) REFERENCES topics (topic_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT posts_ibfk_2 FOREIGN KEY (post_by) REFERENCES users (user_id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE topics ADD CONSTRAINT topics_ibfk_1 FOREIGN KEY (topic_cat) REFERENCES categories (cat_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topics ADD CONSTRAINT topics_ibfk_2 FOREIGN KEY (topic_by) REFERENCES users (user_id) ON UPDATE CASCADE');
        $this->addSql('DROP TABLE user');
    }
}
