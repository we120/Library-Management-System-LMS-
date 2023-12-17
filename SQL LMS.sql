-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2023 at 06:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_book` (IN `bookId` INT)   BEGIN
    DELETE FROM books WHERE book_id = bookId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_book` (IN `p_book_name` VARCHAR(255), IN `p_author_id` INT, IN `p_cat_id` INT, IN `p_ISBN` VARCHAR(255))   BEGIN
  
        INSERT INTO books (book_name, author_id, cat_id, ISBN)
        VALUES (p_book_name, p_author_id, p_cat_id, p_ISBN);

        SELECT 'Book added successfully' AS result;
   
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectAllAuthors` ()   BEGIN
    SELECT * FROM authors;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectAllBooks` ()   BEGIN

    SELECT books.book_id, books.book_name, books.ISBN,  authors.author_name, categories.category_name
    FROM
        books 
JOIN authors 
ON books.author_id = authors.author_id
JOIN categories 
 ON books.cat_id = categories.cat_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectAllCategories` ()   BEGIN
    SELECT * FROM categories;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateBook` (IN `p_book_id` INT, IN `p_book_name` VARCHAR(255), IN `p_author_id` INT, IN `p_cat_id` INT, IN `p_ISBN` VARCHAR(20))   BEGIN
    IF (SELECT COUNT(*) FROM books WHERE book_id = p_book_id) > 0 THEN

        UPDATE books
        SET
            book_name = p_book_name,
            author_id = p_author_id,
            cat_id = p_cat_id,
            ISBN = p_ISBN
        WHERE book_id = p_book_id;

        SELECT 'Book updated successfully' AS message;
    ELSE
        SELECT 'Error: Book not found' AS message;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `mobile` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `mobile`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin@1234', 1148458757);

-- --------------------------------------------------------

--
-- Table structure for table `audit_delete`
--

CREATE TABLE `audit_delete` (
  `audit_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `book_name` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `ISBN` varchar(20) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_delete`
--

INSERT INTO `audit_delete` (`audit_id`, `book_id`, `book_name`, `author_id`, `cat_id`, `ISBN`, `action_type`, `action_timestamp`, `action_description`) VALUES
(1, 6, 'Buko Pandan', 2, 3, '44111', 'DELETE', '2023-12-15 02:00:48', 'Book deleted'),
(2, 1, 'Superman', 1, 1, '5345431', 'DELETE', '2023-12-15 02:02:33', 'Book deleted'),
(3, 2, 'Alamat ng Pinya', 3, 2, '64561', 'DELETE', '2023-12-15 02:02:33', 'Book deleted'),
(4, 5, 'Alamat ng Saging', 1, 3, '51112', 'DELETE', '2023-12-15 02:02:33', 'Book deleted');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `author_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `author_name`) VALUES
(1, 'Joyce Calvez'),
(2, 'Kelly Ann Alinsub'),
(3, 'Brian Agraviador'),
(4, 'JB Locsin');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `ISBN` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `book_name`, `author_id`, `cat_id`, `ISBN`) VALUES
(1, 'Buko Pandan', 2, 3, '44111'),
(2, 'Experiment 101', 2, 1, '534334'),
(3, 'Spiderman', 2, 2, '54612'),
(4, 'Learn English in 1day', 4, 3, '3243421');

--
-- Triggers `books`
--
DELIMITER $$
CREATE TRIGGER `after_book_insert` AFTER INSERT ON `books` FOR EACH ROW BEGIN
    INSERT INTO insert_audit (book_id, book_name, author_id, cat_id, ISBN, action_type, action_description)
    VALUES (NEW.book_id, NEW.book_name, NEW.author_id, NEW.cat_id, NEW.ISBN, 'INSERT', 'New book added.');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_book_update` AFTER UPDATE ON `books` FOR EACH ROW BEGIN
    INSERT INTO update_audit (book_id, book_name, author_id, cat_id, ISBN, action_type, action_description)
    VALUES (NEW.book_id, NEW.book_name, NEW.author_id, NEW.cat_id, NEW.ISBN, 'UPDATE', 'Book information updated.');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `books_delete_trigger` BEFORE DELETE ON `books` FOR EACH ROW BEGIN
    INSERT INTO audit_delete (book_id, book_name, author_id, cat_id, ISBN, action_type, action_description)
    VALUES (OLD.book_id, OLD.book_name, OLD.author_id, OLD.cat_id, OLD.ISBN, 'DELETE', 'Book deleted');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `category_name`) VALUES
