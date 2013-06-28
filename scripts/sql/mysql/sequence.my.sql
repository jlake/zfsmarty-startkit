/*
* MYSQL用シーケンステーブルと関数定義
*/

-- シーケンス管理用テーブル
DROP TABLE IF EXISTS sequence;
CREATE TABLE sequence (
	name VARCHAR(50) NOT NULL,
	current_value INT NOT NULL,
	increment INT NOT NULL DEFAULT 1,
	PRIMARY KEY (name)
) ENGINE=InnoDB;

-- 現在値を取得用関数
DROP FUNCTION IF EXISTS currval;
DELIMITER $
CREATE FUNCTION currval (seq_name VARCHAR(50))
	RETURNS INTEGER
	LANGUAGE SQL
	DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
	COMMENT ''
BEGIN
	DECLARE value INTEGER;
	SET value = 0;
	SELECT current_value INTO value
		FROM sequence
		WHERE name = seq_name;
	RETURN value;
END
$
DELIMITER ;

-- 次の値を取得用関数
DROP FUNCTION IF EXISTS nextval;
DELIMITER $
CREATE FUNCTION nextval (seq_name VARCHAR(50))
	RETURNS INTEGER
	LANGUAGE SQL
	DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
	COMMENT ''
BEGIN
	UPDATE sequence
	SET current_value = current_value + increment
	WHERE name = seq_name;
	RETURN currval(seq_name);
END
$
DELIMITER ;

-- 現在値を更新用関数
DROP FUNCTION IF EXISTS setval;
DELIMITER $
CREATE FUNCTION setval (seq_name VARCHAR(50), value INTEGER)
	RETURNS INTEGER
	LANGUAGE SQL
	DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
	COMMENT ''
BEGIN
	UPDATE sequence
	SET current_value = value
	WHERE name = seq_name;
	RETURN currval(seq_name);
END
$
DELIMITER ;

/*
-- テスト
INSERT INTO sequence VALUES ('TestSeq', 0, 1);
SELECT SETVAL('TestSeq', 10);
SELECT CURRVAL('TestSeq');
SELECT NEXTVAL('TestSeq');
*/