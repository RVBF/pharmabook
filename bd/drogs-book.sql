SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE database farmabook;

use farmabook;

/*Tabela de enderecos*/
CREATE table endereco(
	id int(10) AUTO_INCREMENT NOT NULl PRIMARY key,
	cep varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	logradouro varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	numero int(11) COLLATE utf8_unicode_ci DEFAULT NULL,
	referencia varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	bairro varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	cidade varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	estado varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	uf varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	pais varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Tabela de usarios*/
CREATE TABLE usuarios (
	id int(10) AUTO_INCREMENT NOT NULl PRIMARY key,
	nome varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	login varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	telefone varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	senha varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
	endereco_id int,
	criacao timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	atualizacao timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	FOREIGN KEY (endereco_id) REFERENCES endereco(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Tabela de laboratorios*/
create table laboratorio
(
	id int(10) AUTO_INCREMENT NOT NULl PRIMARY key,
	nome varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL

)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Tabela de medicamentos*/
CREATE TABLE medicamento(
	id int(10) AUTO_INCREMENT NOT NULl PRIMARY key,
	ean varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	cnpj varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	ggrem varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	registro varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	nome_comercial varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	classe_terapeutica varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	laboratorio varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	laboratorio_id int,
	FOREIGN KEY (laboratorio_id) REFERENCES laboratorio(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `medicamento` ADD INDEX( `id`, `ean`, `registro`, `nome_comercial`, `classe_terapeutica`);
