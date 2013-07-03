/*
* 初期データ
*/

/*
* 一般ユーザマスタ
*/
TRUNCATE TABLE user_mst;
INSERT INTO user_mst (
		user_id, user_nm, user_nm_jp, user_pwd, user_mail, set_nm, set_date
	) VALUES (
		1, 'test', 'テスト', md5('test'), 'dummy@localhost', 'ou', CURRENT_TIMESTAMP
	);

/*
* 管理ユーザマスタ
*/
TRUNCATE TABLE admin_user_mst;
INSERT INTO admin_user_mst (
		admin_user_id, admin_user_nm, admin_user_nm_jp, admin_user_pwd, admin_user_mail, set_nm, set_date
	) VALUES (
		1, 'admin', '管理者', md5('admin'), 'dummy@localhost', 'ou', CURRENT_TIMESTAMP
	);

INSERT INTO admin_user_mst (
		admin_user_id, admin_user_nm, admin_user_nm_jp, admin_user_pwd, admin_user_mail, set_nm, set_date
	) VALUES (
		2, 'demo', 'デモ', md5('demo'), 'dummy@localhost', 'ou', CURRENT_TIMESTAMP
	);