(1, 'Science'),
(2, 'Math'),
(3, 'Filipino'),
(4, 'English');

-- --------------------------------------------------------

--
-- Table structure for table `insert_audit`
--

CREATE TABLE `insert_audit` (
  `audit_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `book_name` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `ISBN` varchar(255) DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insert_audit`
--

INSERT INTO `insert_audit` (`audit_id`, `book_id`, `book_name`, `author_id`, `cat_id`, `ISBN`, `action_type`, `action_timestamp`, `action_description`) VALUES
(7, 1, 'Buko Pandan', 2, 3, '44111', 'INSERT', '2023-12-15 02:03:08', 'New book added.'),
(8, 2, 'Experiment 101', 2, 1, '534334', 'INSERT', '2023-12-15 02:03:16', 'New book added.'),
(9, 3, 'Alamat ng Pinya', 4, 1, '453511', 'INSERT', '2023-12-15 02:03:30', 'New book added.'),
(10, 4, 'Learn English in 1day', 4, 3, '3243421', 'INSERT', '2023-12-15 02:19:04', 'New book added.');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `s_no` int(11) NOT NULL,
  `ISBN` int(11) NOT NULL,
  `book_name` varchar(200) NOT NULL,
  `book_author` varchar(200) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `issue_date` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`s_no`, `ISBN`, `book_name`, `book_author`, `student_id`, `status`, `issue_date`) VALUES
(19, 64561, 'Juan Tamad', '-Select author-', 53453, 1, '2323-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `update_audit`
--

CREATE TABLE `update_audit` (
  `audit_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `book_name` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `ISBN` varchar(255) DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `update_audit`
--

INSERT INTO `update_audit` (`audit_id`, `book_id`, `book_name`, `author_id`, `cat_id`, `ISBN`, `action_type`, `action_timestamp`, `action_description`) VALUES
(4, 3, 'Spiderman', 2, 2, '54612', 'UPDATE', '2023-12-15 02:03:44', 'Book information updated.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mobile` int(10) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `mobile`, `address`) VALUES
(1, 'joyjoy', 'joyjoy@gmail.com', '123456789', 912311231, 'Dyan lang\r\n');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_books_author_category`
-- (See below for the actual view)
--
CREATE TABLE `view_books_author_category` (
`book_id` int(11)
,`book_name` varchar(255)
,`ISBN` varchar(13)
,`category_name` varchar(255)
,`author_id` int(11)
,`author_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `view_books_author_category`
--
DROP TABLE IF EXISTS `view_books_author_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_books_author_category`  AS SELECT `b`.`book_id` AS `book_id`, `b`.`book_name` AS `book_name`, `b`.`ISBN` AS `ISBN`, `c`.`category_name` AS `category_name`, `a`.`author_id` AS `author_id`, `a`.`author_name` AS `author_name` FROM ((`books` `b` join `authors` `a` on(`b`.`author_id` = `a`.`author_id`)) join `categories` `c` on(`b`.`cat_id` = `c`.`cat_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_delete`
--
ALTER TABLE `audit_delete`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `insert_audit`
--
ALTER TABLE `insert_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`s_no`);

--
-- Indexes for table `update_audit`
--
ALTER TABLE `update_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_delete`
--
ALTER TABLE `audit_delete`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `insert_audit`
--
ALTER TABLE `insert_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `s_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `update_audit`
--
ALTER TABLE `update_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `insert_audit`
--
ALTER TABLE `insert_audit`
  ADD CONSTRAINT `insert_audit_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `insert_audit_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `insert_audit_ibfk_3` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `update_audit`
--
ALTER TABLE `update_audit`
  ADD CONSTRAINT `update_audit_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `update_audit_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `update_audit_ibfk_3` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_old_books` ON SCHEDULE EVERY 1 DAY STARTS '2023-12-11 01:04:43' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM books WHERE action_timestamp < NOW() - INTERVAL 150 DAY;

    INSERT INTO audit_delete (book_id, book_name, author_id, cat_id, ISBN, action_type, action_timestamp, action_description)
    SELECT book_id, book_name, author_id, cat_id, ISBN, 'DELETE', NOW(), 'Book automatically deleted after 150 days'
    FROM books
    WHERE action_timestamp < NOW() - INTERVAL 150 DAY;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
