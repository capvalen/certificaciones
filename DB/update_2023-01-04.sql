ALTER TABLE `cursos` ADD `posterior` VARCHAR(250) NOT NULL DEFAULT '' AFTER `curFondo`;
ALTER TABLE `alumnocurso` ADD `nota` DOUBLE NULL DEFAULT '0' AFTER `cursoId`;
ALTER TABLE `cursos` ADD `cTipo` INT NULL DEFAULT '1' COMMENT '1=curso; 2=seminario; 3=ponente' AFTER `curCopia`;
ALTER TABLE `alumnocurso` ADD `aTipo` INT NULL DEFAULT '1' COMMENT '1=curso; 2=seminario; 3=ponente' AFTER `correo`;