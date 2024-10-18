CREATE DATABASE `dinner`;

CREATE TABLE `dinner`.`recipe` (
	`recipe_id` BIGINT NOT NULL AUTO_INCREMENT,
	`recipe_title` VARCHAR(100) NOT NULL DEFAULT "Unknown Title",
	`instructions` TEXT NOT NULL,
	`ingredients` TEXT NOT NULL,
	`img_name` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`recipe_id`)
);

DELETE FROM recipe;

LOAD DATA LOCAL INFILE 'C:/Users/alexx/Downloads/FoodIngredientDataWithId.csv' INTO TABLE recipe
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n' 
;

ALTER TABLE `recipe` ADD INDEX `idx_title_img_name` (`recipe_title`, `img_name`);