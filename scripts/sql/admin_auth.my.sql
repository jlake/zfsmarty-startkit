/*
* データベース定義（table, index, sequence, view など）
*/

-- 管理者ユーザマスタ
DROP TABLE IF EXISTS admin_user_mst;
CREATE TABLE admin_user_mst (
	user_id					INTEGER NOT NULL,
	user_kbn				TINYINT NOT NULL DEFAULT 1,		--	管理者区分(0: システム管理者  1: 通常ユーザ)
	user_nm					VARCHAR(32),
	user_nm_jp				VARCHAR(32),
	user_pwd				VARCHAR(32),
	user_email				VARCHAR(64),
	last_login_date			DATETIME NOT NULL,
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY(user_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

-- 管理画面メニューマスタ
DROP TABLE IF EXISTS admin_menu_mst;
CREATE TABLE admin_menu_mst (
	menu_id					INTEGER NOT NULL,	--	メニューID
	menu_title				VARCHAR(64),	--	メニュータイトル
	parent_menu_id			INTEGER,		--	親メニューID
	display_order			INTEGER,		--	表示順
	link_uri				VARCHAR(256),	--	リンクURI
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY( menu_id )
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

-- 管理ページマスタ
DROP TABLE IF EXISTS admin_page_mst;
CREATE TABLE admin_page_mst (
	page_id					INTEGER NOT NULL,	--	ページID
	page_nm					VARCHAR(64),	--	ページ名
	controller_nm			VARCHAR(64),	--	コントローラ名
	action_nm				VARCHAR(64),	--	アクション名
	resource_id				INTEGER,	--	リソースID
	menu_id					INTEGER,	--	メニューID
	sub_menu_id				INTEGER,	--	サブメニューID
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY( page_id ),
	UNIQUE(controller_nm, action_nm)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

-- リソースマスタ
DROP TABLE IF EXISTS admin_resource_mst;
CREATE TABLE admin_resource_mst (
	resource_id				INTEGER NOT NULL,	--	リソースID
	resource_nm				VARCHAR(64),	--	リソースの名称
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY ( resource_id )
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

-- 権限グループマスタ
DROP TABLE IF EXISTS admin_auth_group_mst;
CREATE TABLE admin_auth_group_mst (
	auth_group_id			INTEGER NOT NULL,	--	権限グループID
	auth_group_nm			VARCHAR(64),	--	権限グループの名称
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY ( auth_group_id )
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

-- 権限グループ・ユーザテーブル
DROP TABLE IF EXISTS admin_auth_group_user_tbl;
CREATE TABLE admin_auth_group_user_tbl (
	auth_group_id			VARCHAR(32) NOT NULL,	--	権限グループID
	user_id					VARCHAR(32) NOT NULL,	--	管理者ID
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY ( auth_group_id, user_id )
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;

-- 権限グループ・リソーステーブル
DROP TABLE IF EXISTS admin_auth_group_resource_tbl;
CREATE TABLE admin_auth_group_resource_tbl (
	auth_group_id			INTEGER NOT NULL,	--	権限グループID
	resource_id				INTEGER NOT NULL,	--	リソースID
	read_flg				TINYINT(1) DEFAULT 0,		--	閲覧フラグ(0: 禁止  1: 許可)
	create_flg				TINYINT(1) DEFAULT 0,		--	作成フラグ(0: 禁止  1: 許可)
	update_flg				TINYINT(1) DEFAULT 0,		--	編集フラグ(0: 禁止  1: 許可)
	delete_flg				TINYINT(1) DEFAULT 0,		--	削除フラグ(0: 禁止  1: 許可)
	ins_dt					DATETIME NOT NULL,
	upd_dt					TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY ( auth_group_id, resource_id )
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
;


/*
-- 初期データ
*/

-- ユーザ
-- TRUNCATE TABLE admin_user_mst;
INSERT INTO admin_user_mst (
		user_id, user_kbn, user_nm, user_nm_jp, user_pwd, user_email, ins_dt
	) VALUES (
		1, 0, 'admin', '管理者', md5('admin'), 'dummy@localhost', CURRENT_TIMESTAMP
	);

INSERT INTO admin_user_mst (
		user_id, user_kbn, user_nm, user_nm_jp, user_pwd, user_email, ins_dt
	) VALUES (
		2, 1, 'demo', 'デモ', md5('demo'), 'dummy@localhost', CURRENT_TIMESTAMP
	);

-- メニュー
TRUNCATE TABLE admin_menu_mst;
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		1, 'TOP', NULL, 1, '/admin/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		2, '書籍管理', NULL, 2, '/admin/book/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		3, 'ページ管理', NULL, 3, '/admin/page/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		4, '売上管理', NULL, 4, '/admin/sales/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		5, '端末管理', NULL, 5, '/admin/device/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		6, 'ユーザ管理', NULL, 6, '/admin/user/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		7, '集計管理', NULL, 7, '/admin/stat/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		8, 'お問い合わせ管理', NULL, 8, '/admin/contact/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		9, 'その他管理', NULL, 9, '/admin/test/', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		201, 'タイトル管理', 2, 1, '/admin/title/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		202, 'コンテンツ管理', 2, 2, '/admin/contents/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		203, '著作者管理', 2, 3, '/admin/author/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		204, 'ジャンル等の管理', 2, 4, '/admin/category/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		205, '検索ワード管理', 2, 5, '/admin/keyword/', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		401, '売上分析', 4, 1, '/admin/sales/analysis', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		402, '売上出力', 4, 2, '/admin/sales/output', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		501, '対応端末情報管理', 5, 1, '/admin/device/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		502, 'テスト端末情報管理', 5, 2, '/admin/device/testdev', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		601, 'アカウント管理', 6, 1, '/admin/device/', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		602, '権限グループ管理', 6, 2, '/admin/device/testdev', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		701, '購入履歴', 7, 1, '/admin/stat/purchase', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		702, 'ダウンロード履歴', 7, 2, '/admin/stat/download', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		801, 'お問い合わせ管理', 8, 1, '/admin/contact/list', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		802, 'お問い合わせ回答管理', 8, 2, '/admin/contact/reply', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		803, 'お問い合わせテンプレ作成管理', 8,3, '/admin/contact/table', CURRENT_TIMESTAMP
	);

INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		901, 'FAQ管理', 9, 1, '/admin/other/faq', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		902, 'お知らせ管理', 9, 2, '/admin/other/notice', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		903, 'メッセージR管理', 9, 3, '/admin/other/adver', CURRENT_TIMESTAMP
	);
INSERT INTO admin_menu_mst (
		menu_id, menu_title, parent_menu_id, display_order, link_uri, ins_dt
	) VALUES (
		904, '広告管理', 9, 4, '/admin/other/adver', CURRENT_TIMESTAMP
	);

-- TRUNCATE TABLE admin_page_mst;
INSERT INTO admin_page_mst (
		page_id, page_nm, controller_nm, action_nm, resource_id, menu_id, sub_menu_id, ins_dt
	) VALUES (
		1, 'TOPページ', 'index', 'index', NULL, 1, NULL, CURRENT_TIMESTAMP
	);
INSERT INTO admin_page_mst (
		page_id, page_nm, controller_nm, action_nm, resource_id, menu_id, sub_menu_id, ins_dt
	) VALUES (
		2, '書籍管理', 'book', 'index', NULL, 2, NULL, CURRENT_TIMESTAMP
	);
INSERT INTO admin_page_mst (
		page_id, page_nm, controller_nm, action_nm, resource_id, menu_id, sub_menu_id, ins_dt
	) VALUES (
		3, 'ユーザ管理', 'user', 'index', NULL, 6, NULL, CURRENT_TIMESTAMP
	);
INSERT INTO admin_page_mst (
		page_id, page_nm, controller_nm, action_nm, resource_id, menu_id, sub_menu_id, ins_dt
	) VALUES (
		9999, 'テスト', 'test', 'index', NULL, 9, 1, CURRENT_TIMESTAMP
	);

-- TRUNCATE TABLE admin_resource_mst;
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		1, '書籍管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		2, 'ページ管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		3, '売上管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		4, '端末管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		5, 'ユーザ管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		6, '集計管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		7, 'お問い合わせ管理', CURRENT_TIMESTAMP
	);
INSERT INTO admin_resource_mst (
		resource_id, resource_nm, ins_dt
	) VALUES (
		8, 'その他管理', CURRENT_TIMESTAMP
	);

-- TRUNCATE TABLE admin_auth_group_mst;
INSERT INTO admin_auth_group_mst (
		auth_group_id, auth_group_nm, ins_dt
	) VALUES (
		1, '管理者', CURRENT_TIMESTAMP
	);
INSERT INTO admin_auth_group_mst (
		auth_group_id, auth_group_nm, ins_dt
	) VALUES (
		2, 'コンテンツ担当者', CURRENT_TIMESTAMP
	);
INSERT INTO admin_auth_group_mst (
		auth_group_id, auth_group_nm, ins_dt
	) VALUES (
		3, '売上担当者', CURRENT_TIMESTAMP
	);
INSERT INTO admin_auth_group_mst (
		auth_group_id, auth_group_nm, ins_dt
	) VALUES (
		4, '管理者サポート', CURRENT_TIMESTAMP
	);
INSERT INTO admin_auth_group_mst (
		auth_group_id, auth_group_nm, ins_dt
	) VALUES (
		5, 'コンテンツ情報サポート', CURRENT_TIMESTAMP
	);
INSERT INTO admin_auth_group_mst (
		auth_group_id, auth_group_nm, ins_dt
	) VALUES (
		6, '売上情報サポート', CURRENT_TIMESTAMP
	);
