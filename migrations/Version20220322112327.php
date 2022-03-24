<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322112327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_progress_lesson (course_progress_id INT NOT NULL, lesson_id INT NOT NULL, INDEX IDX_68D61D7637B5B71D (course_progress_id), INDEX IDX_68D61D76CDF80196 (lesson_id), PRIMARY KEY(course_progress_id, lesson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_progress_lesson ADD CONSTRAINT FK_68D61D7637B5B71D FOREIGN KEY (course_progress_id) REFERENCES course_progress (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_progress_lesson ADD CONSTRAINT FK_68D61D76CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_progress DROP FOREIGN KEY FK_8BB59F10FED07355');
        $this->addSql('DROP INDEX IDX_8BB59F10FED07355 ON course_progress');
        $this->addSql('ALTER TABLE course_progress DROP lessons_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE course_progress_lesson');
        $this->addSql('ALTER TABLE courses CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE course_picture course_picture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE course_progress ADD lessons_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course_progress ADD CONSTRAINT FK_8BB59F10FED07355 FOREIGN KEY (lessons_id) REFERENCES lesson (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8BB59F10FED07355 ON course_progress (lessons_id)');
        $this->addSql('ALTER TABLE lesson CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE video video VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lesson_content lesson_content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE section CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE student CHANGE pseudo pseudo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE study_material CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE path path VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE teacher CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE discr discr VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
