--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.3.341.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 14.05.2015 16:58:44
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
CREATE TABLE account (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL COMMENT 'Название счета',
  amount DECIMAL(8, 2) NOT NULL DEFAULT 0.00 COMMENT 'Сумма',
  comments VARCHAR(500) DEFAULT NULL COMMENT 'Комментарий к счету',
  f_deleted INT(11) NOT NULL DEFAULT 0 COMMENT 'Флаг 1 - аккаунт удален',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 16384
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Счета';

--
-- Описание для таблицы categories
--
CREATE TABLE categories (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL COMMENT 'Название категории',
  type BIGINT(20) NOT NULL COMMENT 'Тип 1 - доход, 0 - расход',
  statistic BIGINT(20) DEFAULT NULL COMMENT 'Статистика употребления в процентах',
  amount_limit DECIMAL(8, 0) DEFAULT NULL COMMENT 'Лимит в месяц',
  f_deleted INT(11) NOT NULL DEFAULT 0 COMMENT 'Флаг удаления 1 -удален 0 - не удален',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 11
AVG_ROW_LENGTH = 1820
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Категории расхода и дохода';

--
-- Описание для таблицы menu
--
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
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Меню';

--
-- Описание для таблицы tasks
--
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
-- Описание для таблицы arrears
--
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
AUTO_INCREMENT = 23
AVG_ROW_LENGTH = 744
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Операции по счетам';

DELIMITER $$

--
-- Описание для процедуры auto_transaction
--
CREATE PROCEDURE auto_transaction(IN p_id bigint(20))
  SQL SECURITY INVOKER
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
-- Описание для процедуры getOverflow
--
CREATE PROCEDURE getOverflow(IN p_date date, IN p_category_type INT)
  SQL SECURITY INVOKER
  COMMENT 'Переполнение за указанный месяц'
BEGIN
  DECLARE v_date_from DATE;
  DECLARE v_date_to DATE;

  IF(p_date IS NULL) THEN
   SET p_date = CURRENT_DATE();
  END IF;

  SET v_date_from = DATE_FORMAT(p_date ,'%Y-%m-01 00.00.00');
  SET v_date_to = LAST_DAY(p_date);

  IF p_category_type IS NULL THEN
    SELECT c.id, c.type, c.statistic, c.name, c.amount_limit, ABS(SUM(t.amount*t.op_sign)) AS sum, ABS(SUM(t.amount*t.op_sign))-c.amount_limit AS overflow
    FROM categories c 
    LEFT JOIN transactions t ON c.id=t.categories_id 
    WHERE ((t.date>=DATE_FORMAT(p_date ,'%Y-%m-01 00.00.00') AND t.date<LAST_DAY(p_date)) OR t.date IS NULL)  AND c.f_deleted=0
    GROUP BY c.id
    ORDER BY c.statistic DESC;
  ELSE
    SELECT c.id, c.type, c.statistic, c.name, c.amount_limit, ABS(SUM(t.amount*t.op_sign)) AS sum, ABS(SUM(t.amount*t.op_sign))-c.amount_limit AS overflow
    FROM categories c 
    LEFT JOIN transactions t ON c.id=t.categories_id 
    WHERE ((t.date>=DATE_FORMAT(p_date ,'%Y-%m-01 00.00.00') AND t.date<LAST_DAY(p_date)) OR t.date IS NULL) AND c.type=p_category_type AND c.f_deleted=0
    GROUP BY c.id
    ORDER BY c.statistic DESC;
END IF;


END
$$

--
-- Описание для процедуры transactions
--
CREATE PROCEDURE transactions(IN p_date DATE, IN p_amount DECIMAL(8,2), IN p_categories_id bigint(20), IN p_account_id bigint(20), IN p_comment varchar(500))
  SQL SECURITY INVOKER
  COMMENT 'Добавление дохода или расхода'
BEGIN
  DECLARE v_op_sign int;
  DECLARE v_type int;
  DECLARE v_amount_res decimal(8,2);
  DECLARE v_amount_limit decimal(8,2);
  
  SELECT c.type INTO v_type FROM categories c WHERE c.id=p_categories_id;
  
  IF(v_type=1) THEN
    SET v_op_sign=1;
  ELSE
    SET v_op_sign=-1;
  END IF;

  INSERT INTO transactions (date, amount, categories_id, account_id, comment, op_sign) VALUES(p_date, p_amount, p_categories_id, p_account_id, p_comment, v_op_sign);
  CALL update_accounts(p_account_id);
  CALL update_statistic();

  /* Сделать процедуру подсчета превышений по каждому лимиту
  SELECT ABS(SUM(amount*op_sign)) INTO v_amount_res FROM transactions WHERE categories_id=p_categories_id;
  SELECT amount_limit INTO v_amount_limit FROM categories WHERE id=p_categories_id;
  
  SELECT v_amount_res, v_amount_limit, IF(v_amount_res>v_amount_limit, 1, 0) AS overflow;
  */
END
$$

--
-- Описание для процедуры update_accounts
--
CREATE DEFINER = 'root'@'localhost'
PROCEDURE update_accounts(IN p_account_id bigint(20))
  COMMENT 'Обновление счета'
BEGIN
  DECLARE v_amount decimal(8,2);
  SELECT SUM(t.amount*t.op_sign) INTO v_amount FROM transactions t WHERE t.account_id=p_account_id;
  UPDATE account a SET a.amount=v_amount WHERE a.id=p_account_id;
END
$$

--
-- Описание для процедуры update_statistic
--
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

-- 
-- Вывод данных для таблицы account
--
INSERT INTO account VALUES
(1, 'Наличные', -1301.00, 'Тестовый счет', 0),
(2, 'Карта', 300.00, 'Карта для накопления на яхту', 0);

-- 
-- Вывод данных для таблицы categories
--
INSERT INTO categories VALUES
(1, 'Категория дохода', 1, 80, NULL, 0),
(2, 'Категория расхода', 0, 29, 500, 0),
(4, 'Категория расхода 2', 0, 71, 8000, 0),
(5, 'Категория дохода 2', 1, 20, NULL, 0),
(6, 'еуые', 1, 0, 0, 1),
(7, 'test', 1, 0, 0, 1),
(8, 'test3333333', 1, NULL, NULL, 1),
(9, 'test2', 1, 0, 0, 1),
(10, 'test', 1, NULL, NULL, 1);

-- 
-- Вывод данных для таблицы menu
--
INSERT INTO menu VALUES
(1, 'account', 'Счета', 'account/default', '1', '1'),
(2, 'income', 'Категории дохода', 'categories/income', '2', '1'),
(3, 'expense', 'Категории расхода', 'categories/expense', '3', '1');

-- 
-- Вывод данных для таблицы tasks
--

-- Таблица capital.tasks не содержит данных

-- 
-- Вывод данных для таблицы arrears
--

-- Таблица capital.arrears не содержит данных

-- 
-- Вывод данных для таблицы plan
--

-- Таблица capital.plan не содержит данных

-- 
-- Вывод данных для таблицы transactions
--
INSERT INTO transactions VALUES
(1, '2015-03-13', 100.00, 2, 1, 'тест расхода', -1),
(2, '2015-03-13', 1000.00, 1, 1, 'тест доход', 1),
(3, '2015-03-13', 200.00, 4, 1, NULL, -1),
(4, '2015-03-13', 200.00, 4, 1, 'ttt', -1),
(5, '2015-03-13', 500.00, 1, 1, NULL, 1),
(6, '2015-03-13', 100.00, 4, 1, NULL, -1),
(7, '2015-03-13', 3000.00, 1, 1, 'ttt', 1),
(8, '2015-03-13', 3000.00, 1, 1, 'ttt', 1),
(9, '2015-03-13', 1000.00, 2, 1, 'ttt', -1),
(10, '2015-03-13', 1000.00, 5, 1, 'ttt', 1),
(11, '2015-03-13', 300.00, 2, 1, 'ttt', -1),
(12, '2015-03-13', 300.00, 2, 1, 'ttt', -1),
(13, '2015-03-13', 300.00, 2, 1, 'ttt', -1),
(14, '2015-03-13', 300.00, 4, 1, 'ttt', -1),
(15, '2015-03-13', 1.00, 4, 1, 'ttt', -1),
(16, '2015-03-13', 1000.00, 4, 1, 'ttt', -1),
(17, '2015-03-13', 1000.00, 4, 1, 'ttt', -1),
(18, '2015-03-13', 1000.00, 4, 1, 'ttt', -1),
(19, '2015-03-13', 1000.00, 4, 1, 'ttt', -1),
(20, '2015-03-13', 1000.00, 4, 1, 'ttt', -1),
(21, '2015-03-13', 1000.00, 4, 1, 'ttt', -1),
(22, '2015-03-13', 1000.00, 4, 1, 'ttt', -1);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;