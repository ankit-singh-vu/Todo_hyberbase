-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: pxc
-- Generation Time: Jul 26, 2022 at 07:53 AM
-- Server version: 10.4.10-MariaDB-1:10.4.10+maria~bionic
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `application`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` bigint(250) NOT NULL,
  `user_id` bigint(250) NOT NULL,
  `access_type` int(1) NOT NULL DEFAULT 0,
  `tenant` bigint(250) NOT NULL DEFAULT 0,
  `access_key` text NOT NULL,
  `access_secret` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(5) NOT NULL DEFAULT 0,
  `last_access` bigint(250) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(250) NOT NULL,
  `name` text NOT NULL,
  `protocol` text NOT NULL,
  `support` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(250) NOT NULL,
  `invdate` bigint(250) NOT NULL DEFAULT 0,
  `tenant_id` bigint(250) NOT NULL DEFAULT 0,
  `package_id` bigint(250) NOT NULL DEFAULT 0,
  `payment_plan` bigint(250) NOT NULL DEFAULT 0,
  `qb_invoice_id` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `currency` text DEFAULT NULL,
  `plan_price` bigint(250) NOT NULL DEFAULT 0,
  `discount` bigint(250) NOT NULL DEFAULT 0,
  `amount` bigint(250) NOT NULL DEFAULT 0,
  `status` int(3) NOT NULL DEFAULT 0,
  `weight` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(250) NOT NULL,
  `uuid` text NOT NULL,
  `user_id` bigint(250) NOT NULL,
  `tenant_id` bigint(250) NOT NULL,
  `data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `started_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `completed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `job` text DEFAULT NULL,
  `status` int(5) NOT NULL DEFAULT 0,
  `pid` int(5) NOT NULL DEFAULT 0,
  `percentage` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ota_links`
--

CREATE TABLE `ota_links` (
  `id` bigint(250) NOT NULL,
  `key` text NOT NULL,
  `action` text NOT NULL,
  `expiry` bigint(250) NOT NULL DEFAULT 0,
  `data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(250) NOT NULL,
  `uuid` text NOT NULL,
  `name` text NOT NULL,
  `cost` bigint(250) NOT NULL DEFAULT 0,
  `currency` text NOT NULL,
  `plan_type` int(5) NOT NULL DEFAULT 0,
  `pg_id` bigint(250) NOT NULL DEFAULT 0,
  `pg_name` text NOT NULL,
  `billing_type` text NOT NULL,
  `billing_period` int(5) NOT NULL DEFAULT 0,
  `private` int(1) NOT NULL DEFAULT 0,
  `pg_data` text NOT NULL,
  `status` int(5) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_system_db_tag`
--

CREATE TABLE `plugin_system_db_tag` (
  `id` bigint(250) NOT NULL,
  `table_name` text NOT NULL,
  `record_id` bigint(250) NOT NULL DEFAULT 0,
  `tag` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_system_db_variables`
--

