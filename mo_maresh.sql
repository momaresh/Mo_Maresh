-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2023 at 04:03 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mo_maresh`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `language` varchar(100) NOT NULL,
  `size` decimal(10,2) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `author` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `language`, `size`, `pages`, `author`) VALUES
(100, 'English', '3.64', 133, 'Mikael Krogerus, Roman Tschäppeler'),
(101, 'English', '1.42', 252, ' Michael Thomas Ford '),
(102, 'English', '2.29', 446, 'Thomas S. Kane'),
(103, 'English', '7.71', 4182, 'J.K. Rowling'),
(104, 'English', '0.40', 124, 'Daniel Spade'),
(105, 'English', '0.30', 400, 'Charles Duhigg'),
(106, '', '0.00', 30, 'Little Tiger Press, Tim Warnes'),
(107, 'English', '0.62', 288, 'Mark Manson '),
(108, 'Arabic', '3.57', 192, 'علي بن جابر الفيفي'),
(109, 'Arabic', '2.34', 187, 'Paulo Coelho, باولو كويلو, جواد صيداوي'),
(110, 'Arabic', '0.42', 272, 'Mark Manson, مارك مانسون, الحارث النبهان'),
(111, 'Arabic', '7.36', 530, 'روبرت تي كيوساكي'),
(112, 'English', '0.25', 321, 'Eckhart Tolle'),
(113, 'English', '0.10', 75, 'Arthur Horn'),
(114, 'English', '1.69', 320, 'Neil Pasricha'),
(115, 'English', '3.50', 533, 'Daniel Kahneman'),
(116, 'English', '1.69', 288, 'Ichiro Kishimi, Fumitake Koga'),
(117, 'English', '0.85', 698, ''),
(120, 'Arabic', '35.53', 267, 'أحمد الشقيري');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `card_id` int(11) NOT NULL,
  `number` varchar(20) NOT NULL,
  `holder` varchar(100) NOT NULL,
  `pin` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `expire` date NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`card_id`, `number`, `holder`, `pin`, `type`, `expire`, `amount`) VALUES
(1, '1111 2222 3333 4444', 'Mohammed Maresh', 'yemen2003', 'Visa', '2025-01-22', '5000'),
(2, '1111 1111 1111 1111', 'Ali', '1234', 'Visa', '2023-01-09', '7342'),
(4, '1111 1111 2222 2222', 'Mohammed Maresh', '12345678', 'PayPal', '2025-07-16', '2500');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `book_id` int(11) NOT NULL,
  `category_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`book_id`, `category_name`) VALUES
(100, 'Lifestyle'),
(100, 'Relationships'),
(100, 'Self-Help'),
(101, 'Fiction'),
(101, 'Humour'),
(102, ' Reference'),
(102, 'Writing'),
(103, 'Fantasy'),
(103, 'Fiction'),
(103, 'Science '),
(104, 'Lifestyle'),
(104, 'Relationships'),
(104, 'Self-Help'),
(105, 'Business'),
(105, 'Economics'),
(105, 'Responsibilit'),
(106, 'Children\'s '),
(106, 'Literature'),
(107, 'Lifestyle'),
(107, 'Relationships'),
(107, 'Self-Help'),
(108, 'Islam'),
(108, 'Religion'),
(109, 'Lifestyle'),
(110, 'Relationships'),
(110, 'Self-Help'),
(111, 'Business'),
(111, 'Economics'),
(112, 'Religion'),
(112, 'Spirituality'),
(113, 'Lifestyle'),
(113, 'Relationships'),
(113, 'Self-Help'),
(114, 'Business '),
(114, 'Economics '),
(114, 'Responsibility'),
(115, 'Psychology'),
(116, 'Lifestyle'),
(116, 'Relationships'),
(116, 'Self-Help'),
(117, 'Lifestyle'),
(117, 'Relationships'),
(117, 'Self-Help'),
(120, 'Personal Growth'),
(120, 'Relationships'),
(120, 'Self-Help');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rate` char(1) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `prod_id`, `text`, `rate`, `date`) VALUES
(1, 100, 100, 'The book is so nice', '5', '2023-01-19'),
(5, 111, 108, 'I have read this book many time and i don not feel bord it\'s so cool.', '5', '2023-01-10'),
(6, 128, 100, 'I reccomend reading this book', '3', '2023-01-19'),
(7, 111, 104, 'This book is so cool', '5', '2023-01-19'),
(8, 111, 104, 'I reccomend reading this book', '2', '2023-01-19'),
(9, 111, 107, 'I have read this book many time and i don not feel bord it\'s so cool.', '4', '2023-01-19'),
(11, 111, 117, 'Hi I am trying this code only second time', '5', '2023-01-20'),
(30, 111, 116, 'after try', '5', '2023-02-09'),
(33, 111, 115, 'try anothor also', '1', '2023-02-09');

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE `computers` (
  `computer_id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `os` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `ram_size` int(11) DEFAULT NULL,
  `screen_size` decimal(5,2) DEFAULT NULL,
  `storage_size` int(11) DEFAULT NULL,
  `storage_type` varchar(10) DEFAULT NULL,
  `graphic_brand` varchar(100) DEFAULT NULL,
  `graphic_size` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`computer_id`, `brand`, `os`, `color`, `ram_size`, `screen_size`, `storage_size`, `storage_type`, `graphic_brand`, `graphic_size`) VALUES
