<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddArticleImageTagArticletagTable extends AbstractMigration
{
    public function up()
    {
        $article_table_sql = 'CREATE TABLE Articles(
article_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(200) NOT NULL,
body TEXT NOT NULL,
thumbnail TEXT NOT NULL,
user_id INT UNSIGNED NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);';

        $image_table_sql = 'CREATE TABLE Images(
image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
url TEXT NOT NULL,
article_id INT UNSIGNED NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (article_id) REFERENCES Articles(article_id) ON DELETE CASCADE
);';

        $tag_table_sql = 'CREATE TABLE Tags(
tag_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
label VARCHAR(30) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);';

        $article_tag_table_sql = 'CREATE TABLE ArticlesTags(
article_tag_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
article_id INT UNSIGNED NOT NULL,
tag_id INT UNSIGNED NOT NULL,
FOREIGN KEY (article_id) REFERENCES Articles(article_id),
FOREIGN KEY (tag_id) REFERENCES Tags(tag_id)
);';
        $tables = [
            "Articles" => $article_table_sql,
            "Images" => $image_table_sql,
            "Tags" => $tag_table_sql,
            "ArticlesTags" => $article_tag_table_sql
        ];

        foreach ($tables as $name => $sql) {
            $exists = $this->hasTable($name);
            if (!$exists) {
                $this->execute($sql);
            }
        }
    }
}
