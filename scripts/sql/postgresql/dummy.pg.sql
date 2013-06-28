/*
-- dummy テーブル
*/
DROP TABLE dummy;
CREATE TABLE dummy (
	id					SERIAL,
	inf1				VARCHAR(64),
	inf2				VARCHAR(64),
	set_date			TIMESTAMP,
	set_nm			VARCHAR(64) DEFAULT 'system',
	create_date			TIMESTAMP	DEFAULT CURRENT_TIMESTAMP,
	primary key(ID)
);

/*
テスト用ダミーデータ
*/
DELETE FROM dummy;
INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NEXTVAL('dummy_id_seq'), '1行1番', '1行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NEXTVAL('dummy_id_seq'), '2行1番', '2行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NEXTVAL('dummy_id_seq'), '3行1番', '3行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NEXTVAL('dummy_id_seq'), '4行1番', '4行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NEXTVAL('dummy_id_seq'), '5行1番', '5行2番', 'ou', CURRENT_TIMESTAMP
	);    
