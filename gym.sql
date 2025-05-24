-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 06:45 PM
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
-- Database: `gym`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `session_type` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `membership_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `trainer_id`, `session_type`, `appointment_date`, `appointment_time`, `status`, `created_at`, `membership_id`) VALUES
(56, 21, 5, 'Yoga', '2025-06-03', '20:30:00', 'pending', '2025-05-20 20:40:09', NULL),
(57, 22, 17, 'Zumba', '2025-05-31', '22:00:00', 'pending', '2025-05-20 21:10:19', NULL),
(58, 22, 12, 'Weight Training', '2025-05-30', '14:10:00', 'pending', '2025-05-20 21:10:50', NULL),
(59, 23, 16, 'Cardio', '2025-06-05', '19:30:00', 'pending', '2025-05-20 21:14:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `trainer_id`, `schedule`, `limit`) VALUES
(1, 'strength', 7, '2025-06-16T12:46', 5),
(5, 'zumba', 14, '2025-05-16T18:49', 5),
(6, 'yoga', 13, '2025-05-21T19:15', 10),
(7, 'weight lifting', 8, '2025-05-20T15:30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, '01.accdb', 'uploads/01.accdb', '2025-02-02 06:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `membership_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`membership_id`, `name`, `price`, `duration`, `description`) VALUES
(1, 'Basic', 20.00, 30, 'Access to gym equipment and cardio classes.'),
(2, 'Premium', 40.00, 30, 'Includes yoga, zumba, and personal training.'),
(3, 'Elite', 60.00, 30, 'All access + diet consultation and spa sessions.');

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `query_text` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `status` enum('Pending','Responded') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queries`
--

INSERT INTO `queries` (`id`, `user_id`, `query_text`, `category`, `response`, `status`, `created_at`, `responded_at`) VALUES
(53, 21, 'How can I change to a premium plan?', 'Membership', 'You can switch plans by contacting the front desk or using the upgrade option in your account.', 'Responded', '2025-05-19 15:29:42', '2025-05-20 16:00:12'),
(54, 21, 'Can I pay for next month in advance?', 'Billing', 'Yes, you can pay early at the front desk or online once we activate payment features.\r\n\r\n', 'Responded', '2025-05-19 15:37:02', '2025-05-20 15:47:47'),
(55, 22, 'Are lockers available for all members?', 'Facilities', 'Yes, lockers are available. Bring your own lock or rent one at the counter', 'Responded', '2025-05-20 15:42:03', '2025-05-20 16:01:12'),
(56, 23, 'Can I book a session with Dineth next week?', 'Trainers', NULL, 'Pending', '2025-05-20 15:44:53', NULL),
(57, 23, 'Is there parking at the gym?', 'Other', NULL, 'Pending', '2025-05-20 15:45:57', NULL),
(58, 23, 'Does the basic plan include yoga?', 'Membership', NULL, 'Pending', '2025-05-20 15:46:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `experience` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `name`, `specialty`, `experience`, `profile`) VALUES
(5, 'kethmi', 'Yoga', 3, 'uploads/trainer_5.jpg'),
(7, 'manuja', 'Strength', 3, 'uploads/trainer_7.jpeg'),
(8, 'thivanka', 'Cardio', 3, 'uploads/trainer_8.jpg'),
(12, 'dineth', 'Strength', 3, 'uploads/1744352510_max.jpg'),
(13, 'naveen', 'Cardio', 5, 'uploads/trainer_13.jfif'),
(14, 'dusa', 'Strength', 4, 'uploads/1744396729_nathale.jpeg'),
(16, 'heshan', 'Yoga', 5, 'uploads/trainer10.jpg'),
(17, 'ashi', 'Yoga', 3, 'uploads/1747401663_rosa.jpg'),
(18, 'minal', 'Cardio', 2, 'uploads/1747749119_trainer22.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('customer','staff','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `pwd`, `email`, `role`) VALUES
(6, 'admin', '$2y$10$NwZtM6IQ99x.BCXKEaKphO2fUisknrx4D95iBd0lbNs5MJK.1Tb3G', 'admin@gmail.com', 'admin'),
(12, 'manuja', '$2y$10$KahSeiOoPFksBYGcC9fu4eFaNuSESy5EXESKjb3ACnF6ZRmnSH4rG', 'manuja@gmail.com', 'staff'),
(13, 'thivanka', '$2y$10$ULCKZ2wxIaDnDmMYq2RDw.ICn.jtSUAnDGMxVNQvohGDWeOFfnqiW', 'thivanka@gmail.com', 'staff'),
(14, 'naveen', '$2y$10$LbSJcS9lTDrAHJlF20ZUluxv8p/I3UIVc9Pme5lsf.o3jViry5Xle', 'naveen@gmail.com', 'staff'),
(15, 'heshan', '$2y$10$VdWWgSaUz3Gl8kuXUFbSvOdMcgYUuOSAsctFEENT0pMlR8Rbgrv8m', 'heshan@gmail.com', 'staff'),
(16, 'dineth', '$2y$10$9nR71qOc8pRPb4dWzmxNneS8Z7sZO2JGm90CQIX9d78MftkUNf1xe', 'dineth@gmail.com', 'staff'),
(17, 'ashi', '$2y$10$JCdWpdMDk7j5u7Zl9AR7jO3pWHJ3R7JnJPVbArPkQ.b18VL4GF...', 'ashi@gmail.com', 'staff'),
(18, 'dusa', '$2y$10$okbfbqSn4c7Q6vpF.y9IT.RAJS8l9zl9j7iIDwwa1dtDB1kzexf8G', 'dusa@gmail.com', 'staff'),
(19, 'kethmi', '$2y$10$oUCCYWujKM20iFUdjv8vv.AYUc/NvaZV4jPKEetnVZzqC7YQjGjcS', 'kethmi@gmail.com', 'staff'),
(20, 'minal', '$2y$10$I4Et.rH8d8yH.gzalJPA6OlEoiq9NdNFPyBwtG5JPmUh0H5RoB7Aq', 'minal@gmail.com', 'staff'),
(21, 'customer1', '$2y$10$5ACdNKGzXWoWlvXhpbwSwuWkehseqWjX7pHoP.K5sb1Hzs.NNjdOW', 'customer1@gmail.com', 'customer'),
(22, 'customer2', '$2y$10$2V5QrAAmodGB5qjYaCjpD.Hde1vRTIjIVaCIAbQsgaEsSmCdy4tXe', 'customer2@gmail.com', 'customer'),
(23, 'customer3', '$2y$10$UGVPQjlGb3R.Aq8ejwuToO0SOdWZnYGWIw0LNFe593rz4DODeJyrG', 'customer3@gmail.com', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `user_classes`
--

CREATE TABLE `user_classes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_classes`
--

INSERT INTO `user_classes` (`id`, `user_id`, `class_id`, `registered_at`) VALUES
(1, 6, 1, '2025-04-12 01:52:01'),
(17, 21, 7, '2025-05-20 15:38:31'),
(18, 22, 7, '2025-05-20 15:39:09'),
(19, 22, 6, '2025-05-20 15:39:12'),
(20, 23, 1, '2025-05-20 15:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_memberships`
--

CREATE TABLE `user_memberships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_memberships`
--

INSERT INTO `user_memberships` (`id`, `user_id`, `membership_id`, `start_date`, `end_date`, `type`) VALUES
(58, 21, 1, '2025-05-20', '2025-06-19', 'active'),
(59, 22, 3, '2025-05-20', '2025-06-19', 'active'),
(60, 23, 2, '2025-05-20', '2025-06-19', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`membership_id`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_classes`
--
ALTER TABLE `user_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `user_memberships`
--
ALTER TABLE `user_memberships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `membership_id` (`membership_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_classes`
--
ALTER TABLE `user_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_memberships`
--
ALTER TABLE `user_memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `queries`
--
ALTER TABLE `queries`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_classes`
--
ALTER TABLE `user_classes`
  ADD CONSTRAINT `user_classes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_memberships`
--
ALTER TABLE `user_memberships`
  ADD CONSTRAINT `user_memberships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_memberships_ibfk_2` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`membership_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
