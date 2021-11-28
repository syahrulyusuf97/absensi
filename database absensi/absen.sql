-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table absensi_karyawan.absen
DROP TABLE IF EXISTS `absen`;
CREATE TABLE IF NOT EXISTS `absen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL,
  `tanggal_absen` date DEFAULT NULL,
  `jenis_absen` enum('IN','OUT') DEFAULT NULL,
  `jam_datang` timestamp NULL DEFAULT NULL,
  `jam_pulang` timestamp NULL DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `status` enum('VALID','TIDAK VALID') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_karyawan` (`id_karyawan`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table absensi_karyawan.absen: ~2 rows (approximately)
DELETE FROM `absen`;
/*!40000 ALTER TABLE `absen` DISABLE KEYS */;
INSERT INTO `absen` (`id`, `id_karyawan`, `tanggal_absen`, `jenis_absen`, `jam_datang`, `jam_pulang`, `latitude`, `longitude`, `status`, `created_at`, `updated_at`) VALUES
	(8, 70, '2021-11-27', 'IN', '2021-11-27 09:04:08', NULL, '-7.2574719', '112.7520883', 'TIDAK VALID', '2021-11-27 09:04:08', '2021-11-27 09:04:08'),
	(9, 70, '2021-11-27', 'OUT', NULL, '2021-11-27 20:52:21', '-7.2574719', '112.7520883', 'VALID', '2021-11-27 20:52:21', '2021-11-27 20:52:21'),
	(11, 70, '2021-11-28', 'IN', '2021-11-28 08:37:57', NULL, '-7.2574719', '112.7520883', 'VALID', '2021-11-28 08:37:57', '2021-11-28 08:37:57');
/*!40000 ALTER TABLE `absen` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.activity
DROP TABLE IF EXISTS `activity`;
CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `action` enum('Create','Update','Delete','Login','Logout','Activated User','Nonactivated User') NOT NULL,
  `title` varchar(25) NOT NULL,
  `note` text NOT NULL,
  `oldnote` text,
  `date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `iduser` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=3145 DEFAULT CHARSET=latin1;

-- Dumping data for table absensi_karyawan.activity: ~30 rows (approximately)
DELETE FROM `activity`;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
INSERT INTO `activity` (`id`, `iduser`, `action`, `title`, `note`, `oldnote`, `date`, `created_at`, `updated_at`) VALUES
	(3104, 70, 'Create', 'Absen Masuk', '27-11-2021 Absen Masuk', NULL, '2021-11-27 08:07:33', '2021-11-27 08:07:33', '2021-11-27 08:07:33'),
	(3105, 70, 'Create', 'Absen Masuk', '27-11-2021 Absen Masuk', NULL, '2021-11-27 09:04:08', '2021-11-27 09:04:08', '2021-11-27 09:04:08'),
	(3106, 70, 'Create', 'Izin Sakit', '27-11-2021 Izin Sakit', NULL, '2021-11-27 09:05:41', '2021-11-27 09:05:41', '2021-11-27 09:05:41'),
	(3107, 70, 'Create', 'Izin Sakit', '27-11-2021 Izin Sakit', NULL, '2021-11-27 09:06:52', '2021-11-27 09:06:52', '2021-11-27 09:06:52'),
	(3108, 70, 'Create', 'Izin Cuti', '27-11-2021 Izin Cuti', NULL, '2021-11-27 09:17:08', '2021-11-27 09:17:08', '2021-11-27 09:17:08'),
	(3109, 70, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 09:17:35', '2021-11-27 09:17:35', '2021-11-27 09:17:35'),
	(3110, 72, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 09:17:43', '2021-11-27 09:17:43', '2021-11-27 09:17:43'),
	(3111, 72, 'Update', 'Approve Izin Cuti', '27-11-2021 Approve Izin Cuti', NULL, '2021-11-27 09:46:43', '2021-11-27 09:46:43', '2021-11-27 09:46:43'),
	(3112, 72, 'Update', 'Tolak Izin Cuti', '27-11-2021 Tolak Izin Cuti', NULL, '2021-11-27 09:46:50', '2021-11-27 09:46:50', '2021-11-27 09:46:50'),
	(3113, 72, 'Update', 'Approve Izin Cuti', '27-11-2021 Approve Izin Cuti', NULL, '2021-11-27 09:46:56', '2021-11-27 09:46:56', '2021-11-27 09:46:56'),
	(3114, 72, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 13:53:05', '2021-11-27 13:53:05', '2021-11-27 13:53:05'),
	(3115, 73, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 13:53:25', '2021-11-27 13:53:25', '2021-11-27 13:53:25'),
	(3116, 73, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:14:19', '2021-11-27 14:14:19', '2021-11-27 14:14:19'),
	(3117, 70, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:14:33', '2021-11-27 14:14:33', '2021-11-27 14:14:33'),
	(3118, 70, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:41:12', '2021-11-27 14:41:12', '2021-11-27 14:41:12'),
	(3119, 72, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:41:19', '2021-11-27 14:41:19', '2021-11-27 14:41:19'),
	(3120, 72, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:45:39', '2021-11-27 14:45:39', '2021-11-27 14:45:39'),
	(3121, 73, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:45:46', '2021-11-27 14:45:46', '2021-11-27 14:45:46'),
	(3122, 73, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 14:48:14', '2021-11-27 14:48:14', '2021-11-27 14:48:14'),
	(3123, 70, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:18:13', '2021-11-27 15:18:13', '2021-11-27 15:18:13'),
	(3124, 70, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi Karyawan12', 'Nama sebelumnya Karyawan1', '2021-11-27 15:24:42', '2021-11-27 15:24:42', '2021-11-27 15:24:42'),
	(3125, 70, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi Karyawan1', 'Nama sebelumnya Karyawan12', '2021-11-27 15:24:52', '2021-11-27 15:24:52', '2021-11-27 15:24:52'),
	(3126, 70, 'Update', 'merubah NIP pengguna', 'Diperbarui menjadi 123456', 'Nama sebelumnya ', '2021-11-27 15:29:50', '2021-11-27 15:29:50', '2021-11-27 15:29:50'),
	(3127, 70, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:33:33', '2021-11-27 15:33:33', '2021-11-27 15:33:33'),
	(3128, 72, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:33:41', '2021-11-27 15:33:41', '2021-11-27 15:33:41'),
	(3129, 72, 'Update', 'merubah NIP pengguna', 'Diperbarui menjadi 789101', 'Nama sebelumnya ', '2021-11-27 15:37:40', '2021-11-27 15:37:40', '2021-11-27 15:37:40'),
	(3130, 72, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:38:15', '2021-11-27 15:38:15', '2021-11-27 15:38:15'),
	(3131, 73, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:38:23', '2021-11-27 15:38:23', '2021-11-27 15:38:23'),
	(3132, 73, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:40:19', '2021-11-27 15:40:19', '2021-11-27 15:40:19'),
	(3133, 73, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 15:40:27', '2021-11-27 15:40:27', '2021-11-27 15:40:27'),
	(3134, 73, 'Update', 'merubah NIP pengguna', 'Diperbarui menjadi 111213', 'Nama sebelumnya ', '2021-11-27 15:43:32', '2021-11-27 15:43:32', '2021-11-27 15:43:32'),
	(3135, 73, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 20:51:59', '2021-11-27 20:51:59', '2021-11-27 20:51:59'),
	(3136, 70, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-27 20:52:11', '2021-11-27 20:52:11', '2021-11-27 20:52:11'),
	(3137, 70, 'Create', 'Absen Pulang', '27-11-2021 Absen Pulang', NULL, '2021-11-27 20:52:21', '2021-11-27 20:52:21', '2021-11-27 20:52:21'),
	(3138, 70, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-28 06:29:27', '2021-11-28 06:29:27', '2021-11-28 06:29:27'),
	(3139, 72, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-28 06:29:34', '2021-11-28 06:29:34', '2021-11-28 06:29:34'),
	(3140, 72, 'Logout', 'Logout', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-28 06:51:39', '2021-11-28 06:51:39', '2021-11-28 06:51:39'),
	(3141, 70, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-28 08:15:26', '2021-11-28 08:15:26', '2021-11-28 08:15:26'),
	(3142, 70, 'Create', 'Absen Masuk', '28-11-2021 Absen Masuk', NULL, '2021-11-28 08:30:43', '2021-11-28 08:30:43', '2021-11-28 08:30:43'),
	(3143, 70, 'Login', 'Login', 'IP Address: ::1 Device: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', NULL, '2021-11-28 08:36:49', '2021-11-28 08:36:49', '2021-11-28 08:36:49'),
	(3144, 70, 'Create', 'Absen Masuk', '28-11-2021 Absen Masuk', NULL, '2021-11-28 08:37:57', '2021-11-28 08:37:57', '2021-11-28 08:37:57');
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.identitas_app
DROP TABLE IF EXISTS `identitas_app`;
CREATE TABLE IF NOT EXISTS `identitas_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `userid_create` int(11) NOT NULL,
  `userid_update` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table absensi_karyawan.identitas_app: ~0 rows (approximately)
DELETE FROM `identitas_app`;
/*!40000 ALTER TABLE `identitas_app` DISABLE KEYS */;
INSERT INTO `identitas_app` (`id`, `title`, `deskripsi`, `userid_create`, `userid_update`, `created_at`, `updated_at`) VALUES
	(1, 'Absensi Karyawan', 'Sistem absensi karyawan adalah sebuah sistem yang mencatat kehadiran karyawan berdasarkan syarat dan ketentuan yang telah diatur. Paling tidak, sebuah data yang dihasilkan dari sistem absensi karyawan akan menampilkan informasi waktu datang/waktu pulang.', 6, 6, '2020-03-29 11:04:37', '2021-11-26 21:08:39');
/*!40000 ALTER TABLE `identitas_app` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.izin
DROP TABLE IF EXISTS `izin`;
CREATE TABLE IF NOT EXISTS `izin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL,
  `jenis_izin` enum('SAKIT','CUTI') NOT NULL,
  `tanggal_izin` date NOT NULL,
  `keterangan` text NOT NULL,
  `approve` smallint(6) DEFAULT NULL COMMENT '0 : Pending, 1: Approve, 2 : Rejected',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_karyawan` (`id_karyawan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table absensi_karyawan.izin: ~0 rows (approximately)
DELETE FROM `izin`;
/*!40000 ALTER TABLE `izin` DISABLE KEYS */;
INSERT INTO `izin` (`id`, `id_karyawan`, `jenis_izin`, `tanggal_izin`, `keterangan`, `approve`, `created_at`, `updated_at`) VALUES
	(3, 70, 'CUTI', '2021-11-27', 'test cuti', 1, '2021-11-27 09:17:08', '2021-11-27 09:46:56');
/*!40000 ALTER TABLE `izin` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.oauth_access_tokens
DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.oauth_access_tokens: ~37 rows (approximately)
DELETE FROM `oauth_access_tokens`;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
	('060e12b022358f2fc607b2714f5a68a6111fd9ab9134ee09963ec71df687a9925debadcd7f49724d', 1, 1, 'Personal Access Token', '[]', 1, '2020-07-24 19:53:04', '2020-07-24 19:53:04', '2021-07-24 12:53:04'),
	('07207424cc215972f6c40657387bf6105b13077af489a6366eb57fbee9a103438915dd73e392be39', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 09:02:54', '2021-11-26 09:02:54', '2021-11-27 09:02:54'),
	('08876d7f83a1ffe962fe8586549357897f7d900de4ea3d221a2a94d79019b1be48a27fcc6fb7eb4a', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 08:51:22', '2021-11-26 08:51:22', '2021-11-27 08:51:22'),
	('09558374cba34963f6f45dd2ad35061fe425bb41caf4ab8884030214b69ce29721dde9b2605980bd', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 15:56:01', '2021-11-26 15:56:01', '2021-11-27 15:56:01'),
	('183cf18a4c83105126b8d900dcbab29f287be4561a16d7a7f520063cab5f0342ed8236114db97481', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 06:49:22', '2021-11-26 06:49:22', '2021-11-27 06:49:22'),
	('1ecefa74b71f6a9310d1668f4d96c1513aa9b51707743ba24a75e1e53d078bc00de296d78123b223', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 14:42:26', '2020-07-24 14:42:26', '2021-07-24 07:42:26'),
	('274b5d56686c33cbea9c7c6609f5354ed1f78e8a2db0d6d2d821e3b416cea3570d250359a09f8bc7', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 14:42:27', '2020-07-24 14:42:27', '2021-07-24 07:42:27'),
	('303d385120fa7303e62f588a2faee465b1043e925332c9f70d6ccb2849d1549d1233605218363f23', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 11:27:45', '2021-11-26 11:27:45', '2021-11-27 11:27:45'),
	('370b495626bc83ab5a95e3d5a2be1e87399e074a2dcf7d89456afe0168ba38e25f4a7d6e802290cd', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 11:07:34', '2021-11-26 11:07:34', '2021-11-27 11:07:34'),
	('3848fd3f16dc9d3c119362a25bca5143114f013b1419118b18639b8182ef66f3efbccb87dc6f6e93', 1, 1, 'Personal Access Token', '[]', 1, '2020-07-25 16:33:59', '2020-07-25 16:33:59', '2021-07-25 09:33:59'),
	('3a9e23954c61151fdc4060c7f92c4dc7c8aa8f81726bf683a6b5388916d3a37d023e52aec0de7848', 69, 1, 'Personal Access Token', '[]', 0, '2021-11-19 22:44:10', '2021-11-19 22:44:10', '2022-11-19 22:44:10'),
	('424a1edef20eba1efb005348e569431cabcd4e909946cc8bd258ea6f3d922cc3970ac0ad5b82dbbb', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 14:45:40', '2020-07-24 14:45:40', '2021-07-24 07:45:40'),
	('482ad3f2e7bda27694764214c008f3f35784385dc58f43cfc2b596840b0d9a550ba2275c902c0639', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 10:45:02', '2021-11-26 10:45:02', '2021-11-27 10:45:02'),
	('500ea88c257b68b1c9dcc4e50cf396a13439e1207f867e713341dabddde67ae4ca1bdc264c6c8f85', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 06:31:33', '2021-11-26 06:31:33', '2021-11-27 06:31:33'),
	('51a0a6d4ccec73d62a1724a3242db0f6e48c171a39795728a9d210e2e91fb184cdb3b0b182bb2b9d', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 16:05:53', '2021-11-26 16:05:53', '2021-11-27 16:05:53'),
	('582a5c595c4675b3d2cc34dba32bbbacda95d8ae553a50e2a3e74818a86f02e2f8188d41a066136e', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 12:21:14', '2021-11-26 12:21:14', '2021-11-27 12:21:14'),
	('5f681ae14a87465574f5a2b6023e775afc2c818ba4009629f79e81b80efdeaf2663a317b7d2727fd', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-24 15:44:47', '2021-11-24 15:44:47', '2021-11-25 15:44:47'),
	('6228abdd2f47ff9f62d438c08f620d6c3b3a77c528d5ceac92614c780a911792abd9450778300abb', 69, 1, 'Personal Access Token', '[]', 1, '2021-11-24 13:35:55', '2021-11-24 13:35:55', '2021-12-01 13:35:55'),
	('64d5bf990065afc9214114732c199471f5e8114eeeb1251c16436e07d997bec43765379d333e40ac', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 14:44:04', '2020-07-24 14:44:04', '2021-07-24 07:44:04'),
	('86d2ed2e2ece2d8a2ffefcbc1e626c0d161aa6d554b1f61a85df84544e9e8855ebecc1ccd105b377', 1, 1, 'Personal Access Token', '[]', 1, '2020-07-24 19:49:10', '2020-07-24 19:49:10', '2021-07-24 12:49:10'),
	('97b28d1632c793a81e74ec49851bdc04f14e62c47c10b3bfdd00f49cf9133ad603aafec5672caa57', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-20 19:43:01', '2020-07-20 19:43:01', '2021-07-20 12:43:01'),
	('982e8af5adc787343017d0c9494e174b5ce8ae019382c90edf7fe49b503ea6a1541b0fd6075a5c82', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 14:41:06', '2020-07-24 14:41:06', '2021-07-24 07:41:06'),
	('a4c0a9dcce94e654fec09e343e3149ac9eeda360902c420878b11e9c0ebed92019e80f3936622ebb', 1, 1, 'Personal Access Token', '[]', 1, '2020-07-24 19:30:58', '2020-07-24 19:30:58', '2021-07-24 12:30:58'),
	('a9d2aeb460a6693365c2cb48729b4aa139271fdef37d3fc9410cb2871bacce70dece0fd2acc0a6ff', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 14:42:26', '2020-07-24 14:42:26', '2021-07-24 07:42:26'),
	('ab0e073b2318008b1e7820864a9409d3c22ba37bcda8fc6082e0faa4eea469e4b33489b972f9aba3', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 11:16:32', '2021-11-26 11:16:32', '2021-11-27 11:16:32'),
	('c41fccdf0eee0f024fc045fcbbcc03ddc065ecff5971e2a021998da89638dcc05153ea78eab19b01', 32, 1, 'Personal Access Token', '[]', 1, '2020-07-11 16:16:55', '2020-07-11 16:16:55', '2021-07-11 09:16:55'),
	('c57573cda2c37a43e4bcef48f6ce1ff8c6d0b155fb2195c89a313490ee4dfa95408bf2d24ed65d6d', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-26 15:32:21', '2021-11-26 15:32:21', '2021-11-27 15:32:21'),
	('ca4e2f79a5d47a1d414db0f4e431104c9a4aa617f85b9e50df6eb4eba8841a0070b17a522b1f83ed', 1, 1, 'Personal Access Token', '[]', 1, '2020-07-25 16:37:56', '2020-07-25 16:37:56', '2021-07-25 09:37:56'),
	('d105fb17638a7ec294c4daf69b5de9ebf5cb36455b748c6ed6727bf3fd268321effe5a991632242c', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 15:14:51', '2020-07-24 15:14:51', '2021-07-24 08:14:51'),
	('d55602a0d8d88fcb7c44eb40548a97a95977e00ade0b007bfca2457409d0499ca14535db61c1bf57', 1, 1, 'Personal Access Token', '[]', 1, '2020-07-24 19:49:53', '2020-07-24 19:49:53', '2021-07-24 12:49:53'),
	('e1060da9b4d5746e193a26d728287b7f2761dc7090d3bd0dd25259681ba7fc7e8368987efb23920f', 1, 1, 'Personal Access Token', '[]', 1, '2021-11-24 13:41:35', '2021-11-24 13:41:35', '2021-11-25 13:41:35'),
	('ebecc45c93ed8c765e394d1e4667c6e2da6cb13f28bad94d14c21c568692201636259a06b77b62b0', 69, 1, 'Personal Access Token', '[]', 1, '2021-11-24 10:32:07', '2021-11-24 10:32:07', '2021-11-25 10:32:07'),
	('ed24674a18bbd5ac32f77ed16837166ba394f78c922697e32e6aa38b8bc761b6d83eac4e3b129db6', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 17:58:00', '2020-07-24 17:58:00', '2021-07-24 10:58:00'),
	('ed6451f36ee5d4edc161f65519ba0097090d462064b70ac258582269d38a9898418d4b9f89ae6651', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 14:32:48', '2021-11-26 14:32:48', '2021-11-27 14:32:48'),
	('f7e410daa72d93afa9362cd9588f051229c06176264d9252133a78f097fdcb3cb8caeb01034ab5b7', 1, 1, 'Personal Access Token', '[]', 0, '2020-07-24 16:01:05', '2020-07-24 16:01:05', '2021-07-24 09:01:05'),
	('fb1c6904fd670b86a50ffc45b24f69612ba96e3f7a15c8754fbcc839b1521ab93cc3bc70a8bbaf41', 69, 1, 'Personal Access Token', '[]', 0, '2021-11-24 10:31:07', '2021-11-24 10:31:07', '2021-11-25 10:31:07'),
	('fe9f35143270b4a437518331553f49ad31683423d1b8935be9a9c10699827a634856b1993bf0be8e', 1, 1, 'Personal Access Token', '[]', 0, '2021-11-26 15:28:36', '2021-11-26 15:28:36', '2021-11-27 15:28:36');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.oauth_auth_codes
DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.oauth_auth_codes: ~0 rows (approximately)
DELETE FROM `oauth_auth_codes`;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.oauth_clients
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.oauth_clients: ~2 rows (approximately)
DELETE FROM `oauth_clients`;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Absensi Personal Access Client', 'HKBlcj3rCBVwn6Z65yD6DG5klie8dNkREGdyTqkH', 'http://localhost', 1, 0, 0, '2020-07-10 15:33:30', '2020-07-10 15:33:30'),
	(2, NULL, 'Absensi Password Grant Client', 'Eicbu5I0DAoCmLg4Je4rKWvorQiqXoqgT2yGDEVa', 'http://localhost', 0, 1, 0, '2020-07-10 15:33:32', '2020-07-10 15:33:32');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.oauth_personal_access_clients
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.oauth_personal_access_clients: ~0 rows (approximately)
DELETE FROM `oauth_personal_access_clients`;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '2020-07-10 15:33:31', '2020-07-10 15:33:31');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.oauth_refresh_tokens
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.oauth_refresh_tokens: ~0 rows (approximately)
DELETE FROM `oauth_refresh_tokens`;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.password_resets: ~6 rows (approximately)
DELETE FROM `password_resets`;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Dumping structure for table absensi_karyawan.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` datetime DEFAULT NULL,
  `logout` datetime DEFAULT NULL,
  `login_mobile` datetime DEFAULT NULL,
  `logout_mobile` datetime DEFAULT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = inactive, 1 = active, 2 = suspend',
  `level` tinyint(2) NOT NULL DEFAULT '2' COMMENT '1 = manager, 2 = karyawan, 3 = HRD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table absensi_karyawan.users: ~4 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `nip`, `name`, `sex`, `tempat_lahir`, `tgl_lahir`, `address`, `email`, `username`, `password`, `img`, `remember_token`, `login`, `logout`, `login_mobile`, `logout_mobile`, `is_active`, `level`, `created_at`, `updated_at`) VALUES
	(69, '', 'Developer', 'Laki-laki', NULL, NULL, NULL, 'developer@pemimpi.com', 'developer', '$2y$10$5XC58ce.rQWDV/WZj5uWR.28JuKPLUe30c69610P73npnnPH1Z9oS', NULL, 'YiO2IasyQiGXd3xTpBb68fbSVkpWi4Picf7atbw1pxFFC1jX7X5I5rhCJwKZ', '2021-11-21 12:52:05', NULL, '2021-11-24 13:35:55', '2021-11-24 13:40:30', 1, 2, '2021-11-19 22:11:32', '2021-11-24 13:40:30'),
	(70, '123456', 'Karyawan1', 'Laki-laki', NULL, NULL, NULL, 'karyawan1@mail.com', 'karyawan1', '$2y$10$2qVDPf8o3fbAqQ0IahoyLegR8mDoG1D8yQbtlnKimOjnuF3orrN12', NULL, 'Y8fMxShEpir2Udbv52REQVkzv0GMWU7rcUxSs8F66lCUeeKByRAkOPPrAwYV', '2021-11-28 08:36:49', '2021-11-28 06:29:27', NULL, NULL, 1, 2, '2021-11-26 21:11:46', '2021-11-28 08:36:49'),
	(72, '789101', 'Manager', 'Laki-laki', NULL, NULL, NULL, 'manager@mail.com', 'manager', '$2y$10$aBn7RGfIkhyXI0CM4.HgGecetHj2f5oSdI8B/XRnwXqHmck6Sz4xq', NULL, 'WE1QY5RVtnTt4K5T50dGzXBvP5EC5tAruo4TLX9UkFosHuoF1RRnX2CRdP4N', '2021-11-28 06:29:34', '2021-11-28 06:51:39', NULL, NULL, 1, 1, '2021-11-26 22:11:36', '2021-11-28 06:51:39'),
	(73, '111213', 'HRD', 'Laki-laki', NULL, NULL, NULL, 'hrd@mail.com', 'hrd', '$2y$10$qqPFgbBC5ysuIiSoafjgDuvWxr7RF7sfQHTFoL7zxR4vG/mbP5W3O', NULL, 'P6IfGakQoz5fFJJ86xADR4jX2hZ0gGKfBmDGYVKklOGqhZ99t1AwEY54T2bb', '2021-11-27 15:40:27', '2021-11-27 20:51:59', NULL, NULL, 1, 3, '2021-11-26 22:11:19', '2021-11-27 20:51:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
