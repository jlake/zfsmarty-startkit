/*
-- dummy テーブル
*/
DROP TABLE IF EXISTS dummy;
CREATE TABLE dummy (
	id 					MEDIUMINT NOT NULL AUTO_INCREMENT,
	inf1				VARCHAR(64),					--文字情報１
	inf2				VARCHAR(64),					--文字情報２
	set_date			TIMESTAMP,						--更新日
	set_nm				VARCHAR(64) DEFAULT 'system',	--更新者
	create_date			TIMESTAMP,	--作成日
	PRIMARY KEY(id)
);


/*
テスト用ダミーデータ
*/
DELETE FROM dummy;
INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NULL, '1行1番', '1行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NULL, '2行1番', '2行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NULL, '3行1番', '3行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NULL, '4行1番', '4行2番', 'ou', CURRENT_TIMESTAMP
	);    

INSERT INTO dummy(
		id, inf1, inf2, set_nm, set_date
	) VALUES (
		NULL, '5行1番', '5行2番', 'ou', CURRENT_TIMESTAMP
	);    