(119, 'ASUS', 'Windows 11 Home', 'Bonfire Black', 8, '15.60', 512, 'SSD', 'NVIDIA GeForce GTX 1650', 4),
(125, 'Acer', ' Windows 11 Home', 'Black', 8, '17.30', 1000, 'SSD', 'NVIDIA', 4),
(126, 'Lenovo', ' Windows 11 Pro', ' Arctic Grey', 8, '15.60', 512, 'SSD', 'Intel Iris', 2),
(128, 'ASUS', ' Windows 11 Home', 'Bonfire Black', 8, '15.60', 512, 'SSD', ' NVIDIA GeForce GTX 1650', 0),
(129, 'ASUS', ' Windows 11 Home', 'Off Black', 16, '15.60', 1000, 'HDD', 'NVIDIA GeForce RTX 3070 Ti', 0);

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `user_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`user_id`, `prod_id`, `date`) VALUES
(100, 119, '2023-01-26'),
(100, 120, '2023-01-26'),
(100, 126, '2023-01-27'),
(100, 128, '2023-01-26'),
(108, 128, '2023-02-07'),
(109, 114, '2023-01-20'),
(111, 100, '2023-03-06'),
(111, 114, '2023-01-25'),
(111, 119, '2023-01-25'),
(111, 128, '2023-03-06'),
(128, 100, '2023-01-19'),
(128, 101, '2023-01-17'),
(128, 104, '2023-01-19'),
(128, 107, '2023-01-19'),
(128, 109, '2023-01-19'),
(128, 116, '2023-01-19'),
(128, 117, '2023-01-01'),
(132, 128, '2023-01-29'),
(132, 129, '2023-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `loc_id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`loc_id`, `country`, `city`, `street`) VALUES
(1, 'Yemen', 'Taiz', 'Al-Tahreer'),
(2, 'Yemen', 'Sana\'a', 'Al-Matar'),
(3, 'Yemen', 'Sana\'a', 'Hada'),
(4, 'Yemen', 'Sana\'a', 'Tahreer');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) DEFAULT NULL,
  `loc_id` int(11) DEFAULT NULL,
  `ship_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `ship_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ordered',
  `total_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `card_id`, `loc_id`, `ship_id`, `order_date`, `ship_date`, `status`, `total_price`) VALUES
(2, 111, 2, 2, 1, '2023-01-21', '2023-01-29', 'buyed', '121.19'),
(3, 100, 2, 1, 1, '2023-01-26', '2023-01-27', 'buyed', '438.38'),
(4, 100, 2, 1, 1, '2023-01-27', '2023-01-27', 'buyed', '6597.00'),
(5, 100, 2, 2, 1, '2023-01-27', '2023-01-27', 'buyed', '1220.88'),
(13, 134, 2, 1, 1, '2023-02-01', '2023-02-01', 'buyed', '909.19'),
(15, 111, 2, 1, 1, '2023-02-07', '2023-02-08', 'buyed', '1034.32'),
(19, 111, 2, 2, 1, '2023-02-09', '2023-02-09', 'buyed', '69.79'),
(20, 111, 2, 1, 1, '2023-02-09', '2023-02-20', 'buyed', '2219.89'),
(25, 111, NULL, NULL, NULL, NULL, NULL, 'buyed', '134.42'),
(26, 134, 2, 1, 1, NULL, NULL, 'ordered', '71.97'),
(27, 111, 2, 1, 1, NULL, '2023-03-06', 'buyed', '438.38');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `quantity` int(10) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `add_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `prod_id`, `quantity`, `total_price`, `add_date`) VALUES
(2, 100, 2, '53.98', '2023-02-20 20:11:34'),
(2, 114, 1, '67.21', '2023-02-20 20:11:34'),
(3, 128, 2, '438.38', '2023-02-20 20:11:34'),
(4, 129, 3, '6597.00', '2023-02-20 20:11:34'),
(5, 120, 1, '20.89', '2023-02-20 20:11:34'),
(5, 125, 1, '1199.99', '2023-02-20 20:11:34'),
(13, 119, 1, '690.00', '2023-02-20 20:11:34'),
(13, 128, 1, '219.19', '2023-02-20 20:11:34'),
(15, 114, 1, '67.21', '2023-02-20 20:11:34'),
(15, 115, 1, '27.12', '2023-02-20 20:11:34'),
(15, 126, 1, '939.99', '2023-02-20 20:11:34'),
(19, 115, 1, '27.12', '2023-02-20 20:11:34'),
(19, 116, 1, '21.78', '2023-02-20 20:11:34'),
(19, 120, 1, '20.89', '2023-02-20 20:11:34'),
(20, 120, 2, '41.78', '2023-02-20 20:11:34'),
(20, 129, 1, '2199.00', '2023-02-20 20:11:34'),
(25, 114, 2, '134.42', '2023-03-01 10:13:59'),
(26, 108, 2, '51.08', '2023-03-05 22:38:48'),
(26, 120, 1, '20.89', '2023-03-05 21:47:16'),
(27, 128, 2, '438.38', '2023-03-06 12:15:23');

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `total_price` AFTER INSERT ON `order_items` FOR EACH ROW UPDATE ORDERS SET total_price = total_price + NEW.total_price
WHERE order_id = NEW.order_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `total_price_delete` AFTER DELETE ON `order_items` FOR EACH ROW UPDATE ORDERS SET total_price = total_price - OLD.total_price
WHERE order_id = OLD.order_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prod_id` int(11) NOT NULL,
  `prod_name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sup_id` int(11) NOT NULL DEFAULT 100,
  `sup_date` date NOT NULL DEFAULT current_timestamp(),
  `desc1` text DEFAULT NULL,
  `desc2` text DEFAULT NULL,
  `desc3` text DEFAULT NULL,
  `buying` int(11) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'book'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_id`, `prod_name`, `price`, `sup_id`, `sup_date`, `desc1`, `desc2`, `desc3`, `buying`, `type`) VALUES
