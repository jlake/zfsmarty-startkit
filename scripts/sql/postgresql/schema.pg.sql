/*
* データベース定義（table, index, sequence, view など）
*/

-- セッション管理用テーブル
CREATE TABLE session (
	id char(32),
	modified int,
	lifetime int,
	data text,
	PRIMARY KEY (id)
);

-- 一般ユーザマスタ
-- DROP TABLE user_mst;
CREATE TABLE user_mst (
	user_id					SMALLINT,
	user_nm					VARCHAR(32),
	user_nm_jp				VARCHAR(32),
	user_pwd				VARCHAR(32),
	user_mail				VARCHAR(64),
	last_login_date			TIMESTAMP,
	set_date				TIMESTAMP,
	set_nm					VARCHAR(64) DEFAULT 'system',
	create_date				TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(user_id)
);

-- 管理者ユーザマスタ
-- DROP TABLE admin_user_mst;
CREATE TABLE admin_user_mst (
	admin_user_id			SMALLINT,
	admin_user_nm			VARCHAR(32),
	admin_user_nm_jp		VARCHAR(32),
	admin_user_pwd			VARCHAR(32),
	admin_user_mail			VARCHAR(64),
	last_login_date			TIMESTAMP,
	set_date				TIMESTAMP,
	set_nm					VARCHAR(64) DEFAULT 'system',
	create_date				TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(admin_user_id)
);

