-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 05:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_dairy`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(4, 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `uuid` varchar(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `farm_size` decimal(10,2) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `aadhar` varchar(12) DEFAULT NULL,
  `bank_account` varchar(30) DEFAULT NULL,
  `ifsc` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `username` varchar(100) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `uuid`, `name`, `contact`, `address`, `farm_size`, `email`, `password`, `aadhar`, `bank_account`, `ifsc`, `created_at`, `status`, `username`, `reset_token`, `reset_expires`) VALUES
(15, 'SN68', 'jojo', '', 'wfdw', 0.00, 'jojo@gmail.com', '289dff07669d7a23de0ef88d2f7129e7', '', '', '', '2025-09-20 06:14:49', 'inactive', NULL, NULL, NULL),
(17, 'I2HG', 'joyal royce', '09847012251', 'mallappally', 1.20, 'joyal@gmail.com', '202cb962ac59075b964b07152d234b70', '123345567', '32435445', '32445455', '2025-09-21 14:08:48', 'inactive', 'joyal123', NULL, NULL),
(18, 'LL40', 'pooja', '9847012251', 'konnni', 1.50, 'pooja@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '', '', '', '2025-10-04 10:22:09', 'active', 'pooja123', NULL, NULL),
(19, '0DHU', 'hari', '', 'dfghj', 12.00, 'hari@gmail.com', '202cb962ac59075b964b07152d234b70', '', '', '', '2025-10-04 13:13:21', 'active', 'hari123', NULL, NULL),
(20, '9Y7P', 'shebin', '21344353', 'qwed', 0.00, 'shebin@gmail.com', 'e29116ea97b3bf295e862d55e01acb1f', '', '', '', '2025-10-04 13:43:51', 'inactive', 'shebin123', NULL, NULL),
(21, '44KE', 'vishnu', '9847012251', 'ssssssss', 0.00, 'vishnu@gmail.com', 'e29116ea97b3bf295e862d55e01acb1f', '123456789009', '', '', '2025-10-04 13:50:00', 'active', 'vishnu123', NULL, NULL),
(22, 'HVFU', 'shibin', '9847012251', 'ssssssss', 0.00, 'shibin@gmail.com', 'e29116ea97b3bf295e862d55e01acb1f', '123456789009', '', '', '2025-10-04 13:53:18', 'active', 'shibin123', NULL, NULL),
(23, 'PEXH', 'jijo', '', 'aaaaaaaaaaa', 0.00, 'jiji@gmail.com', 'e29116ea97b3bf295e862d55e01acb1f', '', '', '', '2025-10-09 02:31:52', 'active', 'jiji123', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `milk_collection`
--

CREATE TABLE `milk_collection` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `farmer_uuid` varchar(4) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `fat` float DEFAULT NULL,
  `temperature` varchar(10) DEFAULT NULL,
  `payment` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `milk_collection`
--

INSERT INTO `milk_collection` (`id`, `date`, `farmer_uuid`, `product_type`, `quantity`, `fat`, `temperature`, `payment`) VALUES
(1, '2025-08-23', NULL, 'milk12', 2, 2, '3', 144),
(2, '2025-08-23', NULL, 'milk12', 23, 4, '324', 3244),
(10, '2025-01-09', '44KE', 'milk', 23, 3, '3', 2345),
(12, '2025-10-09', 'LL40', 'milk', 23, 3, '3', 2345),
(13, '2025-10-09', 'LL40', 'milk', 23, 3, '3', 2345);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `farmer_uuid` varchar(4) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomer`
--

CREATE TABLE `tblcustomer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcustomer`
--

