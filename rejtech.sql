-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 05, 2025 at 04:39 AM
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
-- Database: `rejtech`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('PENDING','COMPLETED','CANCELLED','') NOT NULL DEFAULT 'PENDING',
  `total_price` decimal(9,2) NOT NULL,
  `order_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `total_price`, `order_created`) VALUES
(63, 3, 'CANCELLED', 453305.52, '2025-11-26 10:13:22'),
(64, 3, 'CANCELLED', 43708.56, '2025-11-26 10:13:46'),
(65, 3, 'CANCELLED', 729999.22, '2025-11-29 11:50:21'),
(66, 3, 'COMPLETED', 364999.61, '2025-11-29 12:00:57'),
(67, 3, 'CANCELLED', 20676.12, '2025-11-30 02:01:57'),
(68, 3, 'CANCELLED', 23032.44, '2025-11-30 02:05:48'),
(69, 3, 'COMPLETED', 347280.03, '2025-11-30 02:12:06'),
(70, 3, 'COMPLETED', 347280.03, '2025-11-30 02:13:52'),
(71, 3, 'COMPLETED', 194273.71, '2025-11-30 04:07:52'),
(72, 3, 'COMPLETED', 194273.71, '2025-11-30 04:31:22'),
(73, 3, 'CANCELLED', 364999.61, '2025-11-30 08:55:18'),
(74, 3, 'CANCELLED', 712279.64, '2025-12-01 13:58:13'),
(75, 3, 'CANCELLED', 364999.61, '2025-12-01 16:13:31'),
(76, 3, 'CANCELLED', 20676.12, '2025-12-03 14:19:13'),
(77, 3, 'CANCELLED', 53075.52, '2025-12-03 15:04:08'),
(78, 3, 'CANCELLED', 347280.03, '2025-12-03 15:09:39'),
(79, 3, 'CANCELLED', 364999.61, '2025-12-03 15:11:48'),
(80, 3, 'PENDING', 20676.12, '2025-12-03 15:12:21'),
(81, 4, 'PENDING', 147269.41, '2025-12-03 18:46:18'),
(82, 3, 'PENDING', 88305.91, '2025-12-04 13:09:38'),
(83, 3, 'PENDING', 88305.91, '2025-12-04 13:39:23'),
(84, 3, 'CANCELLED', 106151.04, '2025-12-04 21:17:59');

-- --------------------------------------------------------

--
-- Table structure for table `orders_items`
--

CREATE TABLE `orders_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `total_price` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_items`
--

INSERT INTO `orders_items` (`id`, `order_id`, `product_id`, `quantity`, `total_price`) VALUES
(84, 63, 76, 1, 364999.61),
(85, 63, 74, 1, 88305.91),
(86, 64, 99, 1, 23032.44),
(87, 64, 100, 1, 20676.12),
(88, 65, 76, 2, 729999.22),
(89, 66, 76, 1, 364999.61),
(90, 67, 100, 1, 20676.12),
(91, 68, 99, 1, 23032.44),
(92, 69, 82, 1, 347280.03),
(93, 70, 82, 1, 347280.03),
(94, 71, 73, 1, 194273.71),
(95, 72, 73, 1, 194273.71),
(96, 73, 76, 1, 364999.61),
(97, 74, 76, 1, 364999.61),
(98, 74, 82, 1, 347280.03),
(99, 75, 76, 1, 364999.61),
(100, 76, 100, 1, 20676.12),
(101, 77, 101, 1, 53075.52),
(102, 78, 82, 1, 347280.03),
(103, 79, 76, 1, 364999.61),
(104, 80, 100, 1, 20676.12),
(105, 81, 95, 1, 147269.41),
(106, 82, 74, 1, 88305.91),
(107, 83, 74, 1, 88305.91),
(108, 84, 101, 2, 106151.04);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `image4` varchar(255) NOT NULL,
  `image5` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `stock` int(11) NOT NULL,
  `sales` int(11) NOT NULL DEFAULT 0,
  `status` enum('ACTIVE','DRAFT','OUT OF STOCK','') NOT NULL DEFAULT 'ACTIVE',
  `release_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `image1`, `image2`, `image3`, `image4`, `image5`, `name`, `category`, `price`, `description`, `stock`, `sales`, `status`, `release_date`) VALUES
