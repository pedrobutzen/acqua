-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 24-Out-2014 às 05:42
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `acqua_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bloqueio`
--

CREATE TABLE IF NOT EXISTS `bloqueio` (
  `idbloqueio` int(11) NOT NULL AUTO_INCREMENT,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime DEFAULT NULL,
  `usuario` varchar(128) NOT NULL,
  PRIMARY KEY (`idbloqueio`),
  KEY `fk_bloqueio_usuario1_idx` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='								' AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `bloqueio`
--

INSERT INTO `bloqueio` (`idbloqueio`, `data_inicio`, `data_fim`, `usuario`) VALUES
(8, '2014-10-23 15:31:45', NULL, 'usuario.26683');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lancamento`
--

CREATE TABLE IF NOT EXISTS `lancamento` (
  `idlancamento` int(11) NOT NULL AUTO_INCREMENT,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_recebimento` datetime DEFAULT NULL,
  `data_devolucao` datetime DEFAULT NULL,
  `usuario` varchar(128) NOT NULL,
  `usuario_recebimento` varchar(128) DEFAULT NULL,
  `usuario_devolucao` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`idlancamento`),
  KEY `fk_lancamento_usuario1_idx` (`usuario`),
  KEY `fk_lancamento_usuario2_idx` (`usuario_recebimento`),
  KEY `fk_lancamento_usuario3_idx` (`usuario_devolucao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Extraindo dados da tabela `lancamento`
--

INSERT INTO `lancamento` (`idlancamento`, `data_criacao`, `data_recebimento`, `data_devolucao`, `usuario`, `usuario_recebimento`, `usuario_devolucao`) VALUES
(24, '2014-10-23 15:42:37', '2014-10-23 15:43:07', '2014-10-23 15:43:31', 'usuario.20021', 'pedro.butzen', 'pedro.butzen'),
(25, '2014-10-24 00:56:42', '2014-10-24 01:04:29', '2014-10-24 01:06:55', 'usuario.20021', 'pedro.butzen', 'pedro.butzen');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lancamento_has_peca`
--

CREATE TABLE IF NOT EXISTS `lancamento_has_peca` (
  `idlancamento_has_peca` int(11) NOT NULL AUTO_INCREMENT,
  `idpeca` int(11) NOT NULL,
  `idlancamento` int(11) NOT NULL,
  PRIMARY KEY (`idlancamento_has_peca`),
  KEY `fk_lancamento_peca_peca1_idx` (`idpeca`),
  KEY `fk_lancamento_peca_lancamento1_idx` (`idlancamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=436 ;

--
-- Extraindo dados da tabela `lancamento_has_peca`
--

INSERT INTO `lancamento_has_peca` (`idlancamento_has_peca`, `idpeca`, `idlancamento`) VALUES
(429, 55, 24),
(431, 55, 25),
(432, 57, 25),
(433, 56, 25),
(434, 54, 25),
(435, 53, 25);

-- --------------------------------------------------------

--
-- Estrutura da tabela `num_lavanderia`
--

CREATE TABLE IF NOT EXISTS `num_lavanderia` (
  `num` varchar(16) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `num_lavanderia`
--

INSERT INTO `num_lavanderia` (`num`, `sexo`) VALUES
('508', 'f'),
('509', 'f'),
('510', 'f'),
('5B', 'm'),
('5C', 'm'),
('5D', 'm'),
('5E', 'm'),
('5F', 'm'),
('5G', 'm'),
('6A', 'm'),
('6B', 'm'),
('6C', 'm'),
('6D', 'm'),
('7B', 'm');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ocorrencia`
--

CREATE TABLE IF NOT EXISTS `ocorrencia` (
  `idocorrencia` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(128) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `idpeca` int(11) NOT NULL,
  `idtipo_ocorrencia` int(11) NOT NULL,
  PRIMARY KEY (`idocorrencia`),
  KEY `fk_ocorrencia_tipo_ocorrencia1_idx` (`idtipo_ocorrencia`),
  KEY `fk_ocorrencia_peca1_idx` (`idpeca`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Extraindo dados da tabela `ocorrencia`
--

INSERT INTO `ocorrencia` (`idocorrencia`, `descricao`, `status`, `idpeca`, `idtipo_ocorrencia`) VALUES
(7, 'Mancha branca na gola', 1, 53, 1),
(11, 'asdf', 0, 53, 2),
(12, 'fas', 0, 53, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `peca`
--

CREATE TABLE IF NOT EXISTS `peca` (
  `idpeca` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(256) NOT NULL,
  `marca` varchar(128) NOT NULL,
  `cor` varchar(128) NOT NULL,
  `tamanho` varchar(45) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `idtipo` int(11) NOT NULL,
  `usuario` varchar(128) NOT NULL,
  PRIMARY KEY (`idpeca`),
  KEY `fk_peca_tipo1_idx` (`idtipo`),
  KEY `fk_peca_usuario1_idx` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Extraindo dados da tabela `peca`
--

INSERT INTO `peca` (`idpeca`, `descricao`, `marca`, `cor`, `tamanho`, `status`, `idtipo`, `usuario`) VALUES
(53, 'ggwrehgsgfhs', 'h sdfh h sdfh', 'sdfhsdhs', ' hshs f', 0, 2, 'usuario.20021'),
(54, 'gfds g sdfg sdfg s', 'dfg sdgsd gsf g', 'sg s gsf', 'gsfgsfg ', 1, 1, 'usuario.20021'),
(55, ' sdfg sdf g dfgsdgaa', 'd fsgdfgsdf ', ' sdfgsdf', 's gdfgsdf', 0, 1, 'usuario.20021'),
(56, 'fas', 'afs', 'afdsa', 'sdaf', 1, 1, 'usuario.20021'),
(57, 'afsdfasfa', 'afsda', 'dfasd', 'fdsaadsf', 1, 2, 'usuario.20021');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo`
--

CREATE TABLE IF NOT EXISTS `tipo` (
  `idtipo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `usuario` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`idtipo`),
  KEY `fk_tipo_usuario1_idx` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Extraindo dados da tabela `tipo`
--

INSERT INTO `tipo` (`idtipo`, `nome`, `usuario`) VALUES
(1, 'Camiseta', NULL),
(2, 'Calça', NULL),
(3, 'Shorts', NULL),
(4, 'Lençol', NULL),
(5, 'Edredom', NULL),
(27, 'aaa', 'usuario.20021');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_ocorrencia`
--

CREATE TABLE IF NOT EXISTS `tipo_ocorrencia` (
  `idtipo_ocorrencia` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(128) NOT NULL,
  PRIMARY KEY (`idtipo_ocorrencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tipo_ocorrencia`
--

INSERT INTO `tipo_ocorrencia` (`idtipo_ocorrencia`, `tipo`) VALUES
(1, 'Manchada'),
(2, 'Perdida'),
(3, 'Rasgada');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `usuario` varchar(128) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `quarto` varchar(16) DEFAULT NULL,
  `ramal` varchar(32) DEFAULT NULL,
  `permissao` varchar(2) NOT NULL,
  `num` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`usuario`),
  KEY `fk_usuario_num_lavanderia1_idx` (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`usuario`, `nome`, `sexo`, `senha`, `quarto`, `ramal`, `permissao`, `num`) VALUES
('pedro.butzen', 'Pedro Butzen', 'm', 'unasp', '203', '9133', '0', NULL),
('usuario.15831', 'Noaããa1 9276', 'm', '26053', '23', '28421', '0', NULL),
('usuario.17347', 'Nome 29760', 'm', '12153', '52', '17598', '0', NULL),
('usuario.18447', 'Nome 28828', 'm', '10917', '2', '7802', '1', NULL),
('usuario.19443', 'Nome 4272', 'm', '27433', '5235', '20142', '0', NULL),
('usuario.20021', 'nome 6492', 'm', '3131', '238', '21818', '3', '5F'),
('usuario.20287', 'Nome 8204', 'm', '32085', '23542', '29546', '1', NULL),
('usuario.22059', 'Nome 31368', 'm', '19489', '23423', '10310', '0', NULL),
('usuario.22815', 'Nome 5228', 'm', '18997', '5243', '28362', '2', NULL),
('usuario.24135', 'Nome 30324', 'm', '30621', '234523', '29714', '2', NULL),
('usuario.24483', 'Nome 4768', 'f', '24153', '3442', '31262', '0', NULL),
('usuario.26423', 'Nome 25764', 'f', '525', '523', '1474', '1', NULL),
('usuario.26683', 'Nome 2904', 'f', '21681', '623', '11670', '3', '508'),
('usuario.30611', 'Nome 25552', 'f', '4041', '636', '9934', '3', '509'),
('usuario.31215', 'nome 27900', 'f', '24453', '236', '25050', '3', '510'),
('usuario.8219', 'Nome 5560', 'm', '32657', '4842', '12022', '3', '5C'),
('usuario.9139', 'Nome 28016', 'm', '26857', '2786', '22894', '3', '5D'),
('usuario.9707', 'nome 28488', 'm', '481', '3685', '2822', '3', '5E');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `bloqueio`
--
ALTER TABLE `bloqueio`
  ADD CONSTRAINT `fk_bloqueio_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `lancamento`
--
ALTER TABLE `lancamento`
  ADD CONSTRAINT `fk_lancamento_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lancamento_usuario2` FOREIGN KEY (`usuario_recebimento`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lancamento_usuario3` FOREIGN KEY (`usuario_devolucao`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limitadores para a tabela `lancamento_has_peca`
--
ALTER TABLE `lancamento_has_peca`
  ADD CONSTRAINT `fk_lancamento_peca_lancamento1` FOREIGN KEY (`idlancamento`) REFERENCES `lancamento` (`idlancamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lancamento_peca_peca1` FOREIGN KEY (`idpeca`) REFERENCES `peca` (`idpeca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ocorrencia`
--
ALTER TABLE `ocorrencia`
  ADD CONSTRAINT `fk_ocorrencia_peca1` FOREIGN KEY (`idpeca`) REFERENCES `peca` (`idpeca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ocorrencia_tipo_ocorrencia1` FOREIGN KEY (`idtipo_ocorrencia`) REFERENCES `tipo_ocorrencia` (`idtipo_ocorrencia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `peca`
--
ALTER TABLE `peca`
  ADD CONSTRAINT `fk_peca_tipo1` FOREIGN KEY (`idtipo`) REFERENCES `tipo` (`idtipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peca_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tipo`
--
ALTER TABLE `tipo`
  ADD CONSTRAINT `fk_tipo_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_num_lavanderia1` FOREIGN KEY (`num`) REFERENCES `num_lavanderia` (`num`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
