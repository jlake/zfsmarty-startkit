/*
* データベース定義（table, index, sequence, view など）
*/

/*
* 一般ユーザマスタ
*/
-- DROP TABLE USER_MST;
CREATE TABLE USER_MST (
	USER_ID					SMALLINT,							--ユーザID
	USER_NM					VARCHAR(32),						--ユーザ名
	USER_NM_JP				VARCHAR(32),						--氏名
	USER_PWD				VARCHAR(32),						--パスワード
	USER_MAIL				VARCHAR(64),						--メールアドレス
	LAST_LOGIN_DATE			TIMESTAMP,						--最近ログイン日時
	SET_DATE				TIMESTAMP,							--更新日
	SET_NM					VARCHAR(64) DEFAULT 'system',			--更新者
	CREATE_DATE				TIMESTAMP DEFAULT CURRENT_TIMESTAMP,	--作成日
	PRIMARY KEY(USER_ID)
);

/*
* 管理者ユーザマスタ
*/
-- DROP TABLE ADMIN_USER_MST;
CREATE TABLE ADMIN_USER_MST (
	ADMIN_USER_ID			SMALLINT,							--ユーザID
	ADMIN_USER_NM			VARCHAR(32),						--ユーザ名
	ADMIN_USER_NM_JP		VARCHAR(32),						--氏名
	ADMIN_USER_PWD			VARCHAR(32),						--パスワード
	ADMIN_USER_MAIL			VARCHAR(64),						--メールアドレス
	LAST_LOGIN_DATE			TIMESTAMP,						--最近ログイン日時
	SET_DATE				TIMESTAMP,						--更新日
	SET_NM					VARCHAR(64) DEFAULT 'system',	--更新者
	CREATE_DATE				TIMESTAMP DEFAULT CURRENT_TIMESTAMP,	--作成日
	PRIMARY KEY(ADMIN_USER_ID)
);