(100, 'The Question Book - What Makes You Tick?', '26.99', 136, '2023-01-20', 'The number one bestselling Decision Book authors return with compulsive questions about every aspect of our lives.', 'What would be your ideal job if you didn\'t have to worry about money? Would you like to have more responsibility or less? How far would you go for a promotion? When did you last stand up for what you believe in? What are you afraid of? In this unique handbook to your own life and work, there are no right or wrong answers: only honest ones', 'Featuring sections on subjects everyone can relate to, from the professional (work and finance), to the personal (sex and relationships), The Question Book can be used alone, like a journal; or with a colleague, partner or friend. It will probe and enlighten on everything, including what your boss really thinks about you, whether you are in the right job, and what motivates you to get out of bed every morning. These wide-ranging questions - which provoke short \'yes or no\'s as well as open-ended responses that dig deeper - are pertinent, direct, and compulsively fun to answer. In The Question Book, you are under the spotlight. And only you have the answer.', 4, 'book'),
(101, ' Suicide Notes', '28.09', 136, '2023-01-20', 'I\'m not crazy. I don\'t see what the big deal is about what happened. But apparently someone does think it\'s a big deal because here I am. I bet it was my mother. She always overreacts. ', 'Fifteen-year-old Jeff wakes up on New Year\'s Day to find himself in the hospital. Make that the psychiatric ward. With the nutjobs. Clearly, this is all a huge mistake. Forget about the bandages on his wrists and the notes on his chart. Forget about his problems with his best friend, Allie, and her boyfriend, Burke. Jeff\'s perfectly fine, perfectly normal, not like the other kids in the hospital with him. Now they\'ve got problems. But a funny thing happens as his forty-five-day sentence drags on: the crazies start to seem less crazy. ', NULL, 0, 'book'),
(102, 'The Oxford Essential Guide to Writing', '30.31', 136, '2023-01-20', 'A Concise Course in the Art of Writing Whether you\'re composing a letter, writing a school thesis, or starting a novel, this resource offers expert advice on how to think more creatively, how to conjure up ideas from scratch, and how to express those ideas clearly and elegantly. No matter where you find yourself in the writing process--from the daunting look of a blank page, to the rough draft that needs shaping, to the small but important questions of punctuation--you\'ll find what you need in t', NULL, NULL, 0, 'book'),
(103, 'Harry Potter: The Complete Collection (1-7)', '69.99', 136, '2023-01-20', 'All seven eBooks in the multi-award winning, internationally bestselling Harry Potter series, available as one download with stunning cover art by Olly Moss. Enjoy the stories that have captured the imagination of millions worldwide. Having now become classics of our time, the Harry Potter ebooks never fail to bring comfort and escapism to readers of all ages. With its message of hope, belonging and the enduring power of truth and love, the story of the Boy Who Lived continues to delight generat', NULL, NULL, 0, 'book'),
(104, 'How To Analyze People: 13 Laws', '20.99', 136, '2023-01-20', '* 13 rules to adapt your consuct to the shapes of different personalities and consequently how to influence them. ', 'Have you ever felt awkward because you can\'t catch the signals that your partner is trying to send you? * Would you like to read people by their unspoken behavior?* * Do you wish you could figure out if someone is lying to you?* * Do you want to get anybody to do anything you want?* * Are you a manipulator or are you being manipulated?* ', NULL, 0, 'book'),
(105, 'The Power of Habit: Why We Do What We Do in Life and Business', '69.99', 136, '2023-01-20', NULL, 'A young woman walks into a laboratory. Over the past two years, she has transformed almost every aspect of her life. She has quit smoking, run a marathon, and been promoted at work. The patterns inside her brain, neurologists discover, have fundamentally changed. ', 'Marketers at Procter & Gamble study videos of people making their beds. They are desperately trying to figure out how to sell a new product called Febreze, on track to be one of the biggest flops in company history. Suddenly, one of them detects a nearly imperceptible pattern—and with a slight shift in advertising, Febreze goes on to earn a billion dollars a year. ', 0, 'book'),
(106, 'I Love You to the Moon and Back', '5.90', 136, '2023-01-20', 'I\'m not crazy. I don\'t see what the big deal is about what happened. But apparently someone does think it\'s a big deal because here I am. I bet it was my mother. She always overreacts. ', NULL, 'When the sun comes up, Big Bear and Little Bear think of new ways to share their love. Big Bear loves Little Bear more and more as each day passes, right up to each new moon – and back. A joyful celebration of the love between parent and child, this lovely chunky board book is perfect for reading with your special little person. With sturdy pages that are easy for little hands to turn and beautiful illustrations by Tim Warnes, I Love You to the Moon and Back will soon become a firm bedtime favourite. ', 0, 'book'),
(107, ' Everything Is F*cked: A Book About Hope', '30.45', 136, '2023-01-20', 'From the author of the international mega-bestseller The Subtle Art of Not Giving A F*ck comes a counterintuitive guide to the problems of hope. ', 'We live in an interesting time. Materially, everything is the best it’s ever been—we are freer, healthier and wealthier than any people in human history. Yet, somehow everything seems to be irreparably and horribly f*cked—the planet is warming, governments are failing, economies are collapsing, and everyone is perpetually offended on Twitter. At this moment in history, when we have access to technology, education and communication our ancestors couldn’t even dream of, so many of us come back to an overriding feeling of hopelessness. ', NULL, 0, 'book'),
(108, 'لأنك الله: رحلة إلى السماء السابعة', '25.54', 136, '2023-01-20', NULL, 'هذه كلمات عن بعض أسماء الله كتبتها بضعفي عن القوي سبحانه وبعجزي عن القدير سبحانه وبجهلي عن العليم سبحانه.. حرصت أن أجعلها مما بفهمه متوسط الثقافة ويستطيع قراءته المريض على سرسره والحزين بين دموعه والمحتاج وسط كروبه. أردت من هذا الكتاب الدلاله على الله سبحانه والإشارة إليه بالقليل مما لديه وتذكير نفسي وإخواني بأنه على كل شيء قدير وأن فضله كبير وأنه سبحانه السميع البصير.. لا أدعي في هذا الكتاب إحاطة ولا علماً ولاسبقاُ الذي أدعيه هو العحز والتقصير والافتقار إلى عفوه سبحانه وتعالى فإن كان في هذا الكتاب من خير فأسأل الله أن يعيشه بين الناس وإن كان غير ذلك فقد علم سبحانه كل التقصير الذي عندي وقد علمت بعض العفو الذي عنده. ', NULL, 1, 'book'),
(109, 'الخيميائي', '20.76', 136, '2023-01-20', NULL, NULL, NULL, 5, 'book'),
(110, ' فن اللامبالاة: لعيش حياة تخالف المألوف', '15.32', 136, '2023-01-20', 'ظل يُقال لنا طيلة عشرات السنوات إن التفكير الإيجابي هو المفتاح إلى حياة سعيدة ثرية. لكن مارك مانسون يشتم تلك \"الإيجابية\" ويقول: \" فلنكن صادقين، السيء سيء وعلينا أن نتعايش مع هذا \". لا يتهرّب مانسون من الحقائق ولا يغلفها بالسكّر، بل يقولها لنا كما هي: جرعة من الحقيقة الفجِّة الصادقة المنعشة هي ما ينقصنا اليوم. هذا الكتاب ترياق للذهنية التي نهدهد أنفسنا بها، ذهنية \" فلنعمل على أن يكون لدينا كلنا شعور طيب \" التي غزت المجتمع المعاصر فأفسدت جيلًا بأسره صار ينال ميداليات ذهبية لمجرد الحضور إلى المدرسة', NULL, 'ينصحنا مانسون بأن نعرف حدود إمكاناتنا وأن نتقبلها. وأن ندرك مخاوفنا ونواقصنا وما لسنا واثقين منه، وأن نكفّ عن التهرب والفرار من ذلك كله ونبدأ مواجهة الحقائق الموجعة، حتى نصير قادرين على العثور على ما نبحث عنه من جرأة ومثابرة وصدق ومسؤولية وتسامح وحب للمعرفة. لا يستطيع كل شخص أن يكون متميزًا متفوقًا. ففي المجتمع ناجحين وفاشلين؛ وقسم من هذا الواقع ليس عادلًا وليس نتيجة غلطتك أنت. وصحيح أن المال شيء حسن، لكن اهتمامك بما تفعله بحياتك أحسن كثيرًا؛ فالتجربة هي الثروة الحقيقية . ', 0, 'book'),
(111, ' الاب الغني والأب الفقير', '20.17', 136, '2023-01-20', NULL, NULL, NULL, 0, 'book'),
(112, 'The Power of Now: A Guide to Spiritual Enlightenment', '32.23', 136, '2023-01-20', 'A word of mouth phenomenon since its first publication, The Power of Now is one of those rare books with the power to create an experience in readers, one that can radically change their lives for the better. ', NULL, 'To make the journey into the Now we will need to leave our analytical mind and its false created self, the ego, behind. From the very first page of Eckhart Tolle\'s extraordinary book, we move rapidly into a significantly higher altitude where we breathe a lighter air. We become connected to the indestructible essence of our Being, “The eternal, ever present One Life beyond the myriad forms of life that are subject to birth and death.” Although the journey is challenging, Eckhart Tolle uses simple language and an easy question and answer format to guide us. ', 0, 'book'),
(113, ' Manipulation Dark Psychology to Manipulate and Control People', '8.27', 136, '2023-01-20', NULL, 'Step-by-step instructional guide to manipulate people using dark psychology Dark Psychology can be an incredibly powerful method for mind control, brainwashing, influencing, and manipulating those around you, but only if you know how to do it right! Need to learn how to manipulate someone fast? With this guide you will be armed with the fundamental knowledge you need to apply the manipulative power of dark psychology in your personal and professional life.Here is a preview of what you will learn in this guide:\r\n', NULL, 0, 'book'),
(114, 'The Happiness Equation: Want Nothing + Do Anything = Have Everything', '67.21', 136, '2023-01-20', 'What’s the formula for a happy life? ', 'Neil Pasricha is a Harvard MBA, a Walmart executive, a New York Times–bestselling author, and a husband and dad. After selling more than a million copies of his Book of Awesome series, he now shifts his focus from observation to application. ', 'In The Happiness Equation, Pasricha illustrates how to want nothing, do anything, and have everything. If that sounds like a contradiction, you simply haven’t unlocked the 9 Secrets to Happiness. ', 1, 'book'),
(115, 'Thinking, Fast and Slow', '27.12', 136, '2023-01-20', NULL, 'In the international bestseller, Thinking, Fast and Slow, Daniel Kahneman, the renowned psychologist and winner of the Nobel Prize in Economics, takes us on a groundbreaking tour of the mind and explains the two systems that drive the way we think. System 1 is fast, intuitive, and emotional; System 2 is slower, more deliberative, and more logical. The impact of overconfidence on corporate strategies, the difficulties of predicting what will make us happy in the future, the profound effect of cognitive biases on everything from playing the stock market to planning our next vacation—each of these can be understood only by knowing how the two systems shape our judgments and decisions.', NULL, 0, 'book'),
(116, 'The Courage to Be Disliked`', '21.78', 136, '2023-02-03', '', '“Marie Kondo, but for your brain.” —HelloGiggles “Compelling from front to back. Highly recommend.” —Marc Andreessen Reading this book could change your life. The Courage to Be Disliked, already an enormous bestseller in Asia with more than 3.5 million copies sold, demonstrates how to unlock the power within yourself to be the person you truly want to be. Is happiness something you choose for yourself? The Courage to Be Disliked presents a simple and straightforward answer. Using the theories of Alfred Adler, one of the three giants of nineteenth-century psychology alongside Freud and Jung, this book follows an illuminating dialogue between a philosopher and a young man. Over the course of five conversations, the philosopher helps his student to understand how each of us is able to determine the direction of our own life, free from the shackles of past traumas and the expectations of others. ', 'Rich in wisdom, The Courage to Be Disliked will guide you through the concepts of self-forgiveness, self-care, and mind decluttering. It is a deeply liberating way of thinking, allowing you to develop the courage to change and ignore the limitations that you might be placing on yourself. This plainspoken and profoundly moving book unlocks the power within you to find lasting happiness and be the person you truly want to be. Millions have already benefited from its teachings, now you can too. ', 0, 'book'),
(117, '365 Days with Self-Discipline', '67.87', 136, '2023-01-20', NULL, 'How to Build Self-Discipline and Become More Successful (365 Powerful Thoughts From the World\'s Brightest Minds) Its lack makes you unable to achieve your goals. Without it, you\'ll struggle to lose weight, become fit, wake up early, work productively and save money. Not embracing it in your everyday life means that you\'ll never realize your full potential. Ignoring it inevitably leads to regret and feeling sad about how more successful and incredible your life could have been if you had only decided to develop it. What is this powerful thing? Self-discipline. And if there\'s one thing that self-discipline is not, it\'s instant. It takes months (if not years) to develop powerful self-control that will protect you from impulsive decisions, laziness, procrastination, and inaction. You need to exhibit self-discipline day in, day out, 365 days in a year. What if you had a companion who would remind you daily to stay disciplined and persevere, even when the going gets tough? 365 Days With Self', NULL, 1, 'book'),
(119, 'ASUS TUF Gaming F15 Gaming Laptop', '690.00', 137, '2023-01-20', 'SUPERCHARGED GTX GRAPHICS - Gameplay graphics are silky smooth with the NVIDIA GeForce GTX 1650 4GB GDDR6 so you can stay immersed in the game even in the most graphically intensive moments', 'READY FOR ANYTHING - Use your gaming laptop to stream and multitask with ease thanks to an Intel Core i5-10300H with 8M Cache, up to 4.5 GHz, 4 cores and 8GB of blisteringly fast 2933MHz DDR4 RAM on Windows 11', 'SWIFT VISUALS – Stay one step ahead of the competition thanks to its 144Hz 15.6” Full HD (1920 x 1080) IPS Type Display', 0, 'computer'),
(120, ' 40 أربعون', '20.89', 136, '2023-02-01', 'ﺃﻟﻔﺖ ﻫﺬﺍ ﺍﻟﻜﺘﺎﺏ ﻓﻲ ﺃﺛﻨﺎﺀ ﺧﻠﻮﺓ ﻣﺪﺗﻬﺎ ﺃﺭﺑﻌﻮﻥ ﻳﻮماً، ﺣﻴﺚ ﺍﻋﺘﺰﻟﺖ ﺍﻟﻨﺎﺱ ﻭﺍﻟﺘﻜﻨﻮﻟﻮﺟﻴﺎ، ﻭﺟﻠﺴﺖ ﻓﻲ ﺟﺰﻳﺮﺓ ﻧﺎﺋﻴﺔ ﻣﻊ ﻧﻔﺴﻲ ﺃﺣﺎﻭﻝ ﺃﻥ ﺃﺗﻔﻜﺮ ﻓﻲ ﻣﺎ ﻓﺎﺕ، ﻭﺃﺗﺄﻣﻞ ﻓﻲ ﻣﺎ ﻫﻮ ﺁﺕٍ، ﻓﺎﻧﺘﻬﻴﺖ ﺑﺄﺭﺑﻌﻴﻦ ﺧﺎﻃﺮﺓ ﻓﻲ ﻛﻞ ﻣﺤﻮﺭ ﻣﻦ ﺍﻟﻤﺤﺎﻭﺭ ﺃﺩﻧﺎﻩ:', '-٤٠ يوماً مع حياتي.\r\n-٤٠ يوماً مع قرآني.\r\n-٤٠ يوماً مع نفسي.\r\n-٤٠ يوماً مع تحسيناتي.\r\n-٤٠ يوماً مع قصصي.\r\n-٤٠ يوماً مع إلهي.\r\n-٤٠ يوماً مع كتبي.', '', 4, 'book'),
(125, ' Acer Nitro 5 AN517-54-79L1 Gaming Laptop | Intel Core i7-11800H | NVIDIA GeForce RTX 3050Ti Laptop GPU | 17.3\" FHD 144Hz IPS Display | 16GB DDR4 | 1TB NVMe SSD | Killer Wi-Fi 6 | Backlit KB | Win 11', '1199.99', 137, '2023-01-20', 'Aspect Ratio:16:9.Connectivity Technology: Wi-Fi, Bluetooth\r\nDominate the Game: Great performance meets long battery life with the Intel Core i7-11800H Processor - up to 4.6GHz, 8 cores, 16 threads, 24MB Intel Smart Cache', 'RTX, It\'s On: The latest NVIDIA GeForce RTX 3050 Ti (4GB dedicated GDDR6 VRAM) is powered by award-winning architecture with new Ray Tracing Cores, Tensor Cores, and streaming multiprocessors support DirectX 12 Ultimate for the ultimate gaming performance', 'Visual Intensity: Explore game worlds in Full HD detail on the 17.3\" widescreen LED-backlit IPS display with 1920 x 1080 resolution, 144Hz refresh rate and 80% screen-to-body/16:9 aspect ratio', 1, 'computer'),
(126, 'Lenovo - 2022 - IdeaPad 3i - Essential Laptop Computer - Intel Core i5 12th Gen - 15.6\" FHD Display - 8GB Memory - 512GB Storage - Windows 11 Pro', '939.99', 137, '2023-01-25', 'The slim and light Lenovo IdeaPad 3i laptop packs powerful 12th generation Intel Core i5 U series processors and Intel Iris Xe graphics card\r\nAt an exceptionally slim 19.9 mm (just 0.78 inch), the IdeaPad 3i is lightweight and easy to carry everywhere you travel', 'The 15.6\" FHD IPS display gives you wider viewing angles for a better experience with higher color accuracy and contrast so you can see more and do more daily; the screen\'s 4-sided narrow bezels give you more available viewing screen', 'Keep your desk from cluttering with a full-function Type-C port for faster data transfer, power delivery, and 4K display connectivity; USB 3.2 Gen 1 Type-A, USB 2.0 Type-A, and HDMI ports also included, includes Free 3-month Xbox Game Pass', 0, 'computer'),
(128, 'ASUS Chromebook Flip C434 2-In-1 Laptop, 14', '219.19', 137, '2023-01-26', '14 inch Touchscreen FHD 1920x1080 4-way NanoEdge display featuring ultra-narrow bezels (5mm thin) around each side of the display that allows for a 14 inch screen to fit in the body of a 13inch laptop footprint.', 'The FHD display has a durable 360 degree hinge that can be used to flip the touchscreen display to tent, stand, and tablet mode.', 'Powered by the Intel Core m3-8100Y Processor (up to 3.4 GHz) for super-fast and snappy performance. If you use a ton of tabs or run lots of apps, this has the power to get it all done with\r\n4GB DDR3 RAM; 64GB eMMC storage and 2x USB Type-C (Gen 1) and 1s Type-A (Gen 1) ports plus a super bright backlit keyboard.', 8, 'computer'),
(129, 'ASUS ROG Strix Scar 15 (2022) Gaming Laptop, 15.6” 300Hz IPS FHD Display, NVIDIA GeForce RTX 3070 Ti,Intel Core i9 12900H, 16GB DDR5, 1TB SSD, Per-Key RGB Keyboard, Windows 11 Home, G533ZW-AS94', '2199.00', 137, '2023-01-26', 'PEERLESS PROCESSING POWER - Speed through laptop gaming, streaming, and creating with the 12th Gen Intel Core i9-12900H Processor (6 P-cores and 8 E-cores) with 24M Cache and up to 5.0GHz clock speed.Aspect Ratio:16:9', 'RTX REALISM. IT’S ON - This Windows 11 gaming laptop is equipped with the GeForce RTX 3070 Ti laptop GPU with a max TGP of 150W and ROG Boost up to 1460MHz, to give you the most realistic ray-traced graphics and cutting-edge AI features like NVIDIA DLSS.', 'ROG INTELLIGENT COOLING - The SCAR 15 takes cooling up to a notch, adding premium Thermal Grizzly liquid metal, Arc Flow fans, and 0dB Ambient Cooling', 2, 'computer');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `prod_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`prod_id`, `url`) VALUES
(100, 'book0.jpg'),
(101, 'book1.jpg'),
(102, 'book2.jpg'),
(103, 'book3.jpg'),
(104, 'book4.jpg'),
(105, 'book5.jpg'),
(106, 'book6.jpg'),
(107, 'book7.jpg'),
(108, 'book8.jpg'),
(109, 'book9.jpg'),
(110, 'book10.jpg'),
(111, 'book11.jpg'),
(112, 'book12.jpg'),
(113, 'book13.jpg'),
(114, 'book14.jpg'),
(115, 'book15.jpg'),
(116, 'book16.jpg'),
(117, 'book17.jpg'),
(119, '435488_computer3.jpg'),
(119, 'back.jpg'),
(119, 'computer2-1.jpg'),
(119, 'computer2-2.jpg'),
(119, 'computer2-3.jpg'),
(119, 'computer2-4.jpg'),
(119, 'computer2.jpg'),
(120, 'book22.jpg'),
(125, 'computer3.jpg'),
(126, 'computer1-1.jpg'),
(126, 'computer1-2.jpg'),
(126, 'computer1-3.jpg'),
(126, 'computer1-4.jpg'),
(126, 'computer1.jpg'),
(128, 'computer7-1.jpg'),
(128, 'computer7-2.jpg'),
(128, 'computer7-3.jpg'),
(128, 'computer7-4.jpg'),
(128, 'computer7.jpg'),
(129, 'computer6-1.jpg'),
(129, 'computer6-2.jpg'),
(129, 'computer6-3.jpg'),
(129, 'computer6-4.jpg'),
(129, 'computer6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `roll`
--

CREATE TABLE `roll` (
  `roll_id` int(11) NOT NULL,
  `roll_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roll`
--

INSERT INTO `roll` (`roll_id`, `roll_type`) VALUES
(1, 'admin'),
(2, 'user'),
(3, 'supplier');

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE `shippers` (
  `ship_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shippers`
--

INSERT INTO `shippers` (`ship_id`, `name`, `email`, `phone`) VALUES
(1, 'Al-Saaidah', 'alsaaida@gmail.com', '01234323');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(192) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `group_id` int(10) NOT NULL DEFAULT 2,
  `reg_status` int(10) NOT NULL DEFAULT 0,
  `birth_date` date DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `last_update` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password`, `email`, `full_name`, `group_id`, `reg_status`, `birth_date`, `nationality`, `image`, `date`, `last_update`) VALUES