INSERT INTO `tblcustomer` (`id`, `customer_name`, `contact`, `email`, `address`, `registration_date`, `status`, `created_at`, `updated_at`, `password`, `reset_token`, `reset_expires`) VALUES
(6, 'joyal', '', 'joyal@gmail.com', 'alunkal house mallappally north po', '2025-10-04 10:19:44', 'active', '2025-10-04 10:19:44', '2025-10-06 01:46:34', 'e29116ea97b3bf295e862d55e01acb1f', NULL, NULL),
(8, 'jas', '', 'jas@gmail.com', '', '2025-10-04 14:04:23', 'active', '2025-10-04 14:04:23', '2025-10-04 14:05:07', 'ff4b83a6f54595abf0013e776ce6f8f4', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbldelivery`
--

CREATE TABLE `tbldelivery` (
  `ID` int(11) NOT NULL,
  `CustomerName` varchar(100) DEFAULT NULL,
  `Contact` varchar(20) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `DeliveryDate` date DEFAULT NULL,
  `ProductType` varchar(50) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `VehicleNo` varchar(20) DEFAULT NULL,
  `DriverName` varchar(100) DEFAULT NULL,
  `DriverContact` varchar(20) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_uuid` varchar(36) NOT NULL,
  `customer_uuid` varchar(36) NOT NULL,
  `driver_uuid` varchar(36) NOT NULL,
  `delivery_status` enum('pending','received') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldelivery`
--

INSERT INTO `tbldelivery` (`ID`, `CustomerName`, `Contact`, `Address`, `DeliveryDate`, `ProductType`, `Quantity`, `VehicleNo`, `DriverName`, `DriverContact`, `PostingDate`, `delivery_uuid`, `customer_uuid`, `driver_uuid`, `delivery_status`) VALUES
(5, 'pooja', '1234', 'dfghhy', '2025-10-04', 'Milk', 34, '4wr443', 'dsfrg', '34', '2025-10-04 13:19:37', 'Q1OV', 'IDS8', '72H4', 'pending'),
(6, 'joyal royce', '33333', 'hyty', '2025-10-30', 'Curd', 6, 'gh6656', 'jof', '66666666', '2025-10-08 07:46:32', 'J96E', 'T0WP', 'TNI7', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tblorderitems`
--

CREATE TABLE `tblorderitems` (
  `ID` int(11) NOT NULL,
  `OrderID` varchar(50) NOT NULL,
  `ProductID` varchar(50) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorderitems`
--

INSERT INTO `tblorderitems` (`ID`, `OrderID`, `ProductID`, `Quantity`, `Price`, `Total`) VALUES
(1, 'ORD_68d1719f66025', '4', 1, 23.00, 23.00),
(2, 'ORD_68d1719f66025', '5', 1, 45.00, 45.00),
(3, 'ORD_68e122891051f', '8', 1, 23.00, 23.00),
(4, 'ORD_68e613459927e', '8', 1, 23.00, 23.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblorders`
--

CREATE TABLE `tblorders` (
  `ID` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `Status` enum('Pending','Processing','Delivered','Cancelled') DEFAULT 'Pending',
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorders`
--

INSERT INTO `tblorders` (`ID`, `Email`, `TotalAmount`, `Status`, `OrderDate`) VALUES
('ORD_68d1719f66025', 'pooja@gmail.com', 71.40, 'Pending', '2025-09-22 15:56:15'),
('ORD_68e122891051f', 'joyal@gmail.com', 24.15, 'Pending', '2025-10-04 13:35:05'),
('ORD_68e613459927e', 'joyal@gmail.com', 24.15, 'Pending', '2025-10-08 07:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `ID` int(11) NOT NULL,
  `ProductName` varchar(100) DEFAULT NULL,
  `ProductType` varchar(50) DEFAULT NULL,
  `UnitPrice` decimal(10,2) DEFAULT NULL,
  `productimage` varchar(255) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`ID`, `ProductName`, `ProductType`, `UnitPrice`, `productimage`, `Quantity`, `Description`, `PostingDate`) VALUES
(4, 'milk', 'milk', 23.00, 'uploads/products/product_68d1198391f951.31223157.png', NULL, NULL, '2025-09-22 09:40:19'),
(5, 'curd', 'curd', 45.00, 'uploads/products/product_68d1200e547f02.98518454.png', NULL, NULL, '2025-09-22 10:08:14'),
(6, 'cream', 'ice cream', 10.00, 'uploads/products/product_68d12038e98073.17128697.png', NULL, NULL, '2025-09-22 10:08:56'),
(7, 'gee', 'grr', 45.00, '', NULL, NULL, '2025-09-22 10:09:12'),
(8, 'milk', 'Milk Product', 23.00, '', NULL, NULL, '2025-10-04 13:26:36');

-- --------------------------------------------------------

--
-- Table structure for table `tblsales`
--

CREATE TABLE `tblsales` (
  `ID` int(11) NOT NULL,
  `InvoiceNumber` varchar(50) DEFAULT NULL,
  `CustomerName` varchar(100) DEFAULT NULL,
  `Contact` varchar(20) DEFAULT NULL,
  `ProductName` varchar(100) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `TotalAmount` decimal(10,2) DEFAULT NULL,
  `SalesDate` date DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsales`
--

INSERT INTO `tblsales` (`ID`, `InvoiceNumber`, `CustomerName`, `Contact`, `ProductName`, `Quantity`, `Price`, `TotalAmount`, `SalesDate`, `PostingDate`) VALUES
(6, '3434', 'pooja', '23445', 'milk', 34, 4.00, 136.00, '2025-10-04', '2025-10-04 10:48:50'),
(7, 'qw23233', 'pooja', '1234567', 'milk', 34, 444.00, 15096.00, '2025-10-04', '2025-10-04 13:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_order_details`
-- (See below for the actual view)
--
CREATE TABLE `vw_order_details` (
`OrderID` varchar(50)
,`Email` varchar(255)
,`TotalAmount` decimal(10,2)
,`Status` enum('Pending','Processing','Delivered','Cancelled')
,`OrderDate` timestamp
,`ProductID` varchar(50)
,`Quantity` int(11)
,`Price` decimal(10,2)
,`ItemTotal` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_order_details`
--
DROP TABLE IF EXISTS `vw_order_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_order_details`  AS SELECT `o`.`ID` AS `OrderID`, `o`.`Email` AS `Email`, `o`.`TotalAmount` AS `TotalAmount`, `o`.`Status` AS `Status`, `o`.`OrderDate` AS `OrderDate`, `oi`.`ProductID` AS `ProductID`, `oi`.`Quantity` AS `Quantity`, `oi`.`Price` AS `Price`, `oi`.`Total` AS `ItemTotal` FROM (`tblorders` `o` left join `tblorderitems` `oi` on(`o`.`ID` = `oi`.`OrderID`)) ORDER BY `o`.`OrderDate` DESC, `o`.`ID` ASC, `oi`.`ID` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `uuid_2` (`uuid`),
  ADD UNIQUE KEY `uuid_3` (`uuid`),
  ADD UNIQUE KEY `uuid_4` (`uuid`),
  ADD UNIQUE KEY `uuid_5` (`uuid`),
  ADD KEY `idx_reset_token` (`reset_token`),
  ADD KEY `idx_reset_expires` (`reset_expires`);

--
-- Indexes for table `milk_collection`
--
ALTER TABLE `milk_collection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_farmer_uuid` (`farmer_uuid`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_uuid` (`farmer_uuid`);

--
-- Indexes for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_reset_token_customer` (`reset_token`),
  ADD KEY `idx_reset_expires_customer` (`reset_expires`);

--
-- Indexes for table `tbldelivery`
--
ALTER TABLE `tbldelivery`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblorderitems`
--
ALTER TABLE `tblorderitems`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_order_id` (`OrderID`),
  ADD KEY `idx_product_id` (`ProductID`);

--
-- Indexes for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_email` (`Email`),
  ADD KEY `idx_status` (`Status`),
  ADD KEY `idx_order_date` (`OrderDate`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblsales`
--
ALTER TABLE `tblsales`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `milk_collection`
--
ALTER TABLE `milk_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbldelivery`
--
ALTER TABLE `tbldelivery`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblorderitems`
--
ALTER TABLE `tblorderitems`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblsales`
--
ALTER TABLE `tblsales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `milk_collection`
--
ALTER TABLE `milk_collection`
  ADD CONSTRAINT `fk_farmer_uuid` FOREIGN KEY (`farmer_uuid`) REFERENCES `farmers` (`uuid`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`farmer_uuid`) REFERENCES `farmers` (`uuid`);

--
-- Constraints for table `tblorderitems`
--
ALTER TABLE `tblorderitems`
  ADD CONSTRAINT `tblorderitems_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `tblorders` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
