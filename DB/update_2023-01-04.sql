ALTER TABLE `cursos` ADD `posterior` VARCHAR(250) NOT NULL DEFAULT '' AFTER `curFondo`;
ALTER TABLE `alumnocurso` ADD `nota` DOUBLE NULL DEFAULT '0' AFTER `cursoId`;
