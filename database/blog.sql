SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema keimuo_blog
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `keimuo_blog` ;

-- -----------------------------------------------------
-- Schema keimuo_blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `keimuo_blog` DEFAULT CHARACTER SET utf8 ;
USE `keimuo_blog` ;

-- -----------------------------------------------------
-- Table `keimuo_blog`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `keimuo_blog`.`posts` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `headline` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `author` VARCHAR(50) NOT NULL,
  `add_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `keimuo_blog`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `keimuo_blog`.`users` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(20) NOT NULL,
  `lastname` VARCHAR(30) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `mail_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `keimuo_blog`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `keimuo_blog`.`comments` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `author` VARCHAR(45) NOT NULL,
  `content` TEXT NOT NULL,
  `add_date` DATETIME NOT NULL,
  `validation` TINYINT UNSIGNED NOT NULL,
  `posts_id` SMALLINT UNSIGNED NOT NULL,
  `users_id` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `posts_id`, `users_id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_comment_post1_idx` (`posts_id` ASC),
  INDEX `fk_comments_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_comment_post1`
    FOREIGN KEY (`posts_id`)
    REFERENCES `keimuo_blog`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `keimuo_blog`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `keimuo_blog`.`posts`
-- -----------------------------------------------------
START TRANSACTION;
USE `keimuo_blog`;
INSERT INTO `keimuo_blog`.`posts` (`id`, `title`, `headline`, `content`, `author`, `add_date`) VALUES (1, 'Hello World !', 'Qu\'est-ce que le Lorem Ipsum ?', 'Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l\'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n\'a pas fait que survivre cinq siècles, mais s\'est aussi adapté à la bureautique informatique, sans que son contenu n\'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.', 'Kevin Mulot', '2019-08-02 10:03:00');
INSERT INTO `keimuo_blog`.`posts` (`id`, `title`, `headline`, `content`, `author`, `add_date`) VALUES (2, 'Lorem Ipsum', 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut lacus vitae lectus aliquam porta eget quis nisl. Nulla a ligula cursus, commodo ipsum eu, faucibus est. Praesent aliquet tortor non tincidunt ultrices. Curabitur posuere lacus ac lorem lobortis venenatis. Phasellus sit amet lectus nec lorem faucibus ornare. Vivamus diam mauris, iaculis quis luctus ac, laoreet nec ligula. Curabitur sed felis placerat, egestas neque sed, congue eros. Vestibulum at egestas neque. Ut porta dui in nulla imperdiet tincidunt. Sed at libero consequat ligula elementum vestibulum in vel risus. Integer rutrum finibus magna mattis suscipit. Aliquam commodo tortor sem, in porttitor neque ornare ut. Proin in vestibulum magna, eu elementum sapien.', 'Kevin Mulot', '2019-06-03 04:04:00');
INSERT INTO `keimuo_blog`.`posts` (`id`, `title`, `headline`, `content`, `author`, `add_date`) VALUES (3, 'Lorem Ipsum', 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut lacus vitae lectus aliquam porta eget quis nisl. Nulla a ligula cursus, commodo ipsum eu, faucibus est. Praesent aliquet tortor non tincidunt ultrices. Curabitur posuere lacus ac lorem lobortis venenatis. Phasellus sit amet lectus nec lorem faucibus ornare. Vivamus diam mauris, iaculis quis luctus ac, laoreet nec ligula. Curabitur sed felis placerat, egestas neque sed, congue eros. Vestibulum at egestas neque. Ut porta dui in nulla imperdiet tincidunt. Sed at libero consequat ligula elementum vestibulum in vel risus. Integer rutrum finibus magna mattis suscipit. Aliquam commodo tortor sem, in porttitor neque ornare ut. Proin in vestibulum magna, eu elementum sapien.', 'Kevin Mulot', '2019-07-23 14:06:00');
INSERT INTO `keimuo_blog`.`posts` (`id`, `title`, `headline`, `content`, `author`, `add_date`) VALUES (4, 'Lorem Ipsum', 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut lacus vitae lectus aliquam porta eget quis nisl. Nulla a ligula cursus, commodo ipsum eu, faucibus est. Praesent aliquet tortor non tincidunt ultrices. Curabitur posuere lacus ac lorem lobortis venenatis. Phasellus sit amet lectus nec lorem faucibus ornare. Vivamus diam mauris, iaculis quis luctus ac, laoreet nec ligula. Curabitur sed felis placerat, egestas neque sed, congue eros. Vestibulum at egestas neque. Ut porta dui in nulla imperdiet tincidunt. Sed at libero consequat ligula elementum vestibulum in vel risus. Integer rutrum finibus magna mattis suscipit. Aliquam commodo tortor sem, in porttitor neque ornare ut. Proin in vestibulum magna, eu elementum sapien.', 'Kevin Mulot', '2019-08-01 20:24:00');

COMMIT;


-- -----------------------------------------------------
-- Data for table `keimuo_blog`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `keimuo_blog`;
INSERT INTO `keimuo_blog`.`users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `status`) VALUES (1, 'Administrateur', 'Blog', 'Admin', 'admin@blog.com', '$2y$10$Lfknb4/kWZ7UsyeZBwjCBOoPSv8vDRAIDbzbo8O9hJLrt2s0gL5Si', 'admin');
INSERT INTO `keimuo_blog`.`users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `status`) VALUES (2, 'Keanu', 'Reaves', 'Neo', 'iambreathtaking@god.com', '$2y$10$.Gthz1D7zl4xvcrnjyeld.2HVhGYxtg3B7X4JsN5PX9kCRx5Lc.4i', 'normal');

COMMIT;


-- -----------------------------------------------------
-- Data for table `keimuo_blog`.`comments`
-- -----------------------------------------------------
START TRANSACTION;
USE `keimuo_blog`;
INSERT INTO `keimuo_blog`.`comments` (`id`, `author`, `content`, `add_date`, `validation`, `posts_id`, `users_id`) VALUES (1, 'Admin', 'Hello World !', '2019-04-22 15:12:52', 1, 1, 1);

COMMIT;