(100, 'mo_maresh', 'f133e870abae1f12a9edb414a086cb86', 'maresh@gmail.com', 'Mohammed Maresh', 1, 1, '2003-02-21', 'Yemeni', 'img3.jpg', NULL, '2023-03-06'),
(104, 'M.ALharbi', 'alharbi2003', 'alharbi@gmail.com', 'Mohammed Alharbi', 2, 1, '2003-01-02', 'Yemeni', '', NULL, '2023-02-03'),
(107, 'Hamoud', 'mahmoud2003', 'mahmoud@gmail.com', 'Mahmuod Maresh', 2, 1, '2004-03-04', 'Yemeni', 'img.png', NULL, NULL),
(108, 'Karoom', 'akram2003', 'akram@gmail.com', 'Akram', 2, 1, '0000-00-00', '', '899182_face.jpg', '2023-01-06', '2023-02-08'),
(109, 'Malik', 'malik2003', 'malik@gmail.com', 'Malik', 2, 1, '2001-10-26', 'Yemeni', 'img.png', '2023-01-07', NULL),
(111, 'maresh', 'cf8448efffc5e45685441a55a651a699', 'maresh.mohammed18@gmail.com', 'Mohammed Maresh', 2, 1, '2003-02-21', 'Yemeni', '417785_img2.jpg', '2023-01-07', '2023-03-06'),
(124, 'moneeb', 'moneeb2003', 'moneeb@gmail.com', 'Moneeb Maresh', 2, 1, '0000-00-00', '', 'img.png', '2023-01-08', NULL),
(125, 'Hithem Desgin', '7356hrsm', '77xxxytham4444@gmainl.com', 'Hithem Al-Hafity', 2, 1, '1992-07-01', 'Yemeni', 'img.png', '2023-01-13', '2023-01-13'),
(126, 'wesam', 'wesam2003', 'wesam@gmail.com', 'Wesam Al-Qadasi', 2, 1, '1998-07-14', 'Yemeni', 'img.png', '2023-01-17', NULL),
(127, 'mohammed', 'mohammed2003', 'almauri@gmail.com', 'Mohammed Al-Mauri', 2, 1, '1997-02-07', 'Yemeni', 'img.png', '2023-01-17', NULL),
(128, 'ali19', 'ali192003', 'ali@gmail.com', 'Ali Dabbash', 2, 1, '0000-00-00', '', 'img.png', '2023-01-18', NULL),
(132, 'Dadi', 'dadi2003', 'dad@gmail.com', 'My Father', 2, 1, '0000-00-00', 'Yemeni', 'img.png', '2023-01-28', NULL),
(133, 'foud', 'foud2003', 'Fuad@gmail.com', 'Fuad Al-Haidari', 2, 1, '0000-00-00', '', '738304_fuad.jpg', '2023-01-30', NULL),
(134, 'ahmed', 'ahmed2003', 'al-haboob@gmail.com', 'Ahmed Al-Haboob', 2, 1, '0000-00-00', '', '938180_ahmed.jpg', '2023-01-30', '2023-01-30'),
(135, 'naser', 'naser2003', 'naser@gmail.com', 'Naser Sailan', 2, 1, '2003-12-02', 'Yemeni', 'IMG_20220515_132101_Bokeh-1.jpg', '2023-02-01', '2023-02-03'),
(136, 'profile_books', 'profile2003', 'profilebooks@gmail.com', 'Profile Books', 3, 1, NULL, NULL, 'img.png', '2023-02-02', NULL),
(137, 'microsoft', 'microsoft2003', 'microsoft@gmail.com', 'Microsoft', 3, 1, NULL, NULL, 'img.png', '2023-02-02', NULL),
(139, 'Abood', 'abdullah2003', 'abdullah@gmail.com', 'Abdallah', 2, 0, '2008-03-15', 'Egeption', 'img.png', '2023-03-06', NULL),
(140, 'eeeeee', '25d55ad283aa400af464', 'mohammeeeed.marseh18@gmail.com', 'eeeee', 2, 0, '0000-00-00', '', NULL, '2023-03-06', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`card_id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`book_id`,`category_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_user_fk` (`user_id`),
  ADD KEY `comment_prod_fk` (`prod_id`);

--
-- Indexes for table `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`computer_id`);

