<!--1-  users
	id,username,email,password,created_at
categories
	id,name,created_at
films
	id,title,description,release_date,category_id,created_at,
comments
	id,film_id,user_id,comment,created_at
wishlist
	id,film_id,user_id,created_at -->


<!-- //CREATE DATABASE -->
create database cinema;

<!-- //CREATE TABLE -->

create table users (
id bigint primary key AUTO_INCREMENT,
username varchar(255) not null,
email varchar(120) UNIQUE not null,
password varchar(255) UNIQUE not null,
created_at timestamp default CURRENT_TIMESTAMP
);

create table categories (
id bigint primary key AUTO_INCREMENT,
name varchar(255) not null,
created_at timestamp default CURRENT_TIMESTAMP
);

create table films (
id bigint primary key AUTO_INCREMENT,
title varchar(255) not null,
description varchar(60000) default null,
release_date timestamp,
category_id bigint,
created_at timestamp default CURRENT_TIMESTAMP,
FOREIGN key (category_id) REFERENCES categories(id)
);

create table comments (
id bigint primary key AUTO_INCREMENT,
film_id bigint not null,
user_id bigint not null,
comment varchar(60000) default null,
created_at timestamp default CURRENT_TIMESTAMP,
FOREIGN key (film_id) REFERENCES films(id),
foreign key (user_id) REFERENCES users(id)
);

create table wishlist (
id bigint primary key AUTO_INCREMENT,
film_id bigint not null,
user_id bigint not null,
created_at timestamp default CURRENT_TIMESTAMP,
FOREIGN key (film_id) REFERENCES films(id),
foreign key (user_id) REFERENCES users(id)
);

<!-- //////////////////////////////////////////////////////////////// -->
INSERT USERS
insert into users (username, email, password) VALUES
('Shalala', 'shalala@gmail.com', '123'),
('Aysel', 'aysel@gmail.com', '1234'),
('Fidan','fidan@gmail.com', '12345');


INSERT CATEGORIES
insert into categories(name)
VALUES
('detective'),
('drama'),
('fantasy');


INSERT FILMS
insert into films(title, description,release_date, category_id)
VALUES
('Chinatown', 'There is description of movie', '2001-09-11', 1),
('The Bear', 'There is description of movie', '2015-12-01', 2),
(' The Seventh Seal (1957)', 'There is description of movie', '1957-03-09', 3);


INSERT COMMENTS
insert into comments(film_id, user_id, comment)
VALUES
(1,7,'Beautiful devective movie'),
(2,8,'I like this drama'),
(3,9,'It is very interesting fantasy movie');
(1,9,'This is second comment for detective movie');


INSERT WISHLIST
insert into wishlist(film_id,user_id)
VALUES
(1,9),
(2,8),
(3,7),
(3,8);





<!-- 2- 1.Butun Filmleri kateqoriya adlari ile gosterin.

2.Spesifik filmin butun kommentlerini getirin

3.Userin wishlistindeki butun filmleri detalli melumatlari ile birge getiurin(filmin adi, release date ve s.)

4.Butun commentleri commenti yazan sexsin adi ile birge getirin

5.Butun filmleri commentlerinin sayi ile birge getirin -->

<!-- 1 -->
select films.title, categories.name from films
left join categories on films.category_id = categories.id

<!-- 2 -->
select films.title, comments.comment from films
left join comments on comments.film_id=films.id
where films.id=1

<!-- 3 -->
select *, wishlist.user_id from films
left join wishlist on films.id=wishlist.film_id
where wishlist.user_id=8

<!-- 4 -->
select comments.comment, users.username from comments
left join users on users.id=comments.user_id

<!-- 5 -->
select films.title, count(comments.film_id) as count_of_comments from films
left join comments on comments.film_id=films.id
group by comments.film_id
