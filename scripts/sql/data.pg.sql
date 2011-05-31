/*
* 初期データ
*/

/*
* 一般ユーザマスタ
*/
TRUNCATE TABLE USER_MST;
INSERT INTO USER_MST (
		USER_ID, USER_NM, USER_NM_JP, USER_PWD, USER_MAIL, SET_NM, SET_DATE
	) VALUES (
		1, 'test', 'テスト', md5('test'), 'dummy@localhost', 'ou', CURRENT_TIMESTAMP
	);

/*
* 管理ユーザマスタ
*/
TRUNCATE TABLE ADMIN_USER_MST;
INSERT INTO ADMIN_USER_MST (
		ADMIN_USER_ID, ADMIN_USER_NM, ADMIN_USER_NM_JP, ADMIN_USER_PWD, ADMIN_USER_MAIL, SET_NM, SET_DATE
	) VALUES (
		1, 'admin', '管理者', md5('admin'), 'dummy@localhost', 'ou', CURRENT_TIMESTAMP
	);

INSERT INTO ADMIN_USER_MST (
		ADMIN_USER_ID, ADMIN_USER_NM, ADMIN_USER_NM_JP, ADMIN_USER_PWD, ADMIN_USER_MAIL, SET_NM, SET_DATE
	) VALUES (
		2, 'demo', 'デモ', md5('demo'), 'dummy@localhost', 'ou', CURRENT_TIMESTAMP
	);