--
-- Indexes for table `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`user_id`,`prod_id`),
  ADD KEY `list_prod_fk` (`prod_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`loc_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_user_fk` (`user_id`),
  ADD KEY `order_loc_fk` (`loc_id`),
  ADD KEY `order_ship_fk` (`ship_id`),
  ADD KEY `order_card_fk` (`card_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`prod_id`),
  ADD KEY `item_prod_fk` (`prod_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `prod_sup_fk` (`sup_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`prod_id`,`url`);

--
-- Indexes for table `roll`
--
ALTER TABLE `roll`
  ADD PRIMARY KEY (`roll_id`);

--
-- Indexes for table `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`ship_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_rule` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `computers`
--
ALTER TABLE `computers`
  MODIFY `computer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `loc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `shippers`
--
ALTER TABLE `shippers`
  MODIFY `ship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `prod_book_fk` FOREIGN KEY (`book_id`) REFERENCES `products` (`prod_id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `book_category_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_prod_fk` FOREIGN KEY (`prod_id`) REFERENCES `products` (`prod_id`),
  ADD CONSTRAINT `comment_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `computers`
--
ALTER TABLE `computers`
  ADD CONSTRAINT `computer_sup_fk` FOREIGN KEY (`computer_id`) REFERENCES `products` (`prod_id`);

--
-- Constraints for table `list`
--
ALTER TABLE `list`
  ADD CONSTRAINT `list_prod_fk` FOREIGN KEY (`prod_id`) REFERENCES `products` (`prod_id`),
  ADD CONSTRAINT `list_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_card_fk` FOREIGN KEY (`card_id`) REFERENCES `cards` (`card_id`),
  ADD CONSTRAINT `order_loc_fk` FOREIGN KEY (`loc_id`) REFERENCES `locations` (`loc_id`),
  ADD CONSTRAINT `order_ship_fk` FOREIGN KEY (`ship_id`) REFERENCES `shippers` (`ship_id`),
  ADD CONSTRAINT `order_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `item_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `item_prod_fk` FOREIGN KEY (`prod_id`) REFERENCES `products` (`prod_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `prod_sup_fk` FOREIGN KEY (`sup_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `prod_image_fk` FOREIGN KEY (`prod_id`) REFERENCES `products` (`prod_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_rule` FOREIGN KEY (`group_id`) REFERENCES `roll` (`roll_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
