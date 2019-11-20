-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20-Nov-2019 às 20:30
-- Versão do servidor: 10.4.8-MariaDB
-- versão do PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `clubedosign`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `login`
--

CREATE TABLE `login` (
  `cod` varchar(32) NOT NULL,
  `type` varchar(150) DEFAULT NULL,
  `user_information` int(11) DEFAULT 0,
  `mid` int(11) DEFAULT 0,
  `avatar` varchar(1024) DEFAULT NULL,
  `user` varchar(512) DEFAULT NULL,
  `pass` varchar(32) DEFAULT NULL,
  `perfil` int(3) DEFAULT NULL,
  `documents` text DEFAULT NULL,
  `products` text DEFAULT NULL,
  `repairs` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `evaluation` int(3) NOT NULL DEFAULT 0,
  `registerDate` datetime DEFAULT NULL,
  `obs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `login`
--

INSERT INTO `login` (`cod`, `type`, `user_information`, `mid`, `avatar`, `user`, `pass`, `perfil`, `documents`, `products`, `repairs`, `status`, `evaluation`, `registerDate`, `obs`) VALUES
('4297f44b13955235245b2497399d7a93', 'master', 1, 0, NULL, 'admin', 'e99a18c428cb38d5f260853678922e03', 1, 'WyIiXQ==', 'WyIiXQ==', 'WyIiXQ==', 1, 0, '2019-07-16 07:42:34', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `manufacture_information`
--

CREATE TABLE `manufacture_information` (
  `cod` int(11) NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `logo` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sc` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razao_social` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `responsavel_adm` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_adm` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone_adm` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_fantasia` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `celular_adm` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `documents` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tfixo` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comprehensiveness` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tec_assit` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pages`
--

CREATE TABLE `pages` (
  `cod` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `icon` varchar(150) NOT NULL,
  `link` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pages`
--

INSERT INTO `pages` (`cod`, `title`, `icon`, `link`) VALUES
(1, 'Chat', 'fal fa-comments-alt', '/painel/chat'),
(2, 'Notificações', 'fal fa-bell', '/painel/notifications'),
(3, 'Configuracoes', 'fal fa-cogs', '/master/profile-settings'),
(4, 'Perfil', 'fal fa-user', '/painel/profile'),
(5, 'Orçamentos', 'fal fa-calculator', '/painel/badgets');

-- --------------------------------------------------------

--
-- Estrutura da tabela `profiles`
--

CREATE TABLE `profiles` (
  `cod` int(3) NOT NULL,
  `desc` varchar(1024) NOT NULL,
  `licenses` text NOT NULL,
  `pages` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `profiles`
--

INSERT INTO `profiles` (`cod`, `desc`, `licenses`, `pages`) VALUES
(1, 'DESENVOLVIMENTO', '[\"26366d14125ca99cc74a8e506ad9f03a\",\"a38c63814824ef5effca5888bf7ee41b\",\"f76967270b715f599a9c14d2d2419fd5\",\"a5f2adfb497420957e80b0f5c0c29295\",\"c17f211a80df3ef1fa216c1ff03b18e5\",\"be3d09cb5c19f5f2fa2d0d3644c2db68\",\"c38c635d826053a1ecca6d5c43c32801\",\"8d97b150a415e80e4c8910eae169c7e8\",\"d0ea0f12de7c2702eb6c9cda9268107e\",\"6c36723a88bd0befcdc1b5251ef49bce\",\"6076405e87783734c407e281ab50ab1f\",\"c89fa3a474b1dcbb8c632c31ff84f9ce\",\"ed326c14e94eae62b5eb756a84acf02d\",\"f3106b0fb5b467685d478f17701066f5\",\"875f5607eb7b95cc261b98f73d762038\",\"bd7539a1b19b0429c5d75e3b63309840\",\"ac6e49ca79d0180ada17d02de94cd38c\",\"dd7382dc0281c39a2318ec2d89449f4e\",\"82e6f507a8a20c93ce699e0caa1a2735\",\"19b7c25a84a8386d904e6850c96dba39\",\"2106c174ebe70184dc24ecc32b8ccf62\",\"a1b9f713b7045d2bfb9378715213bfc4\"]', '[3]'),
(2, 'CLUBER', '[\"27765b87eefa5bfcad86bfe2001e1e49\",\"a065ee7f94174a6d8339d8fc74e88608\",\"89189248c77fbd6b7a5bf03d8fc87778\",\"d2e4fd2c4e81c6c10e1d24fc2865a30c\",\"dd7382dc0281c39a2318ec2d89449f4e\",\"20e82c3642c673138582e8ebd2277970\",\"ac6e49ca79d0180ada17d02de94cd38c\",\"0e89db00c98a7f569e14e0a6b7c38907\",\"10eccb86d269c5e7c7bf024512797670\",\"eb84b04a91449e0ac985825735e7091a\"]', '[]'),
(3, 'SIGN', '[\"eb84b04a91449e0ac985825735e7091a\",\"a065ee7f94174a6d8339d8fc74e88608\"]', '[]'),
(4, 'CLUBER+', '[\"eb84b04a91449e0ac985825735e7091a\",\"ac6e49ca79d0180ada17d02de94cd38c\",\"20e82c3642c673138582e8ebd2277970\",\"27765b87eefa5bfcad86bfe2001e1e49\",\"0e89db00c98a7f569e14e0a6b7c38907\",\"d2e4fd2c4e81c6c10e1d24fc2865a30c\",\"10eccb86d269c5e7c7bf024512797670\",\"dd7382dc0281c39a2318ec2d89449f4e\",\"89189248c77fbd6b7a5bf03d8fc87778\",\"2d9e10ebe1cfa2ed31936653851068ae\",\"a065ee7f94174a6d8339d8fc74e88608\",\"fec6793562b91ef92c89b86a780ff343\"]', '[]'),
(5, 'SERVICER', '[\"ac6e49ca79d0180ada17d02de94cd38c\",\"20e82c3642c673138582e8ebd2277970\",\"27765b87eefa5bfcad86bfe2001e1e49\",\"0e89db00c98a7f569e14e0a6b7c38907\",\"d2e4fd2c4e81c6c10e1d24fc2865a30c\",\"10eccb86d269c5e7c7bf024512797670\",\"dd7382dc0281c39a2318ec2d89449f4e\",\"89189248c77fbd6b7a5bf03d8fc87778\",\"2d9e10ebe1cfa2ed31936653851068ae\",\"a065ee7f94174a6d8339d8fc74e88608\",\"eb84b04a91449e0ac985825735e7091a\",\"fec6793562b91ef92c89b86a780ff343\"]', '[]'),
(7, 'MANUFACTURE', '[\"2d9e10ebe1cfa2ed31936653851068ae\",\"0e89db00c98a7f569e14e0a6b7c38907\",\"10eccb86d269c5e7c7bf024512797670\",\"977fe34dc6121dc031f503bf1a9adccd\",\"d98976a4500ff69b316939d5a5a54ddd\",\"fec6793562b91ef92c89b86a780ff343\",\"4d9a50b676a6ea375f987b14dc35071f\",\"eb84b04a91449e0ac985825735e7091a\",\"8d97b150a415e80e4c8910eae169c7e8\",\"1511aa7a59266ef10e3bb5c469d33aed\",\"6115d8212b80a46bc86a7bf93f4e9538\"]', '[]');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessions`
--

CREATE TABLE `sessions` (
  `cod` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  `ws_sessions` text NOT NULL,
  `event` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `sessions`
--

INSERT INTO `sessions` (`cod`, `user`, `ws_sessions`, `event`) VALUES
('7401869c7e87cdfef33a77eee1884c11', '4297f44b13955235245b2497399d7a93', 'W10=', '2019-11-20 15:17:57'),
('bc7d089f2d357b1692d02e50a1320f46', '4297f44b13955235245b2497399d7a93', 'Array', '2019-11-20 15:15:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `settings`
--

CREATE TABLE `settings` (
  `cod` varchar(250) NOT NULL,
  `data` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `settings`
--

INSERT INTO `settings` (`cod`, `data`) VALUES
('cluberplus', '4'),
('manufacturer', '7'),
('repair', '2'),
('resell', '2'),
('sellers', '6'),
('sign', '3');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_information`
--

CREATE TABLE `user_information` (
  `cod` int(10) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `num` varchar(250) DEFAULT NULL,
  `comp` varchar(250) DEFAULT NULL,
  `uf` varchar(25) DEFAULT NULL,
  `rua` varchar(250) DEFAULT NULL,
  `tfixo` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `user_information`
--

INSERT INTO `user_information` (`cod`, `name`, `cpf`, `cep`, `bairro`, `cidade`, `num`, `comp`, `uf`, `rua`, `tfixo`, `whatsapp`) VALUES
(1, 'Simple Singer', '136.659.317-04', '', '', '', '', '', '', '', '(24) 3336-2307', '(24) 98875-2365');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `manufacture_information`
--
ALTER TABLE `manufacture_information`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`cod`);

--
-- Índices para tabela `user_information`
--
ALTER TABLE `user_information`
  ADD PRIMARY KEY (`cod`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `manufacture_information`
--
ALTER TABLE `manufacture_information`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `pages`
--
ALTER TABLE `pages`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `profiles`
--
ALTER TABLE `profiles`
  MODIFY `cod` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `user_information`
--
ALTER TABLE `user_information`
  MODIFY `cod` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