CREATE TABLE `plugin_system_db_variables` (
  `id` bigint(250) NOT NULL,
  `table_name` text NOT NULL,
  `record_id` bigint(250) NOT NULL DEFAULT 0,
  `name` text NOT NULL,
  `data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_system_db_version`
--

CREATE TABLE `plugin_system_db_version` (
  `id` bigint(250) NOT NULL,
  `table_name` text NOT NULL,
  `version` bigint(250) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plugin_system_db_version`
--

INSERT INTO `plugin_system_db_version` (`id`, `table_name`, `version`, `created_at`, `updated_at`) VALUES
(1, '__@#application', 1658821117, '2021-11-25 10:36:42', '2022-07-26 13:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `plugin_system_notification_emails`
--

CREATE TABLE `plugin_system_notification_emails` (
  `id` bigint(250) NOT NULL,
  `user_id` bigint(250) NOT NULL DEFAULT 0,
  `emailto` text NOT NULL,
  `emailfrom` text NOT NULL,
  `subject` text NOT NULL,
  `data` text NOT NULL,
  `content` text NOT NULL,
  `content_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `send_at` bigint(250) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0,
  `attachments` text NOT NULL,
  `protocol` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` bigint(250) NOT NULL,
  `role_id` bigint(250) NOT NULL DEFAULT 0,
  `user_id` bigint(250) NOT NULL DEFAULT 0,
  `tenant_id` bigint(250) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `role_id`, `user_id`, `tenant_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 0, '2021-12-08 05:12:03', '2021-12-08 10:42:03');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(250) NOT NULL,
  `name` text NOT NULL,
  `tenant_id` bigint(250) DEFAULT NULL,
  `user_id` bigint(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `tenant_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'owner', 1, NULL, '2021-12-08 10:42:03', '2021-12-08 10:42:03', 0),
(2, 'admin', 1, NULL, '2021-12-08 10:42:03', '2021-12-08 10:42:03', 0),
(3, 'staff', 1, NULL, '2021-12-08 10:42:03', '2021-12-08 10:42:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(250) NOT NULL,
  `user_id` bigint(250) NOT NULL,
  `access_id` bigint(250) NOT NULL,
  `token` text NOT NULL,
  `data` text DEFAULT NULL,
  `last_access` bigint(250) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(250) NOT NULL,
  `uuid` text NOT NULL,
  `pg_id` bigint(250) NOT NULL DEFAULT 0,
  `tenant_id` bigint(250) NOT NULL DEFAULT 0,
  `plan_id` bigint(250) NOT NULL DEFAULT 0,
  `payment_plan` bigint(250) NOT NULL DEFAULT 0,
  `trial_end` bigint(250) NOT NULL DEFAULT 0,
  `pg_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_logs`
--

CREATE TABLE `subscription_logs` (
  `id` bigint(250) NOT NULL,
  `subscription_id` bigint(250) NOT NULL DEFAULT 0,
  `event` text NOT NULL,
  `user_id` bigint(250) NOT NULL DEFAULT 0,
  `data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(250) NOT NULL,
  `uuid` text NOT NULL,
  `email` text NOT NULL,
  `name` text NOT NULL,
  `data` text DEFAULT NULL,
  `create_ts` bigint(250) DEFAULT NULL,
  `user_id` bigint(250) DEFAULT NULL,
  `qb_customer_id` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(5) NOT NULL DEFAULT 0,
  `tenant_type` int(5) NOT NULL DEFAULT 0,
  `total_spent` bigint(250) DEFAULT 0,
  `currency` text DEFAULT NULL,
  `country` text DEFAULT 'USA',
  `gender` text DEFAULT 'Male',
  `company` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `language` text DEFAULT 'English'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `uuid`, `email`, `name`, `data`, `create_ts`, `user_id`, `qb_customer_id`, `created_at`, `updated_at`, `status`, `tenant_type`, `total_spent`, `currency`, `country`, `gender`, `company`, `experience`, `phone`, `type`, `language`) VALUES
(1, 'b1a1db11-d4c8-4963-9501-8b062fe9889d', 'ankitsinggggg@gmail.com', 'ankit singh', NULL, 1638940323, 1, NULL, '2021-12-08 10:42:03', '2021-12-08 10:42:03', 10, 0, 0, NULL, 'USA', 'Male', NULL, NULL, NULL, NULL, 'English');

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` bigint(250) NOT NULL,
  `description` text DEFAULT NULL,
  `is_striked` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `todos`
--

INSERT INTO `todos` (`id`, `description`, `is_striked`, `created_at`, `updated_at`) VALUES
(50, 'dftgdfg dfgdfg dfgdf dfgdf', 0, '2021-12-20 09:04:53', '2021-12-20 14:34:53'),
(51, 'fdgdfgdfg', 0, '2021-12-20 09:13:26', '2021-12-20 14:43:26'),
(58, 'dfds', 0, '2021-12-20 12:59:08', '2021-12-20 12:59:08'),
(59, 'tyrt', 0, '2022-02-21 16:37:22', '2022-02-21 16:37:22'),
(60, ' rtyrtyrt', 0, '2022-02-21 16:37:27', '2022-02-21 16:37:27'),
(61, 'ankit', 0, '2022-07-26 13:11:00', '2022-07-26 13:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(250) NOT NULL,
  `uuid` text NOT NULL,
  `email` text NOT NULL,
  `first_name` text NOT NULL,
  `middle_name` text NOT NULL,
  `last_name` text NOT NULL,
  `profile_pic` text DEFAULT NULL,
  `c_tenant` bigint(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(5) NOT NULL DEFAULT 0,
  `user_type` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `email`, `first_name`, `middle_name`, `last_name`, `profile_pic`, `c_tenant`, `created_at`, `updated_at`, `status`, `user_type`) VALUES
(1, 'a44e0058-575c-413d-9e27-ba22aae3d903', 'ankitsinggggg@gmail.com', 'ankit', '', 'singh', NULL, 1, '2021-12-08 05:12:03', '2021-12-08 10:42:03', 10, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ota_links`
--
ALTER TABLE `ota_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin_system_db_tag`
--
ALTER TABLE `plugin_system_db_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin_system_db_variables`
--
ALTER TABLE `plugin_system_db_variables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin_system_db_version`
--
ALTER TABLE `plugin_system_db_version`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugin_system_notification_emails`
--
ALTER TABLE `plugin_system_notification_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_logs`
--
ALTER TABLE `subscription_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ota_links`
--
ALTER TABLE `ota_links`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plugin_system_db_tag`
--
ALTER TABLE `plugin_system_db_tag`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plugin_system_db_variables`
--
ALTER TABLE `plugin_system_db_variables`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plugin_system_db_version`
--
ALTER TABLE `plugin_system_db_version`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plugin_system_notification_emails`
--
ALTER TABLE `plugin_system_notification_emails`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_logs`
--
ALTER TABLE `subscription_logs`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
