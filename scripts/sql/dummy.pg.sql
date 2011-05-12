/*
-- DUMMY テーブル
*/
DROP TABLE DUMMY;
CREATE TABLE DUMMY (
	ID					SERIAL,							--ID
	INF1				VARCHAR(64),					--文字情報１
	INF2				VARCHAR(64),					--文字情報２
	SET_DATE			TIMESTAMP,						--更新日
	SET_NM			VARCHAR(64) DEFAULT 'system',	--更新者
	CREATE_DATE			TIMESTAMP	DEFAULT CURRENT_TIMESTAMP,	--作成日
	PRIMARY KEY(ID)
);

/*
テスト用ダミーデータ
*/
DELETE FROM DUMMY;
INSERT INTO DUMMY(
		ID, INF1, INF2, SET_NM, SET_DATE
	) VALUES (
		NEXTVAL('dummy_id_seq'), '1行1番', '1行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO DUMMY(
		ID, INF1, INF2, SET_NM, SET_DATE
	) VALUES (
		NEXTVAL('dummy_id_seq'), '2行1番', '2行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO DUMMY(
		ID, INF1, INF2, SET_NM, SET_DATE
	) VALUES (
		NEXTVAL('dummy_id_seq'), '3行1番', '3行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO DUMMY(
		ID, INF1, INF2, SET_NM, SET_DATE
	) VALUES (
		NEXTVAL('dummy_id_seq'), '4行1番', '4行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO DUMMY(
		ID, INF1, INF2, SET_NM, SET_DATE
	) VALUES (
		NEXTVAL('dummy_id_seq'), '5行1番', '5行2番', 'ou', CURRENT_TIMESTAMP
	);    
