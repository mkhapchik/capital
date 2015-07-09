--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.2.280.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 09.07.2015 19:12:04
-- Версия сервера: 5.5.23
-- Версия клиента: 4.1
--


USE capital;

DELIMITER $$

DROP PROCEDURE transactions$$
CREATE PROCEDURE transactions (IN p_date date, IN p_amount decimal(8, 2), IN p_categories_id bigint(20), IN p_account_id bigint(20), IN p_comment varchar(500))
SQL SECURITY INVOKER
COMMENT 'Добавление дохода или расхода'
BEGIN
  DECLARE v_op_sign int;
  DECLARE v_type int;
  DECLARE v_amount_res decimal(8, 2);
  DECLARE v_amount_limit decimal(8, 2);
  DECLARE v_lastId bigint(20);



  SELECT
    c.type INTO v_type
  FROM categories c
  WHERE c.id = p_categories_id;

  IF (v_type = 1) THEN
    SET v_op_sign = 1;
  ELSE
    SET v_op_sign = -1;
  END IF;

  INSERT INTO transactions (date, amount, categories_id, account_id, comment, op_sign)
    VALUES (p_date, p_amount, p_categories_id, p_account_id, p_comment, v_op_sign);

  SET v_lastId = LAST_INSERT_ID();

  CALL update_accounts(p_account_id, p_amount * v_op_sign);
  CALL update_statistic();

  
  SELECT
    v_lastId AS id;
END
$$

DROP PROCEDURE update_accounts$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE update_accounts (IN p_account_id bigint(20), IN p_amount decimal(8, 2))
COMMENT 'Обновление счета'
BEGIN
  DECLARE v_count int;
  DECLARE v_total int;
  DECLARE v_percent int;
  DECLARE v_amount decimal(8, 2);


  SELECT
    COUNT(id) INTO v_count
  FROM transactions t
  WHERE t.account_id = p_account_id;
  SELECT
    COUNT(id) INTO v_total
  FROM transactions;

  SET v_percent = (v_count / v_total) * 100;

  UPDATE account a
  SET a.amount = a.amount + p_amount,
      a.statistic = v_percent
  WHERE a.id = p_account_id;
END
$$

DELIMITER ;