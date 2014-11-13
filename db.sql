SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáze: `lmctest`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `intro` text NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `enabled` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Vypisuji data pro tabulku `article`
--

INSERT INTO `article` (`id`, `title`, `intro`, `content`, `date`, `user_id`, `enabled`) VALUES
(1, 'Test: PHP developer', 'Tohle je domácí úkol\r\n<address>\r\nIvan Ignáth\r\n+420774546159\r\n</address>', '<p>Vytvořte jednoduchou aplikaci v PHP.</p?\r\n<h3>Scope</h3>\r\n<ul>\r\n<li>Registrace</li>\r\n<li>Přihlášení</li>\r\n<li>Přidání, editace, zobrazení článků</li>\r\n</ul>\r\n<p>Aplikace prosím pište tak, aby umožňovala pozdější rozšiřování. Výsledek nahrajte na Github.</p>', '2014-11-13 13:35:14', 1, 'Y');

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(128) CHARACTER SET utf8 NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(127) CHARACTER SET utf16 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `first_name`, `last_name`, `email`) VALUES
(1, 'igi', '$2y$10$KkKPWTZJpVzbfu.zXvlP7.UW8ztIzQVPlMZJ7f6wnsPgeEeBlOqo.', 'Ivan', 'Ignáth', 'ignath@gmail.com');