CREATE DATABASE `dinner`;

CREATE TABLE `dinner`.`recipe` (
	`recipe_id` BIGINT NOT NULL AUTO_INCREMENT,
	`recipe_title` VARCHAR(100) NOT NULL DEFAULT "Unknown Title",
	`instructions` TEXT NOT NULL,
	`img_name` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`recipe_id`)
);

CREATE TABLE `dinner`.`ingredient` (
	`ingredient_id` BIGINT NOT NULL AUTO_INCREMENT,
	`ingredient_name` VARCHAR(100) NOT NULL DEFAULT "Unknown Ingredient",
	PRIMARY KEY (`ingredient_id`)
);

CREATE TABLE `dinner`.`recipe_ingredients` (
	`recipe_id` BIGINT NOT NULL,
	`ingredient_id` BIGINT NOT NULL,
	`quanitity` VARCHAR(10) NOT NULL,
	`unit` VARCHAR(20) NULL DEFAULT NULL,
	PRIMARY KEY (`recipe_id`),
	CONSTRAINT `fk_recipe_id`
		FOREIGN KEY (`recipe_id`)
		REFERENCES `dinner`.`recipe` (`recipe_id`)
		ON DELETE NO ACTION
		ON UPDATE NO ACTION,
	CONSTRAINT `fk_ingredient_id`
		FOREIGN KEY (`ingredient_id`)
		REFERENCES `dinner`.`ingredient` (`ingredient_id`)
		ON DELETE NO ACTION
		ON UPDATE NO ACTION
);