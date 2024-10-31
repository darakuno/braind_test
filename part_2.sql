CREATE DATABASE site;
USE site;

CREATE TABLE authors (
   id_author INT PRIMARY KEY AUTO_INCREMENT,
   name VARCHAR(255) NOT NULL,
   email VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE articles (
   id_article INT PRIMARY KEY AUTO_INCREMENT,
   title VARCHAR(100) NOT NULL,
   detailed_description TEXT NOT NULL,
   anons_description VARCHAR(250) NOT NULL,
   date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE author_has_article (
   id_author INT,
   id_article INT,
   PRIMARY KEY (id_author, id_article),
   FOREIGN KEY (id_author) REFERENCES authors(id_author),
   FOREIGN KEY (id_article) REFERENCES articles(id_article)
);

CREATE TABLE subsections (
   id_subsection INT PRIMARY KEY AUTO_INCREMENT,
   name VARCHAR(45) NOT NULL
);

CREATE TABLE article_has_subsection (
   id_article INT,
   id_subsection INT,
   PRIMARY KEY (id_article, id_subsection),
   FOREIGN KEY (id_article) REFERENCES articles(id_article),
   FOREIGN KEY (id_subsection) REFERENCES subsections(id_subsection)
);

CREATE TABLE comment (
   id_comment INT PRIMARY KEY AUTO_INCREMENT,
   id_article INT,
   user_name VARCHAR(255) NOT NULL DEFAULT ('Неизвестный пользвоатель'),
   comment TEXT,
   rating TINYINT(1) CHECK (rating >= 1 AND rating <= 5), -- Оценка от 1 до 5
   date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (id_article) REFERENCES articles(id_article)
);

CREATE TABLE tags (
   id_tag INT PRIMARY KEY AUTO_INCREMENT,
   name VARCHAR(45) UNIQUE NOT NULL
);

CREATE TABLE article_has_tags (
   id_article INT,
   tag_id INT,
   PRIMARY KEY (id_article, tag_id),
   FOREIGN KEY (id_article) REFERENCES articles(id_article),
   FOREIGN KEY (tag_id) REFERENCES tags(id_tag)
);

INSERT INTO authors (name, email) VALUES
('Иван Иванов', 'ivanov@example.com'),
('Петр Петров', 'petrov@example.com'),
('Светлана Светлова', 'svetlova@example.com');

INSERT INTO articles (title, detailed_description, anons_description) VALUES
('Первая статья', 'Это детальное описание первой статьи.', 'Анонс первой статьи.'),
('Вторая статья', 'Это детальное описание второй статьи.', 'Анонс второй статьи.'),
('Третья статья', 'Это детальное описание третьей статьи.', 'Анонс третьей статьи.');

INSERT INTO author_has_article (id_author, id_article) VALUES
(1, 1),  -- Иван Иванов написал первую статью
(1, 2),  -- Иван Иванов написал вторую статью
(2, 3),  -- Петр Петров написал третью статью
(3, 1);  -- Светлана Светлова написала первую статью

INSERT INTO subsections (name) VALUES
('Подраздел 1'),
('Подраздел 2'),
('Подраздел 3');

INSERT INTO article_has_subsection (id_article, id_subsection) VALUES
(1, 1),  -- Первая статья относится к Подразделу 1
(2, 2),  -- Вторая статья относится к Подразделу 2
(2, 3),  -- Вторая статья относится к Подразделу 3
(3, 3);  -- Третья статья относится к Подразделу 3

INSERT INTO comment (id_article, user_name, rating, comment) VALUES
(1, 'Александр', 5, 'Отличная статья!'),
(1, 'Мария', 4, 'Очень познавательно.'),
(2, 'Дмитрий', 3, 'Интересная информация.');

INSERT INTO comment (id_article, rating) VALUES
(1, 5),  -- Первая статья получила оценку 5
(1, 4),  -- Первая статья получила оценку 4
(2, 3),  -- Вторая статья получила оценку 3
(3, 5);  -- Третья статья получила оценку 5

INSERT INTO comment (id_article, comment) VALUES
(3, 'Не хватает примеров.'),
(1, 'Неинтересно.');

INSERT INTO tags (name) VALUES
('Технологии'),
('Наука'),
('Образование');

INSERT INTO article_has_tags (id_article, tag_id) VALUES
(1, 1),  -- Первая статья имеет тег Технологии
(1, 2),  -- Первая статья имеет тег Наука
(2, 2),  -- Вторая статья имеет тег Наука
(3, 3);  -- Третья статья имеет тег Образование

-- ЗАПРОС: получение всех оценок и комментариев к публикациям заданного автора
SELECT
    articles.id_article, comment, rating, title, name AS name_author, user_name AS comment_author
FROM
    articles
JOIN
    author_has_article ON articles.id_article = author_has_article.id_article
JOIN
    authors on author_has_article.id_author = authors.id_author
JOIN
    comment ON articles.id_article = comment.id_article
WHERE
    author_has_article.id_author = 1;




