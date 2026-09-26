-- ====================================================================
-- IT0049 (Web System Technologies) - Technical Formative Assessment 3
-- Project: CodeIgniter 4 POS Database
-- Database Export: tfa3_pos.sql
-- ====================================================================

-- --------------------------------------------------------
-- Table structure for table `customers`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `customers`
-- --------------------------------------------------------

INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `created_at`) VALUES
(1, 'Elena Rostova', 'elena.rostova@example.com', '+1 (555) 234-5678', '2026-03-01 08:30:00'),
(2, 'Marcus Vance', 'marcus.vance@example.com', '+1 (555) 345-6789', '2026-03-02 09:15:00'),
(3, 'Aria Thorne', 'aria.thorne@example.com', '+1 (555) 456-7890', '2026-03-03 10:45:00'),
(4, 'Julian Mercer', 'julian.mercer@example.com', '+1 (555) 567-8901', '2026-03-04 14:20:00'),
(5, 'Sophia Lin', 'sophia.lin@example.com', '+1 (555) 678-9012', '2026-03-05 16:05:00');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `username`, `full_name`, `avatar`, `created_at`) VALUES
(1, 'admin.reyes', 'Carlos Reyes', NULL, '2026-01-15 08:00:00'),
(2, 'mgr.castro', 'Beatriz Castro', NULL, '2026-01-20 08:30:00'),
(3, 'cashier.valdez', 'Daniel Valdez', NULL, '2026-02-01 09:00:00'),
(4, 'cashier.santos', 'Camille Santos', NULL, '2026-02-01 09:15:00'),
(5, 'inv.navarro', 'Leo Navarro', NULL, '2026-02-10 10:00:00');
