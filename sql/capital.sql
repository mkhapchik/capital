--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.2.280.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 30.07.2015 0:11:37
-- Версия сервера: 5.5.23
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

--
-- Описание для таблицы account
--
DROP TABLE IF EXISTS account;
CREATE TABLE account (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL COMMENT 'Название счета',
  amount DECIMAL(8, 2) NOT NULL DEFAULT 0.00 COMMENT 'Сумма',
  comments VARCHAR(500) DEFAULT NULL COMMENT 'Комментарий к счету',
  f_deleted INT(11) NOT NULL DEFAULT 0 COMMENT 'Флаг 1 - аккаунт удален',
  statistic BIGINT(20) DEFAULT NULL COMMENT 'Статистика употребления в процентах',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 10
AVG_ROW_LENGTH = 16384
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Счета';

--
-- Описание для таблицы categories
--
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL COMMENT 'Название категории',
  type BIGINT(20) NOT NULL COMMENT 'Тип 1 - доход, 0 - расход',
  statistic BIGINT(20) DEFAULT NULL COMMENT 'Статистика употребления в процентах',
  amount_limit DECIMAL(8, 0) DEFAULT NULL COMMENT 'Лимит в месяц',
  f_deleted INT(11) NOT NULL DEFAULT 0 COMMENT 'Флаг удаления 1 -удален 0 - не удален',
  color VARCHAR(255) DEFAULT NULL COMMENT 'Цвет категории (запланировано)',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 37
AVG_ROW_LENGTH = 1365
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Категории расхода и дохода';

--
-- Описание для таблицы menu
--
DROP TABLE IF EXISTS menu;
CREATE TABLE menu (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  label VARCHAR(255) NOT NULL,
  route VARCHAR(255) NOT NULL,
  ord VARCHAR(255) DEFAULT NULL,
  is_active VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 3276
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Меню';

--
-- Описание для таблицы session
--
DROP TABLE IF EXISTS session;
CREATE TABLE session (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  user_id BIGINT(20) NOT NULL COMMENT 'id пользователя',
  token VARCHAR(255) NOT NULL COMMENT 'идентификатор сессии',
  ip VARCHAR(255) NOT NULL COMMENT 'ip пользователя',
  starttime DATETIME NOT NULL COMMENT 'время открытия сессии',
  endtime DATETIME DEFAULT NULL COMMENT 'время завершения сессии',
  closed INT(11) NOT NULL DEFAULT 0 COMMENT 'флаг: 1 - сессия закрыта, 0 - открыта',
  method_close ENUM('automatic','timeout','manually') DEFAULT NULL COMMENT 'флаг: 1 - автоматическое завершение сессии 0 - логаут пользователя',
  last_activity DATETIME DEFAULT NULL COMMENT 'время последней активности',
  PRIMARY KEY (id),
  INDEX IDX_session_user_id (user_id),
  UNIQUE INDEX UK_session_hash (token)
)
ENGINE = INNODB
AUTO_INCREMENT = 107
AVG_ROW_LENGTH = 455
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Сессия пользователей';

--
-- Описание для таблицы tasks
--
DROP TABLE IF EXISTS tasks;
CREATE TABLE tasks (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL COMMENT 'Дата задания',
  amount DECIMAL(8, 2) DEFAULT NULL COMMENT 'Сумма',
  op_sign INT(11) DEFAULT NULL COMMENT '1 - доход, -1 - расход',
  categories_id BIGINT(20) DEFAULT NULL COMMENT 'id категории',
  account_id BIGINT(20) DEFAULT NULL COMMENT 'id счета',
  comment VARBINARY(500) DEFAULT 'автоматическая транзакция' COMMENT 'комментарий',
  is_done INT(11) NOT NULL DEFAULT 0 COMMENT '0 - задача в задании, 1 - задача выполнена',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Задачи для автоматических транзакций';

--
-- Описание для таблицы users
--
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  login VARCHAR(255) NOT NULL COMMENT 'Логин',
  pwd VARCHAR(255) NOT NULL COMMENT 'Хеш пароля',
  counter_failures INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Счетчик неверных авторизаций',
  blocked INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Флаг блокировки: 1 - заблокирован, 0 - активен',
  name VARCHAR(50) NOT NULL COMMENT 'Имя пользователя',
  PRIMARY KEY (id),
  UNIQUE INDEX UK_users_login (login)
)
ENGINE = INNODB
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Пользователи';

--
-- Описание для таблицы arrears
--
DROP TABLE IF EXISTS arrears;
CREATE TABLE arrears (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  lender VARCHAR(255) DEFAULT NULL COMMENT 'кредитор',
  borrower VARCHAR(255) DEFAULT NULL COMMENT 'заемщик',
  amount DECIMAL(8, 2) NOT NULL DEFAULT 0.00 COMMENT 'сумма',
  date_start DATE NOT NULL COMMENT 'дата займа',
  date_stop DATE NOT NULL COMMENT 'дата фактического возврата',
  date_return DATE DEFAULT NULL COMMENT 'дата возврата',
  way TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1-взять в долг, 0- дать в долг',
  account_id BIGINT(20) NOT NULL COMMENT 'счет',
  PRIMARY KEY (id),
  CONSTRAINT FK_arrears_account_id FOREIGN KEY (account_id)
    REFERENCES account(id) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Долги';

--
-- Описание для таблицы plan
--
DROP TABLE IF EXISTS plan;
CREATE TABLE plan (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL COMMENT 'Наименование',
  comment VARCHAR(255) DEFAULT NULL COMMENT 'Комментарий',
  date DATETIME DEFAULT NULL COMMENT 'Дата реализации',
  amount DECIMAL(8, 2) DEFAULT NULL COMMENT 'Сумма',
  categories_id BIGINT(20) NOT NULL COMMENT 'Категория',
  account_id BIGINT(20) DEFAULT NULL COMMENT 'Ссылка на счет накопления',
  PRIMARY KEY (id),
  CONSTRAINT FK_plan_account_id FOREIGN KEY (account_id)
    REFERENCES account(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_plan_categories_id FOREIGN KEY (categories_id)
    REFERENCES categories(id) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Планируемые покупки';

--
-- Описание для таблицы transactions
--
DROP TABLE IF EXISTS transactions;
CREATE TABLE transactions (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL COMMENT 'дата операции',
  amount DECIMAL(8, 2) NOT NULL DEFAULT 0.00 COMMENT 'сумма операции',
  categories_id BIGINT(20) DEFAULT NULL COMMENT 'id категории',
  account_id BIGINT(20) DEFAULT NULL COMMENT 'id счета',
  comment VARCHAR(500) DEFAULT NULL COMMENT 'комментрарий',
  op_sign INT(11) NOT NULL DEFAULT -1 COMMENT '1 - доход, -1- расход',
  PRIMARY KEY (id),
  CONSTRAINT FK_transactions_account_id FOREIGN KEY (account_id)
    REFERENCES account(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_transactions_categories_id FOREIGN KEY (categories_id)
    REFERENCES categories(id) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE = INNODB
AUTO_INCREMENT = 149
AVG_ROW_LENGTH = 309
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Операции по счетам';

DELIMITER $$

--
-- Описание для процедуры auto_transaction
--
DROP PROCEDURE IF EXISTS auto_transaction$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE auto_transaction(IN p_id bigint(20))
  COMMENT 'Автоматическая транзакция - исполнение задания'
BEGIN
  DECLARE v_date date;
  DECLARE v_amount DECIMAL(8,2);
  DECLARE v_categories_id bigint(20);
  DECLARE v_account_id bigint(20);
  DECLARE v_comment varchar(500);
  
  
  CALL transactions(v_date, v_amount, v_categories_id, v_account_id, v_comment);
END
$$

--
-- Описание для процедуры delete_user
--
DROP PROCEDURE IF EXISTS delete_user$$
CREATE PROCEDURE delete_user(IN p_user_id BIGINT)
  SQL SECURITY INVOKER
BEGIN
  DELETE FROM session WHERE user_id=p_user_id;
  DELETE FROM users WHERE id=p_user_id;

END
$$

--
-- Описание для процедуры getOverflow
--
DROP PROCEDURE IF EXISTS getOverflow$$
CREATE PROCEDURE getOverflow(IN p_date_start date, IN p_date_end date, IN p_category_type INT)
  SQL SECURITY INVOKER
  COMMENT 'Переполнение за указанный месяц'
BEGIN
  DECLARE v_date_from DATE;
  DECLARE v_date_to DATE;
  DECLARE v_date_start varchar(10); 
  DECLARE v_date_end varchar(10);

  IF(p_date_start IS NULL) THEN
    SET v_date_start = DATE_FORMAT(CURRENT_DATE() ,'%Y-%m-01 00.00.00');
  ELSE
    SET v_date_start = p_date_start;
  END IF;

  IF(p_date_end IS NULL) THEN
    SET v_date_end = LAST_DAY(v_date_start);
  ELSE
    SET v_date_end = p_date_end;
  END IF;

  IF p_category_type IS NULL THEN
    
    SELECT c.id, c.type, c.statistic, c.name, c.amount_limit, 
  (SELECT ABS(SUM(t.amount*t.op_sign)) FROM transactions t 
    WHERE t.categories_id=c.id AND t.date>=v_date_start 
    AND t.date<=v_date_end
  ) AS sum, 
  (SELECT ABS(SUM(t.amount*t.op_sign))-c.amount_limit FROM  transactions t
    WHERE t.categories_id=c.id AND t.date>=v_date_start 
    AND t.date<=v_date_end 
  ) AS overflow  
  FROM categories c WHERE c.f_deleted=0 ORDER BY c.statistic DESC;
  
  ELSE
    
SELECT c.id, c.type, c.statistic, c.name, c.amount_limit, 
  (SELECT ABS(SUM(t.amount*t.op_sign)) FROM transactions t 
    WHERE t.categories_id=c.id AND t.date>=v_date_start 
    AND t.date<=v_date_end
  ) AS sum, 
  (SELECT ABS(SUM(t.amount*t.op_sign))-c.amount_limit FROM  transactions t
    WHERE t.categories_id=c.id AND t.date>=v_date_start 
    AND t.date<=v_date_end 
  ) AS overflow  
  FROM categories c WHERE c.f_deleted=0 AND c.type=p_category_type ORDER BY c.statistic DESC;
END IF;


END
$$

--
-- Описание для процедуры report_expense
--
DROP PROCEDURE IF EXISTS report_expense$$
CREATE PROCEDURE report_expense(IN p_date_start date, IN p_date_end date)
  SQL SECURITY INVOKER
BEGIN
  DECLARE v_date_start varchar(10); 
  DECLARE v_date_end varchar(10);
  DECLARE v_sum decimal(8, 0);
  DECLARE v_limit_sum decimal(8, 0);


  IF(p_date_start IS NULL) THEN
    SET v_date_start = DATE_FORMAT(CURRENT_DATE() ,'%Y-%m-01 00.00.00');
  ELSE
    SET v_date_start = p_date_start;
  END IF;

  IF(p_date_end IS NULL) THEN
    SET v_date_end = LAST_DAY(v_date_start);
  ELSE
    SET v_date_end = p_date_end;
  END IF;

  SELECT SUM(t.amount) AS sum INTO v_sum FROM transactions t INNER JOIN categories c ON t.categories_id=c.id WHERE t.date>=v_date_start AND t.date<=v_date_end AND c.type=0;
  SELECT SUM(c.amount_limit) AS limit_sum INTO v_limit_sum FROM categories c;

  SELECT v_sum as sum, v_limit_sum AS limit_sum, (v_limit_sum-v_sum) as balance;
END
$$

--
-- Описание для процедуры transactions
--
DROP PROCEDURE IF EXISTS transactions$$
CREATE PROCEDURE transactions(IN p_date date, IN p_amount decimal(8, 2), IN p_categories_id bigint(20), IN p_account_id bigint(20), IN p_comment varchar(500))
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

--
-- Описание для процедуры update_accounts
--
DROP PROCEDURE IF EXISTS update_accounts$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE update_accounts(IN p_account_id bigint(20), IN p_amount decimal(8, 2))
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

--
-- Описание для процедуры update_statistic
--
DROP PROCEDURE IF EXISTS update_statistic$$
CREATE PROCEDURE update_statistic()
  SQL SECURITY INVOKER
  COMMENT 'Обновление статистики категории'
BEGIN
  DECLARE v_category_id int;
  DECLARE v_count int;
  DECLARE v_total int;
  DECLARE v_type int;
  DECLARE v_op_sign int;
  DECLARE v_percent int;
  DECLARE done INT DEFAULT 0;
  DECLARE cur1 CURSOR FOR SELECT id FROM categories;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

  OPEN cur1;

  REPEAT
    FETCH cur1 INTO v_category_id;
    IF NOT done THEN 
      SELECT type INTO v_type FROM categories WHERE id=v_category_id;
      IF (v_type=1) THEN SET v_op_sign = 1;
      ELSE SET v_op_sign = -1;   
      END IF;
      
      SELECT COUNT(id) INTO v_count FROM transactions WHERE categories_id=v_category_id;
      SELECT COUNT(id) INTO v_total FROM transactions WHERE op_sign=v_op_sign;
    
      SET v_percent = (v_count/v_total) * 100;
    
      UPDATE categories SET statistic=v_percent WHERE id=v_category_id;
    END IF; 
  UNTIL done END REPEAT;
END
$$

DELIMITER ;

DELIMITER $$

--
-- Описание для триггера session_OnInsert
--
DROP TRIGGER IF EXISTS session_OnInsert$$
CREATE 
	DEFINER = 'root'@'localhost'
TRIGGER session_OnInsert
	BEFORE INSERT
	ON session
	FOR EACH ROW
BEGIN
  -- SET NEW.starttime = IFNULL(NEW.starttime, NOW());
  SET NEW.starttime = NOW();
END
$$

DELIMITER ;

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;