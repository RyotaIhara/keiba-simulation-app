-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-02-11 12:06:32
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `keiba_simulation`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `betting_type_mst`
--

CREATE TABLE `betting_type_mst` (
  `id` int(11) NOT NULL,
  `betting_type_code` varchar(11) NOT NULL,
  `betting_type_name` varchar(11) NOT NULL,
  `is_ordered` tinyint(4) NOT NULL COMMENT '順番が大事かどうか'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `betting_type_mst`
--

INSERT INTO `betting_type_mst` (`id`, `betting_type_code`, `betting_type_name`, `is_ordered`) VALUES
(1, 'Tansho', '単勝', 0),
(2, 'Fukusho', '複勝', 0),
(3, 'Wakuren', '枠連', 0),
(4, 'Umaren', '馬連', 0),
(5, 'wide', 'ワイド', 0),
(6, 'Wakutan', '枠単', 1),
(7, 'Umatan', '馬単', 1),
(8, 'Fuku3', '3連複', 0),
(9, 'Tan3', '3連単', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `box_voting_record`
--

CREATE TABLE `box_voting_record` (
  `id` int(11) NOT NULL,
  `voting_record_id` int(11) NOT NULL,
  `voting_uma_ban_box` varchar(50) NOT NULL,
  `voting_amount_box` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `formation_voting_record`
--

CREATE TABLE `formation_voting_record` (
  `id` int(11) NOT NULL,
  `voting_record_id` int(11) NOT NULL,
  `voting_uma_ban_1` varchar(50) NOT NULL,
  `voting_uma_ban_2` varchar(50) NOT NULL,
  `voting_uma_ban_3` varchar(50) NOT NULL,
  `voting_amount_formaation` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `how_to_buy_mst`
--

CREATE TABLE `how_to_buy_mst` (
  `id` int(11) NOT NULL,
  `how_to_buy_code` varchar(11) NOT NULL,
  `how_to_buy_name` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='通常/フォーメーション/ながし/ボックス';

--
-- テーブルのデータのダンプ `how_to_buy_mst`
--

INSERT INTO `how_to_buy_mst` (`id`, `how_to_buy_code`, `how_to_buy_name`) VALUES
(1, 'normal', '通常'),
(2, 'box', 'ボックス'),
(3, 'nagashi', 'ながし'),
(4, 'formation', 'フォーメーション');

-- --------------------------------------------------------

--
-- テーブルの構造 `login_session`
--

CREATE TABLE `login_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text NOT NULL,
  `expire_time` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `nagashi_voting_record`
--

CREATE TABLE `nagashi_voting_record` (
  `id` int(11) NOT NULL,
  `voting_record_id` int(11) NOT NULL,
  `shaft_pattern` int(3) NOT NULL COMMENT '1:軸1頭ながし、2:軸2頭ながし、',
  `shaft` varchar(50) NOT NULL COMMENT '軸',
  `partner` varchar(50) NOT NULL COMMENT '相手',
  `voting_amount_nagashi` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `netkeiba_expected_horse`
--

CREATE TABLE `netkeiba_expected_horse` (
  `id` int(11) NOT NULL,
  `race_info_id` int(11) NOT NULL,
  `uma_ban` int(4) NOT NULL,
  `feature_datas` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `racecourse_mst`
--

CREATE TABLE `racecourse_mst` (
  `id` int(11) NOT NULL,
  `jyo_cd` varchar(5) NOT NULL,
  `racecourse_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `racecourse_mst`
--

INSERT INTO `racecourse_mst` (`id`, `jyo_cd`, `racecourse_name`) VALUES
(1, '65', '帯広ば競馬場'),
(2, '30', '門別競馬場'),
(3, '35', '盛岡競馬場'),
(4, '36', '水沢競馬場'),
(5, '42', '浦和競馬場'),
(6, '43', '船橋競馬場'),
(7, '44', '大井競馬場'),
(8, '45', '川崎競馬場'),
(9, '46', '金沢競馬場'),
(10, '47', '笠松競馬場'),
(11, '48', '名古屋競馬場'),
(12, '50', '園田競馬場'),
(13, '51', '姫路競馬場'),
(14, '54', '高知競馬場'),
(15, '55', '佐賀競馬場');

-- --------------------------------------------------------

--
-- テーブルの構造 `race_card`
--

CREATE TABLE `race_card` (
  `id` int(11) NOT NULL,
  `race_info_id` int(11) DEFAULT NULL,
  `waku_ban` int(4) DEFAULT NULL,
  `uma_ban` int(4) DEFAULT NULL,
  `horse_name` text DEFAULT NULL,
  `sex_age` varchar(8) DEFAULT NULL COMMENT '性齢',
  `weight` float DEFAULT NULL,
  `jockey_name` text DEFAULT NULL,
  `favourite` int(4) DEFAULT NULL,
  `win_odds` float DEFAULT NULL,
  `trainer` varchar(11) DEFAULT NULL COMMENT '厩舎',
  `weight_gain_loss` float DEFAULT NULL,
  `is_cancel` tinyint(4) DEFAULT NULL COMMENT '取消フラグ',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `race_info`
--

CREATE TABLE `race_info` (
  `id` int(11) NOT NULL,
  `race_date` date NOT NULL,
  `jyo_cd` varchar(5) NOT NULL,
  `race_num` int(3) NOT NULL,
  `race_name` text NOT NULL,
  `entry_horce_count` int(4) DEFAULT NULL,
  `course_type` varchar(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `rotation` varchar(11) DEFAULT NULL,
  `weather` varchar(11) DEFAULT NULL,
  `baba_state` varchar(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `race_refund_amount`
--

CREATE TABLE `race_refund_amount` (
  `id` int(11) NOT NULL,
  `race_info_id` int(11) NOT NULL,
  `betting_type_mst_id` int(11) NOT NULL,
  `pattern` varchar(4) DEFAULT NULL COMMENT '複勝やワイドなどを考慮',
  `result_uma_ban` varchar(50) DEFAULT NULL,
  `refund_amount` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `race_schedule`
--

CREATE TABLE `race_schedule` (
  `id` int(11) NOT NULL,
  `race_date` date NOT NULL,
  `jyo_cd` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `setting_service`
--

CREATE TABLE `setting_service` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `setting_service`
--

INSERT INTO `setting_service` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'netkeiba_loginId', 'ネット競馬のログインID'),
(2, 'netkeiba_password', 'ネット競馬のパスワード');

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`id`, `code`, `username`, `password`) VALUES
(1, 'ihara', '井原 瞭太', 'ihara1234'),
(2, 'test001', 'テストユーザー001', 'test1234'),
(3, 'shimu01', 'シミュレーションユーザー01', 'shimu01'),
(4, 'shimu02', 'シミュレーションユーザー02', 'shimu02');

-- --------------------------------------------------------

--
-- テーブルの構造 `voting_record`
--

CREATE TABLE `voting_record` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `race_info_id` int(11) NOT NULL,
  `how_to_buy_mst_id` int(11) NOT NULL,
  `betting_type_mst_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `voting_record_detail`
--

CREATE TABLE `voting_record_detail` (
  `id` int(11) NOT NULL,
  `voting_record_id` int(11) NOT NULL,
  `voting_uma_ban` varchar(50) NOT NULL,
  `voting_amount` int(11) DEFAULT NULL,
  `refund_amount` int(11) DEFAULT NULL,
  `hit_status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `betting_type_mst`
--
ALTER TABLE `betting_type_mst`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `betting_type_code` (`betting_type_code`);

--
-- テーブルのインデックス `box_voting_record`
--
ALTER TABLE `box_voting_record`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `formation_voting_record`
--
ALTER TABLE `formation_voting_record`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `how_to_buy_mst`
--
ALTER TABLE `how_to_buy_mst`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `login_session`
--
ALTER TABLE `login_session`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `nagashi_voting_record`
--
ALTER TABLE `nagashi_voting_record`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `netkeiba_expected_horse`
--
ALTER TABLE `netkeiba_expected_horse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `race_info_id` (`race_info_id`,`uma_ban`);

--
-- テーブルのインデックス `racecourse_mst`
--
ALTER TABLE `racecourse_mst`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jyo_cd` (`jyo_cd`);

--
-- テーブルのインデックス `race_card`
--
ALTER TABLE `race_card`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `race_info_id` (`race_info_id`,`uma_ban`);

--
-- テーブルのインデックス `race_info`
--
ALTER TABLE `race_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `race_date` (`race_date`,`jyo_cd`,`race_num`);

--
-- テーブルのインデックス `race_refund_amount`
--
ALTER TABLE `race_refund_amount`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `race_info_id` (`race_info_id`,`betting_type_mst_id`,`pattern`);

--
-- テーブルのインデックス `race_schedule`
--
ALTER TABLE `race_schedule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `race_date` (`race_date`,`jyo_cd`);

--
-- テーブルのインデックス `setting_service`
--
ALTER TABLE `setting_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `voting_record`
--
ALTER TABLE `voting_record`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `voting_record_detail`
--
ALTER TABLE `voting_record_detail`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `betting_type_mst`
--
ALTER TABLE `betting_type_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `box_voting_record`
--
ALTER TABLE `box_voting_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `formation_voting_record`
--
ALTER TABLE `formation_voting_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `how_to_buy_mst`
--
ALTER TABLE `how_to_buy_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `login_session`
--
ALTER TABLE `login_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `nagashi_voting_record`
--
ALTER TABLE `nagashi_voting_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `netkeiba_expected_horse`
--
ALTER TABLE `netkeiba_expected_horse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `racecourse_mst`
--
ALTER TABLE `racecourse_mst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- テーブルの AUTO_INCREMENT `race_card`
--
ALTER TABLE `race_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `race_info`
--
ALTER TABLE `race_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `race_refund_amount`
--
ALTER TABLE `race_refund_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `race_schedule`
--
ALTER TABLE `race_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `setting_service`
--
ALTER TABLE `setting_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `voting_record`
--
ALTER TABLE `voting_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `voting_record_detail`
--
ALTER TABLE `voting_record_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