(73, '6915e32d11a10_S16STUDIO-1-1024x1024.png', '6915e32d12639_S16STUDIO-9-1024x1024.png', '6915e32d135e2_S16STUDIO-2-1024x1024.png', '6915e32d199ee_S16STUDIO-8-1024x1024.png', '6915e32d1a8c1_S16STUDIO-3-1024x1024.png', 'Stealth 16 AI Studio A1VHG-027US 16', 'laptop', 194273.71, 'A combination of sharp appearance and excellent performance, the all-new Stealth 16 AI Studio A1VHG-027US. One second you may be working in a busy city, and the next second you could fall into a gaming battlefield to fight for a win. Succeed at gaming and work, no matter where you go. The new slim and light body, sleek and aerodynamic for cosmic travel, blasted to the sky, exploring beyond the limits.', 10, 2, 'ACTIVE', '2025-11-13 21:54:53'),
(74, '6915e3958ebdf_Stealth14StudioA13V-1-1024x1024.png', '6915e3958f867_Stealth14StudioA13V-4-1024x1024.png', '6915e39590895_Stealth14StudioA13V-2-1024x1024.png', '6915e395918ab_Stealth14StudioA13V-3-1024x1024.png', '6915e395929e1_Stealth14StudioA13V-5-1024x1024.png', 'Stealth 14Studio A13VE-202US 14', 'laptop', 88305.91, 'A combination of sharp appearance and excellent performance, the all-new Stealth 14Studio A13VE-202US 14 FHD Gaming Laptop. One second you may be working in a busy city, and the next second you could fall into a gaming battlefield to fight for a win. Succeed at gaming and work, no matter where you go.', 10, 0, 'ACTIVE', '2025-11-13 21:56:37'),
(75, '6915e3d45327e_TITAN18HXA20161-1024x1024.png', '6915e3d454383_TITAN18HXA20164-1024x1024.png', '6915e3d4572e8_TITAN18HXA20165-1024x1024.png', '6915e3d458255_TITAN18HXA20167-1024x1024.png', '6915e3d4591be_TITAN18HXA201610-1024x1024.png', ' Titan 18 HX AI A2XWJG-614US 18', 'laptop', 323731.63, 'Experience unprecedented gaming power with the MSI Titan 18 HX AI A2XWJG-614US, featuring the groundbreaking Intel® Core™ Ultra 9 processor 285HX and NVIDIA GeForce RTX™ 50 Series GPU. Housed in a premium magnesium-aluminum alloy chassis, this flagship laptop combines elite-level performance with surprising portability. The advanced cooling system ensures sustained peak performance, while NVIDIA\'s Blackwell architecture and DLSS 4 technology deliver stunning graphics and AI-enhanced capabilities. Whether you\'re conquering the latest games or pursuing creative endeavors, the Titan 18 HX represents the pinnacle of mobile computing excellence, offering superior performance, advanced thermal management, and exceptional durability in one remarkable package.', 10, 0, 'ACTIVE', '2025-11-13 21:57:40'),
(76, '6915e4147429f_TITAN18MYTH1-1024x1024.png', '6915e41474f92_TITAN18MYTH5-1024x1024.png', '6915e41476269_TITAN18MYTH2-1024x1024.png', '6915e414771de_TITAN18MYTH6-1024x1024.png', '6915e414782c6_TITAN18MYTH4-1024x1024.png', 'Titan 18 HX Dragon Edition Norse Myth A2XWJG-440US 18', 'laptop', 364999.61, 'MSI\'s Titan 18 HX Dragon Edition Norse Myth A2XWJG-440US redefines the standard for gaming laptops. Powered by the latest Intel® Core™ Ultra 9 processor 285HX and up to NVIDIA® GeForce RTX™ 5090 Laptop GPU, this limited-edition beast delivers unparalleled performance with a stunning Norse mythology-inspired design. Featuring intricate engravings and a dragon spirit plaque, it\'s more than just a high-performance laptop—it\'s a true collectible masterpiece. Built for those who demand legendary power and resilience, this Titan is ready to conquer any challenge as your ultimate gaming powerhouse.', 11, 0, 'ACTIVE', '2025-11-13 21:58:44'),
(80, '6915eac2b6485_VISIONW-3-1024x1024.png', '6915eac2b71f8_VISIONW-7-1024x1024.png', '6915eac2b837e_VISIONW-5-1024x1024.png', '6915eac2b9255_VISIONW-2-1024x1024.png', '6915eac2b9fde_VISIONW-4-1024x1024.png', ' Vision RS AI 2NVV7-1466US Gaming Desktop', 'desktop', 176554.13, 'The Vision RS AI 2NVV7-1466US sets its sights on combining the very best in cutting-edge gaming technology from NVIDIA and Intel into a spectacular eye-catching design. If you got it flaunt it, built into the MSI Maestro chassis, a one-piece 270-degree tempered glass panel provides a panoramic view of the interior without obstructive metal at the corners, ensuring a seamless showcase of internal components.', 10, 0, 'ACTIVE', '2025-11-13 22:27:14'),
(81, '6915eb29e40df_VisionElite-Vertical-1-1024x1024.png', '6915eb29e4f1c_VisionElite-Vertical-2-1024x1024.png', '6915eb29e623f_VisionElite-Vertical-3-1024x1024.png', '6915eb29e72f5_VisionElite-Vertical-4-1024x1024.png', '6915eb29e83df_VisionElite-Vertical-3-1024x1024.png', ' Vision Elite ZS 9NVZ-1481US Gaming Desktop', 'desktop', 303656.62, 'The Vision Elite ZS 9NVZ-1481US sets its sights on combining the very best in cutting-edge gaming technology from NVIDIA and Intel into a spectacular eye-catching design. If you got it flaunt it, built into the MSI Maestro chassis, a one-piece 270-degree tempered glass panel provides a panoramic view of the interior without obstructive metal at the corners, ensuring a seamless showcase of internal components.', 10, 0, 'ACTIVE', '2025-11-13 22:28:57'),
(82, '6915eb5c0d6d6_VISIONXAI2NVV9043-3-1024x1024.png', '6915eb5c0e4ab_VISIONXAI2NVV9043-4-1024x1024.png', '6915eb5c0f824_VISIONXAI2NVV9043-1-1024x1024.png', '6915eb5c107ae_VISIONXAI2NVV9043-2-1024x1024.png', '6915eb5c11506_VISIONXAI2NVV9043-8-1024x1024.png', ' MEG Vision X AI 2NVZ9-045US Gaming Desktop', 'desktop', 347280.03, 'The MEG VISION X AI integrates MSI\'s latest AI technology into its front touchscreen, the AI HMI, and is equipped with the latest CPU and a powerful graphics card. Enhanced by Silent Storm Cooling AI technology, it unleashes unprecedented gaming performance through a unique AI gaming experience. Join fellow gamers in exploring the limitless possibilities of AI!', 8, 1, 'ACTIVE', '2025-11-13 22:29:48'),
(83, '6915eb860d39a_VE1-1024x1024.png', '6915eb860e236_VE3-1024x1024.png', '6915eb860f077_VE2-1024x1024.png', '6915eb860fde4_VE6-1024x1024.png', '6915eb8610aee_VE5-1024x1024.png', 'Vision Elite RS AI 2NVZ9-1288US Gaming Desktop', 'desktop', 317844.53, 'The Vision Elite RS AI 2NVZ9-1288US sets its sights on combining the very best in cutting-edge gaming technology from NVIDIA and Intel into a spectacular eye-catching design. If you got it flaunt it, built into the MSI Maestro chassis, a one-piece 270-degree tempered glass panel provides a panoramic view of the interior without obstructive metal at the corners, ensuring a seamless showcase of internal components.', 10, 0, 'ACTIVE', '2025-11-13 22:30:30'),
(86, '6916b6facf750_G272QPFE2-2-1024x1024.png', '6916b6fad1023_G272QPFE2-1-1024x1024.png', '6916b6fad2733_G272QPFE2-3-1024x1024.png', '6916b6fad3d9c_G272QPFE2-5-1024x1024.png', '6916b6fad4c0a_G272QPFE2-7-1024x1024.png', 'G272QPF E2 27', 'monitor', 11765.91, 'Visualize your victory with the MSI G272QPF E2 gaming monitor. Equipped with a 2560x1440, 180hz Refresh rate, 1ms(GtG) response time Rapid IPS panel, the G272QPF E2 will give you the competitive edge you need to take down your opponents. Built with Adaptive-sync technology, the G272QPF E2 can match the display\'s refresh rate with your GPU for ultra-smooth gameplay. Make sure you can hit your mark with all the latest technologies built-in the MSI Gaming monitors for competitive play.', 10, 0, 'ACTIVE', '2025-11-14 12:58:34'),
(87, '6916b76f4227b_G275LE14-2-1024x1024.png', '6916b76f43c0d_G275LE14-1-1024x1024.png', '6916b76f45214_G275LE14-3-1024x1024.png', '6916b76f464d5_G275LE14-5-1024x1024.png', '6916b76f476e2_G275LE14-6-1024x1024.png', 'G275L E14 27', 'monitor', 6470.99, 'Visualize your victory with the MSI G275L E14 gaming monitor. Equipped with a 1920 x 1080, 144hz Refresh rate, 1ms MPRT response time IPS panel, the G275L E14 will give you the competitive edge you need to take down your opponents. Built with Adaptive-sync technology, the G275L E14 can match the display\'s refresh rate with your GPU for ultra-smooth gameplay. Make sure you can hit your mark with all the latest technologies built-in the MSI Gaming monitors for competitive play.', 10, 0, 'ACTIVE', '2025-11-14 13:00:31'),
(88, '6916b79a9e459_MPG274URFQD-2-1024x1024.png', '6916b79a9fbdd_MPG274URFQD-1-1024x1024.png', '6916b79aa1238_MPG274URFQD-4-1024x1024.png', '6916b79aa262c_MPG274URFQD-6-1024x1024.png', '6916b79aa3644_MPG274URFQD-8-1024x1024.png', 'MPG 274URF QD 27', 'monitor', 22355.76, 'Visualize your victory with MPG 274URF QD gaming monitor. Equipped with a 160Hz Refresh rate, 0.5ms (GtG, Min.) response time, MPG 274URF QD will give you the competitive edge you need to take down your opponents. The UHD (3840x3160) high resolution will let you experience the mesmerizing images. Enjoy extremely smooth, tear-free gameplay with built-in Adaptive-Sync technology when paired with your GPU for ultra-smooth gameplay.', 10, 0, 'ACTIVE', '2025-11-14 13:01:14'),
(89, '6916b80b1061a_MPG321URXQDOLED-2-1024x1024.png', '6916b80b11d2e_MPG322URXQDOLED-01-1024x1024.png', '6916b80b15e63_MPG321URXQDOLED-3-1024x1024.png', '6916b80b170bd_MPG321URXQDOLED-6-1024x1024.png', '6916b80b17f94_MPG321URXQDOLED-7-1024x1024.png', 'MPG 322URX QD-OLED 32', 'monitor', 64126.84, 'Experience immersive gaming with the MPG 322URX QD-OLED gaming monitor. It features a 3840x2160 (UHD) QD-OLED panel, 240Hz refresh rate, and rapid 0.03ms(GtG) response time. VESA certification for ClearMR 13000 and DisplayHDR True Black 400 ensures exceptionally vivid images and mitigates motion blur. OLED Care 2.0 technology helps prevent burn-in. For console gamers, the MPG 322URX QD-OLED provides HDMI 2.1 ports with 48Gbps bandwidth, 4K 120Hz support, VRR, and ALLM and DisplayPort 2.1a offers 80Gbps bandwidth for gaming and professional displays. It supports future hardware with no loss in performance.', 10, 0, 'ACTIVE', '2025-11-14 13:03:07'),
(92, '6916bec9a598f_RTX508016GSHADOW3XOC-1-1024x1024-1024x1024.png', '6916bec9a6cfd_RTX508016GSHADOW3XOC-10-1024x1024.png', '6916bec9a8227_RTX508016GSHADOW3XOC-2-1024x1024.png', '6916bec9a8fcb_RTX508016GSHADOW3XOC-3-1024x1024.png', '6916bec9a9f08_RTX508016GSHADOW3XOC-6-1024x1024.png', 'GeForce RTX 5080 16G SHADOW 3X OC', 'graphics-card', 64798.21, 'MSI GeForce RTX 5080 16G SHADOW 3X OC focuses on the essentials to tackle any challenge. Its efficient thermal solution is encased in a resilient enclosure with a neutral aesthetic, allowing this sleek graphics card to integrate seamlessly into any build.', 10, 0, 'ACTIVE', '2025-11-14 13:31:53'),
(93, '6916bf196bc41_RTX508016GGAMINGTRIOW1-1024x1024.png', '6916bf196efba_RTX508016GGAMINGTRIOOCW-11-1024x1024.png', '6916bf197022e_RTX508016GGAMINGTRIOW-4-1024x1024.png', '6916bf1970f43_RTX508016GGAMINGTRIOW-2-1024x1024.png', '6916bf1971b82_RTX508016GGAMINGTRIOW-8-1024x1024.png', ' GeForce RTX 5080 16G GAMING TRIO WHITE', 'graphics-card', 91895.89, 'Fearless and bold, GAMING TRIO delivers strong performance to both gaming and content creation. It blends a fierce look with advanced cooling technologies, making it an unwavering ally on the gaming battlefield. GeForce RTX 5080 16G GAMING TRIO WHITE is the ideal choice for gamers who strive to give their all.', 10, 0, 'ACTIVE', '2025-11-14 13:33:13'),
(94, '6916bf4fd829c_RTX509032GVANGUARDSOC1-1024x1024.png', '6916bf4fd9d76_5090VSOC-1-1024x1024.png', '6916bf4fdb3ea_RTX509032GVANGUARDSOC-4-1024x1024.png', '6916bf4fdc67d_RTX509032GVANGUARDSOC-2-1024x1024.png', '6916bf4fdd7cd_RTX509032GVANGUARDSOC-9-1024x1024.png', ' GeForce RTX 5090 32G VANGUARD SOC', 'graphics-card', 153160.21, 'MSI Vanguard series graphics card embodies the spirit of exploration. Premium gaming craftsmanship is expressed with aerodynamic edges, fractured lines, and futuristic RGB lights that empower players to push the limits of adventure while exploring uncharted worlds.', 10, 0, 'ACTIVE', '2025-11-14 13:34:07'),
(95, '6916bf846ff44_RTX5090D32GGAMINGTRIOOC1-1024x1024.png', '6916bf8471a6a_RTX509032GGAMINGTRIOOC-11-1024x1024.png', '6916bf8472c36_RTX5090D32GGAMINGTRIOOC-4-1024x1024.png', '6916bf8473985_RTX5090D32GGAMINGTRIOOC-2-1024x1024.png', '6916bf8474753_RTX5090D32GGAMINGTRIOOC-8-1024x1024.png', ' GeForce RTX 5090 32G GAMING TRIO OC', 'graphics-card', 147269.41, 'Fearless and bold, MSI GeForce RTX 5090 32G GAMING TRIO OC delivers strong performance to both gaming and content creation. It blends a fierce look with advanced cooling technologies, making it an unwavering ally on the gaming battlefield. GAMING TRIO is the ideal choice for gamers who strive to give their all.', 10, 0, 'ACTIVE', '2025-11-14 13:35:00'),
(98, '6916c2ab056ca_Z790ACE-2-1024x1024.png', '6916c2ab075b8_Z790ACE-1-1024x1024.png', '6916c2ab088c9_Z790ACE-4-1024x1024.png', '6916c2ab097f5_Z790ACE-3-1024x1024.png', '6916c2ab0a416_Z790ACE-5-1024x1024.png', ' MEG Z790 ACE', 'motherboard', 23621.52, 'Furnished premium aesthetics with its dark black finish and gold embellishes. Developed to unlock the full gaming potential of the latest 14th Gen Intel® Core processor by combining elite hardware system support and premium durability, the MEG Z790 ACE is an outstanding E-ATX platform for extreme settings in terms of overclocking the processor, memory, and graphics card at the highest level.', 10, 0, 'ACTIVE', '2025-11-14 13:48:27'),
(99, '6916c2e582863_Z790CARBMAXWFIII-2-1024x1024.png', '6916c2e5841b0_Z790CARBMAXWFIII-1-1024x1024.png', '6916c2e5858b2_Z790CARBMAXWFIII-4-1024x1024.png', '6916c2e58677b_Z790CARBMAXWFIII-3-1024x1024.png', '6916c2e5875fe_Z790CARBMAXWFIII-5-1024x1024.png', ' MPG Z790 CARBON MAX WIFI II', 'motherboard', 23032.44, 'MPG Z790 CARBON MAX WIFI II motherboard is designed for high-performance gaming, multitasking, and overclocking, featuring cutting-edge technologies like PCIe 5.0, DDR5 memory, and Wi-Fi 6E. It also offers robust thermal solutions and extensive connectivity options, making it a standout choice for gaming enthusiasts and content creators.', 11, 0, 'ACTIVE', '2025-11-14 13:49:25'),
(100, '6916c322bbcf1_Z890CARBONWIFI-2-1024x1024.png', '6916c322bd46c_Z890CARBONWIFI-1-1024x1024.png', '6916c322be9af_Z890CARBONWIFI-3-1024x1024.png', '6916c322bf87f_Z890CARBONWIFI-4-1024x1024.png', '6916c322c045f_Z890CARBONWIFI-7-1024x1024.png', ' MPG Z890 CARBON WIFI', 'motherboard', 20676.12, 'MPG Z890 CARBON WIFI employs a carbon black color scheme and stylish pattern over Mystic light RGB lighting to show a different identity. It is also powerful inside by featuring Killer 5G LAN and Wi-Fi 7 solution, Thunderbolt 4, Supplemental PCIe Power and the effortless EZ DIY designs, making this motherboard definitely one of the best Z890 ATX motherboards for Intel Core Ultra processors.', 10, 0, 'ACTIVE', '2025-11-14 13:50:26'),
(101, '6916c363c876d_Z890GODLIKE-2-1024x1024.png', '6916c363ca00a_Z890GODLIKE-1A-1024x1024.png', '6916c363cb429_Z890GODLIKE-3-1024x1024.png', '6916c363cc36e_Z890GODLIKE-4-1024x1024.png', '6916c363cd506_Z890GODLIKE-8-1024x1024.png', 'MEG Z890 GODLIKE', 'motherboard', 53075.52, 'Furnished premium aesthetics with its dark black finish and pale gold embellishes. Developed to unlock the full gaming potential of the Intel Core Ultra processor by combining elite hardware system support and premium durability, the MEG Z890 GODLIKE is an outstanding E-ATX of Z890 platform for extreme settings in terms of overclocking the processor, DDR5 memory, and graphics card at the highest level.', 10, 0, 'ACTIVE', '2025-11-14 13:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `role` enum('customer','admin','','') NOT NULL DEFAULT 'customer',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(3, 'Mark Niel', 'Morales', 'mrknlmrls@gmail.com', '$2y$10$C8Mn/KJyTEi.2LBIE2Cg5u93zuU7OF66D9ttPDacac5uWyu3SOSxy', '09393566825', 'Quezon City', 'customer', '2025-11-23 03:06:01'),
(4, 'admin', 'rejtech', 'rejtech4@gmail.com', '$2y$10$iowVRyj1uC8XcZhMw8r5VOUDt/XZxusJ1vVVLD7TGYwcQrY3waT0y', '09123456789', 'Quezon City', 'admin', '2025-12-03 16:45:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_items`
--
ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
