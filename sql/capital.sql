--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.3.358.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 06.10.2015 16:53:00
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
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 16384
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Счета';

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
AUTO_INCREMENT = 16
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Категории расхода и дохода';

--
-- Описание для таблицы ip_allowed_list
--
DROP TABLE IF EXISTS ip_allowed_list;
CREATE TABLE ip_allowed_list (
  id BIGINT(20) NOT NULL AUTO_INCREMENT,
  ip VARCHAR(255) NOT NULL,
  is_active INT(11) NOT NULL DEFAULT 1 COMMENT 'Флаг активности ip адреса в списке',
  PRIMARY KEY (id),
  UNIQUE INDEX UK_ip_allowed_list_ip (ip)
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Список разрешенных ip адресов';

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
AVG_ROW_LENGTH = 2730
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Меню';

--
-- Описание для таблицы pages
--
DROP TABLE IF EXISTS pages;
CREATE TABLE pages (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL COMMENT 'системное имя',
  label VARCHAR(255) DEFAULT NULL COMMENT 'название',
  route_name VARCHAR(255) DEFAULT NULL COMMENT 'имя маршрута',
  route_params VARCHAR(255) DEFAULT NULL COMMENT 'параметры маршрута',
  pages_type_id INT(11) DEFAULT NULL COMMENT 'тип страницы, null - системная',
  is_active INT(11) NOT NULL DEFAULT 1 COMMENT 'флаг активности страницы',
  is_delete INT(11) NOT NULL DEFAULT 0 COMMENT 'флаг удаления страницы',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 2730
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы pages_menu
--
DROP TABLE IF EXISTS pages_menu;
CREATE TABLE pages_menu (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  label VARCHAR(255) NOT NULL,
  is_active INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы pages_menu_items
--
DROP TABLE IF EXISTS pages_menu_items;
CREATE TABLE pages_menu_items (
  id INT(11) NOT NULL AUTO_INCREMENT,
  label VARCHAR(255) DEFAULT NULL,
  pages_menu_id BINARY(20) DEFAULT NULL,
  page_id INT(11) DEFAULT NULL,
  parent_item_id INT(11) DEFAULT NULL,
  ord VARCHAR(255) DEFAULT NULL,
  is_active VARCHAR(255) DEFAULT NULL,
  uri VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 11
AVG_ROW_LENGTH = 2340
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Меню';

--
-- Описание для таблицы pages_privileges
--
DROP TABLE IF EXISTS pages_privileges;
CREATE TABLE pages_privileges (
  id INT(11) DEFAULT NULL,
  name VARCHAR(255) DEFAULT NULL COMMENT 'системное имя (как правило совпадает с action)',
  label VARCHAR(255) DEFAULT NULL COMMENT 'визуальное название',
  pages_type_id INT(11) DEFAULT NULL COMMENT 'id типа страницы для общих привилегий конкретного типа',
  pages_id INT(11) DEFAULT NULL COMMENT 'id страницы, в случае уникальной привилегии страницы',
  allow INT(11) NOT NULL DEFAULT 0 COMMENT 'разрешение 1 - разрешено 0 - запрещено'
)
ENGINE = INNODB
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Привилегии страниц';

--
-- Описание для таблицы pages_properties
--
DROP TABLE IF EXISTS pages_properties;
CREATE TABLE pages_properties (
  id INT(11) NOT NULL AUTO_INCREMENT,
  page_id INT(11) NOT NULL,
  title VARCHAR(255) DEFAULT NULL,
  content LONGTEXT DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Общие свойства страниц';

--
-- Описание для таблицы pages_type
--
DROP TABLE IF EXISTS pages_type;
CREATE TABLE pages_type (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  label VARCHAR(255) DEFAULT NULL,
  route_name VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Типы страниц';

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
AUTO_INCREMENT = 204
AVG_ROW_LENGTH = 121
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
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 2730
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Операции по счетам';

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
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Пользователи';

DELIMITER $$

--
-- Описание для процедуры auto_transaction
--
DROP PROCEDURE IF EXISTS auto_transaction$$
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
CREATE PROCEDURE transactions(IN p_date DATE, IN p_amount DECIMAL(8,2), IN p_categories_id bigint(20), IN p_account_id bigint(20), IN p_comment varchar(500))
  SQL SECURITY INVOKER
  COMMENT 'Добавление дохода или расхода'
BEGIN
  DECLARE v_op_sign int;
  DECLARE v_type int;
  DECLARE v_amount_res decimal(8,2);
  DECLARE v_amount_limit decimal(8,2);
  DECLARE v_lastId bigint(20);
                    
                   
  
  SELECT c.type INTO v_type FROM categories c WHERE c.id=p_categories_id;
  
  IF(v_type=1) THEN
    SET v_op_sign=1;
  ELSE
    SET v_op_sign=-1;
  END IF;

  INSERT INTO transactions (date, amount, categories_id, account_id, comment, op_sign) VALUES(p_date, p_amount, p_categories_id, p_account_id, p_comment, v_op_sign);
  
  SET v_lastId = LAST_INSERT_ID();
  
  CALL update_accounts(p_account_id);
  CALL update_statistic();

  /* Сделать процедуру подсчета превышений по каждому лимиту
  SELECT ABS(SUM(amount*op_sign)) INTO v_amount_res FROM transactions WHERE categories_id=p_categories_id;
  SELECT amount_limit INTO v_amount_limit FROM categories WHERE id=p_categories_id;
  
  SELECT v_amount_res, v_amount_limit, IF(v_amount_res>v_amount_limit, 1, 0) AS overflow;
  */
  SELECT v_lastId as id;
END
$$

--
-- Описание для процедуры update_accounts
--
DROP PROCEDURE IF EXISTS update_accounts$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE update_accounts(IN p_account_id bigint(20))
  COMMENT 'Обновление счета'
BEGIN
  DECLARE v_count int;
  DECLARE v_total int;
  DECLARE v_percent int;
  DECLARE v_amount decimal(8,2);
   
  SELECT SUM(t.amount*t.op_sign),COUNT(id) INTO v_amount,v_count FROM transactions t WHERE t.account_id=p_account_id;      
  SELECT COUNT(id) INTO v_total FROM transactions;
   
  SET v_percent = (v_count/v_total) * 100;
    
  UPDATE account a SET a.amount=v_amount, a.statistic=v_percent WHERE a.id=p_account_id;
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

-- 
-- Вывод данных для таблицы account
--
INSERT INTO account VALUES
(4, 'Счет 1', -1820.00, '', 0, 100);

-- 
-- Вывод данных для таблицы arrears
--

-- Таблица capital.arrears не содержит данных

-- 
-- Вывод данных для таблицы categories
--
INSERT INTO categories VALUES
(14, 'Категория расхода 1', 0, 33, 400, 0, NULL),
(15, 'Категория расхода 2', 0, NULL, 0, 0, NULL);

-- 
-- Вывод данных для таблицы ip_allowed_list
--
INSERT INTO ip_allowed_list VALUES
(1, '192.168.20.50', 1),
(2, '127.0.0.1', 1);

-- 
-- Вывод данных для таблицы menu
--
INSERT INTO menu VALUES
(1, 'account', 'Счета', 'account/default', '4', '1'),
(2, 'income', 'Категории дохода', 'categories/income', '5', '1'),
(3, 'expense', 'Категории расхода', 'categories/expense', '6', '1'),
(4, 'transactions_income', 'Доходы', 'transactions/income', '2', '1'),
(5, 'transactions_expense', 'Расходы', 'transactions/expense', '1', '1'),
(6, 'table_expen', 'Сравнение расходов', 'reports/table_expen', '3', '1');

-- 
-- Вывод данных для таблицы pages
--
INSERT INTO pages VALUES
(1, 'account', 'Счета', 'account/default', NULL, 1, 1, 0),
(2, 'income', 'Категории дохода', 'categories/income', NULL, 1, 1, 0),
(3, 'expense', 'Категории расхода', 'categories/expense', NULL, 1, 1, 0),
(4, 'transactions_income', 'Доходы', 'transactions/income', NULL, 1, 1, 0),
(5, 'transactions_expense', 'Расходы', 'transactions/expense', NULL, 1, 1, 0),
(6, 'table_expen', 'Сравнение расходов', 'reports/table_expen', NULL, 1, 1, 0);

-- 
-- Вывод данных для таблицы pages_menu
--
INSERT INTO pages_menu VALUES
(1, 'main', 'Основное меню', 0),
(2, 'capital', 'Мой капитал', 1);

-- 
-- Вывод данных для таблицы pages_menu_items
--
INSERT INTO pages_menu_items VALUES
(1, 'Счета', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 1, 0, '5', '1', NULL),
(5, 'Категории дохода', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 2, 0, '4', '1', NULL),
(6, 'Категории расхода', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 3, 0, '3', '1', NULL),
(7, 'Доходы', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 4, 0, '2', '1', NULL),
(8, 'Расходы', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 5, 0, '1', '1', NULL),
(9, 'Отчеты', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', NULL, 0, '6', '1', NULL),
(10, 'Сравнение расходов', '2\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0', 6, 9, '1', '1', NULL);

-- 
-- Вывод данных для таблицы pages_privileges
--

-- Таблица capital.pages_privileges не содержит данных

-- 
-- Вывод данных для таблицы pages_properties
--

-- Таблица capital.pages_properties не содержит данных

-- 
-- Вывод данных для таблицы pages_type
--
INSERT INTO pages_type VALUES
(1, 'content', 'Страница контента', 'content'),
(2, 'news', 'Страница новости', 'news');

-- 
-- Вывод данных для таблицы plan
--

-- Таблица capital.plan не содержит данных

-- 
-- Вывод данных для таблицы session
--
INSERT INTO session VALUES
(69, 1, '241f8b0e9a7174c3349799d792a9d5f3', '127.0.0.1', '2015-07-24 10:46:14', '2015-07-24 11:03:35', 1, 'timeout', '2015-07-24 10:46:33'),
(70, 1, 'bd696a660f74c163faf4ff4617b4291d', '127.0.0.1', '2015-07-24 11:40:10', '2015-07-24 11:40:28', 1, 'timeout', '2015-07-24 11:40:10'),
(71, 1, '33340c646da9cc23dcef38790ff39bfa', '127.0.0.1', '2015-07-24 11:40:41', '2015-07-24 15:51:06', 1, 'timeout', '2015-07-24 11:40:54'),
(72, 1, '86584ca70544b7f696f8d676bea1d987', '127.0.0.1', '2015-07-24 18:15:44', '2015-07-24 18:17:04', 1, 'timeout', '2015-07-24 18:15:52'),
(73, 1, 'e48d3948df42eb0656344e49696521ab', '127.0.0.1', '2015-07-28 08:42:56', '2015-07-28 15:03:45', 1, 'timeout', '2015-07-28 15:03:32'),
(74, 1, 'e4809f66d65f68ba6abbae5eb6a09931', '127.0.0.1', '2015-07-28 15:04:17', '2015-07-28 15:07:19', 1, 'timeout', '2015-07-28 15:07:00'),
(75, 1, '69f75ff76071213485faa1c6696e744a', '127.0.0.1', '2015-07-28 15:07:23', '2015-07-28 15:07:45', 1, 'timeout', '2015-07-28 15:07:29'),
(76, 1, 'c18ae470d2228a4982c10f6a9aa23d68', '127.0.0.1', '2015-07-28 15:08:23', '2015-07-28 15:12:57', 1, 'timeout', '2015-07-28 15:12:22'),
(77, 1, '7eb78d07e404b177ae045966827ee6b0', '127.0.0.1', '2015-07-28 15:13:00', '2015-07-28 15:13:33', 1, 'timeout', '2015-07-28 15:13:07'),
(78, 1, '9482569e39494897c2b5f9f81a86c6ea', '127.0.0.1', '2015-07-28 15:13:36', '2015-07-28 15:15:41', 1, 'timeout', '2015-07-28 15:15:31'),
(79, 1, '471545d4dc1b358df22c67b7100d6356', '127.0.0.1', '2015-07-28 15:15:45', '2015-07-28 15:21:00', 1, 'timeout', '2015-07-28 15:20:36'),
(80, 1, 'c55e84e63873448837a96188cdb0da4d', '127.0.0.1', '2015-07-28 15:21:05', '2015-07-28 15:21:29', 1, 'timeout', '2015-07-28 15:21:05'),
(81, 1, '51a7b036e9ceb19096324ee88be9de8e', '127.0.0.1', '2015-07-28 15:36:50', '2015-07-28 15:37:00', 1, 'timeout', '2015-07-28 15:36:51'),
(82, 1, '8e6606b48a2ce356ebebb4376e41be4d', '127.0.0.1', '2015-07-28 15:37:06', '2015-07-28 15:37:07', 1, 'manually', '2015-07-28 15:37:06'),
(83, 1, 'f46af1a724afbeb8aa5d230675be5984', '127.0.0.1', '2015-07-28 15:37:31', '2015-07-29 08:35:26', 1, 'timeout', '2015-07-28 15:37:31'),
(84, 1, 'd5fa7a9292c976bf8c46e26401e48141', '127.0.0.1', '2015-07-29 08:46:02', '2015-07-29 08:46:54', 1, 'timeout', '2015-07-29 08:46:02'),
(85, 1, '5a37e7683405ac4a7846dc25ee21da69', '127.0.0.1', '2015-07-29 08:46:58', '2015-07-29 08:48:00', 1, 'manually', '2015-07-29 08:46:58'),
(86, 2, '568e87ba912820aedeeabfc353ec1964', '127.0.0.1', '2015-07-29 08:53:02', '2015-07-29 08:54:02', 1, 'timeout', '2015-07-29 08:53:02'),
(87, 1, '5190441d1c45b5bddfc63ee27ba76181', '127.0.0.1', '2015-07-29 08:54:30', '2015-07-29 08:55:09', 1, 'timeout', '2015-07-29 08:54:30'),
(88, 1, 'fe127cfcd3e3416681c6e00b9150a253', '127.0.0.1', '2015-07-29 08:56:25', '2015-07-29 08:57:16', 1, 'timeout', '2015-07-29 08:56:25'),
(89, 1, '218b3dd6c510f6fdd40ac6598beff471', '127.0.0.1', '2015-07-29 08:57:29', '2015-07-29 08:59:41', 1, 'timeout', '2015-07-29 08:57:57'),
(90, 1, 'a6dbb78f259d7429b524f53b42089c99', '127.0.0.1', '2015-07-29 08:59:52', '2015-07-29 09:00:26', 1, 'timeout', '2015-07-29 08:59:52'),
(91, 1, '6e0a04252c9c251f2b260150500654f7', '127.0.0.1', '2015-07-29 09:00:44', '2015-07-29 09:01:59', 1, 'timeout', '2015-07-29 09:00:44'),
(92, 1, 'b782239acd01666f4f379551dadb9110', '127.0.0.1', '2015-07-29 09:02:03', '2015-07-29 09:03:04', 1, 'timeout', '2015-07-29 09:02:03'),
(93, 1, '93614ee9586997f44e7a0696e01b0a1b', '127.0.0.1', '2015-07-29 09:03:08', '2015-07-29 09:03:28', 1, 'timeout', '2015-07-29 09:03:08'),
(94, 1, 'cea310ac5fbec67f083c7a5682139044', '127.0.0.1', '2015-07-29 09:03:28', '2015-07-29 09:03:49', 1, 'timeout', '2015-07-29 09:03:29'),
(95, 1, '21cff8ef79ebfbf6c589c55d2fe0165d', '127.0.0.1', '2015-07-29 09:03:49', '2015-07-29 09:04:37', 1, 'timeout', '2015-07-29 09:03:49'),
(96, 1, 'f1581e3f9b4defb0e4053cd0ec4bbd69', '127.0.0.1', '2015-07-29 09:04:38', '2015-07-29 09:05:27', 1, 'timeout', '2015-07-29 09:04:39'),
(97, 1, '067ffcb39b9526ca1cd119a015deccf4', '127.0.0.1', '2015-07-29 09:07:08', '2015-07-29 09:07:22', 1, 'timeout', '2015-07-29 09:07:09'),
(98, 1, '63c5c73ebff9880444cae19be1e8ad56', '127.0.0.1', '2015-07-29 09:08:56', '2015-07-29 09:09:57', 1, 'timeout', '2015-07-29 09:08:56'),
(99, 1, '48dc02b5384a4dc8126a4f4c5f4f504d', '127.0.0.1', '2015-07-29 09:16:58', '2015-07-29 09:17:02', 1, 'manually', '2015-07-29 09:16:58'),
(100, 1, 'b708ac702b49eb7d346aa868733c7771', '127.0.0.1', '2015-07-29 09:18:17', '2015-07-29 09:23:22', 1, 'timeout', '2015-07-29 09:18:17'),
(101, 1, 'a0a4945dd95e1530a9e9c3453ed8bb50', '127.0.0.1', '2015-07-29 09:23:28', '2015-07-29 09:24:00', 1, 'manually', '2015-07-29 09:23:57'),
(102, 1, 'd636e7697b49e68e36a6dfa6198efd85', '127.0.0.1', '2015-07-29 16:42:57', '2015-07-29 17:45:12', 1, 'timeout', '2015-07-29 16:42:59'),
(103, 2, '16493d8c253feeb0175aaa8fb2efb098', '127.0.0.1', '2015-07-29 17:45:19', '2015-07-29 17:57:21', 1, 'manually', '2015-07-29 17:56:29'),
(104, 1, 'f9ff22d969f14826680f61ad9979c8cf', '127.0.0.1', '2015-07-29 17:57:26', '2015-07-30 09:17:06', 1, 'timeout', '2015-07-29 17:58:00'),
(105, 1, '739292df7ad39b8d9a134c30cd6d6cea', '127.0.0.1', '2015-07-30 09:17:07', '2015-07-31 13:14:31', 1, 'automatic', '2015-07-30 09:17:10'),
(106, 1, '6f17371b5aa1eca31764d5aa61ba48fd', '127.0.0.1', '2015-07-31 13:14:31', '2015-07-31 13:23:31', 1, 'manually', '2015-07-31 13:22:41'),
(107, 1, 'decc3f8311d3f3ee34eefa94a6a87b4a', '127.0.0.1', '2015-08-03 16:06:39', '2015-08-04 08:43:18', 1, 'timeout', '2015-08-03 16:07:10'),
(108, 1, '8db4f7efcef545e1828b1d1b79cca4c6', '127.0.0.1', '2015-08-04 08:43:24', '2015-08-04 09:20:50', 1, 'timeout', '2015-08-04 08:46:50'),
(109, 1, '36d2d09c31475c2959501538fcd67fd2', '127.0.0.1', '2015-08-04 11:01:59', '2015-08-04 13:07:25', 1, 'timeout', '2015-08-04 11:06:22'),
(110, 1, '13b0f7602e2465379a2582390d8a2c1b', '127.0.0.1', '2015-08-04 13:07:30', '2015-08-05 09:04:44', 1, 'manually', '2015-08-04 13:13:21'),
(111, 1, '410decebdbb95630fa148f8ea198ff7f', '127.0.0.1', '2015-08-05 09:15:20', '2015-08-05 09:17:43', 1, 'manually', '2015-08-05 09:17:08'),
(112, 1, 'ededbe8de615347d38635e73255d53fb', '127.0.0.1', '2015-08-05 10:32:35', '2015-08-05 10:46:17', 1, 'timeout', '2015-08-05 10:32:45'),
(113, 1, '855a4a39745da47872948f094bad6b09', '127.0.0.1', '2015-08-06 10:08:48', '2015-08-06 10:09:25', 1, 'manually', '2015-08-06 11:09:22'),
(114, 1, '66046726bae65bc28d411a115c0e069f', '127.0.0.1', '2015-08-06 10:10:00', '2015-08-06 10:10:02', 1, 'manually', '2015-08-06 11:10:00'),
(115, 1, '805a9d74da4454bb5914d2999f5fd9f6', '127.0.0.1', '2015-08-06 10:11:08', '2015-08-06 10:11:12', 1, 'manually', '2015-08-06 11:11:09'),
(116, 1, '76632e565603788814d77c71ba5f0176', '127.0.0.1', '2015-08-06 10:25:30', '2015-08-06 10:29:21', 1, 'timeout', '2015-08-06 11:26:17'),
(117, 1, '4ab9d82d91cc2b27f3c11859668a1ed0', '127.0.0.1', '2015-08-06 10:29:22', '2015-08-06 10:30:02', 1, 'timeout', '2015-08-06 11:29:27'),
(118, 1, 'bdfb8717f4564cdc88cd0ff7df474704', '127.0.0.1', '2015-08-10 09:18:20', '2015-08-10 10:49:01', 1, 'timeout', '2015-08-10 10:18:23'),
(119, 1, 'b34d1d9f73d5ca41835e33274211589e', '127.0.0.1', '2015-08-31 08:13:38', '2015-08-31 08:22:50', 1, 'manually', '2015-08-31 08:22:48'),
(120, 1, '848321a5ddcf1c57047359550a4786bc', '127.0.0.1', '2015-08-31 08:26:22', '2015-08-31 08:26:25', 1, 'manually', '2015-08-31 08:26:22'),
(121, 1, 'b8edf62144911110aed5e16db94cd853', '127.0.0.1', '2015-08-31 09:49:13', '2015-08-31 09:49:15', 1, 'manually', '2015-08-31 09:49:13'),
(122, 1, 'cdc3c5ba814b1cdaa7dbf9f21a56c6de', '127.0.0.1', '2015-08-31 10:25:15', '2015-08-31 10:25:22', 1, 'manually', '2015-08-31 10:25:15'),
(123, 1, '10f03b49ff0ba4591260f690b4fb5567', '127.0.0.1', '2015-08-31 10:27:30', '2015-08-31 10:27:32', 1, 'manually', '2015-08-31 10:27:30'),
(124, 1, 'eb12f775c491f351491b0f8f2682c5e2', '127.0.0.1', '2015-09-02 10:41:41', '2015-09-02 10:41:43', 1, 'manually', '2015-09-02 10:41:42'),
(125, 1, '55b3fbb703071e2fcec546111d5e3532', '127.0.0.1', '2015-09-02 11:58:32', '2015-09-02 11:58:34', 1, 'manually', '2015-09-02 11:58:32'),
(126, 1, '7b0d5b99c6e38bfaaca65e5455f1130c', '127.0.0.1', '2015-09-02 11:59:25', '2015-09-02 11:59:28', 1, 'manually', '2015-09-02 11:59:26'),
(127, 1, '88706e0f49b2bc1dd7e33f253051330a', '127.0.0.1', '2015-09-02 11:59:43', '2015-09-02 12:00:34', 1, 'manually', '2015-09-02 11:59:44'),
(128, 1, '6a7ad585ef942d312873f8675bec0420', '127.0.0.1', '2015-09-02 12:01:04', '2015-09-02 12:01:06', 1, 'manually', '2015-09-02 12:01:04'),
(129, 1, '682592ba5bfc0bcd66bc3a3bd80737d0', '127.0.0.1', '2015-09-02 13:15:01', '2015-09-02 13:28:13', 1, 'manually', '2015-09-02 13:28:12'),
(130, 1, '204e0bc9aed1f40a00ee32e5a4a071ba', '127.0.0.1', '2015-09-02 14:01:18', '2015-09-02 14:01:22', 1, 'manually', '2015-09-02 14:01:18'),
(131, 1, '21285ecd0bacc3f1b24c9112c2209523', '127.0.0.1', '2015-09-02 15:54:48', '2015-09-02 16:07:08', 1, 'manually', '2015-09-02 15:54:48'),
(132, 1, '83ed22c10df1ff77bc0e4dec30428c9e', '127.0.0.1', '2015-09-03 09:05:12', '2015-09-03 09:51:19', 1, 'manually', '2015-09-03 09:51:16'),
(133, 1, '6c20aa98fd21ecf9d983a745d2d44483', '127.0.0.1', '2015-09-03 11:03:31', '2015-09-03 11:04:14', 1, 'manually', '2015-09-03 11:04:10'),
(134, 1, 'b431097764e12721ab45fb8815745416', '127.0.0.1', '2015-09-03 11:10:13', '2015-09-03 11:10:17', 1, 'manually', '2015-09-03 11:10:13'),
(135, 1, '267d49973c464f51db42b448d854ec5e', '127.0.0.1', '2015-09-03 15:47:16', '2015-09-04 09:20:39', 1, 'timeout', '2015-09-03 17:30:39'),
(136, 1, '0be0e31031bbfe0df807fb00d85ada07', '127.0.0.1', '2015-09-04 09:20:39', '2015-09-04 10:30:11', 1, 'manually', '2015-09-04 10:30:04'),
(137, 1, '77daf3fdb880888d4416f2fd4a9592c9', '127.0.0.1', '2015-09-04 10:33:56', '2015-09-04 10:38:41', 1, 'timeout', '2015-09-04 10:33:57'),
(138, 1, 'f75ecfcfe4873e8b3feffdbb943b61db', '127.0.0.1', '2015-09-04 10:38:57', '2015-09-04 10:42:06', 1, 'timeout', '2015-09-04 10:38:57'),
(139, 1, 'ba337a97bcb3cf03254192a1c7f36bb0', '127.0.0.1', '2015-09-04 10:44:35', '2015-09-04 10:44:44', 1, 'timeout', '2015-09-04 10:44:36'),
(140, 1, '6ba9b40765359a5e4365e2ce1ff6eef0', '127.0.0.1', '2015-09-04 10:47:23', '2015-09-04 10:47:32', 1, 'timeout', '2015-09-04 10:47:23'),
(141, 1, '7ace887eaeb0e9f3b1ee4c56b5332115', '127.0.0.1', '2015-09-04 10:51:09', '2015-09-04 10:51:25', 1, 'timeout', '2015-09-04 10:51:10'),
(142, 1, '19e9eb310aaad66ee4f61de4ca0bfc97', '127.0.0.1', '2015-09-04 10:54:07', '2015-09-04 10:54:34', 1, 'timeout', '2015-09-04 10:54:08'),
(143, 1, '3dde523174e0e9f2d040cb0d31eda47e', '127.0.0.1', '2015-09-04 10:55:42', '2015-09-04 10:55:54', 1, 'timeout', '2015-09-04 10:55:42'),
(144, 1, 'caec47cb436672ccda7e86cbb51e4219', '127.0.0.1', '2015-09-04 10:56:08', '2015-09-04 10:56:21', 1, 'timeout', '2015-09-04 10:56:10'),
(145, 1, 'bae4739fa4880547c750926629eda66b', '127.0.0.1', '2015-09-04 10:56:38', '2015-09-04 10:57:06', 1, 'timeout', '2015-09-04 10:56:42'),
(146, 1, '995f0e509b2869a07d056a6d38b50f70', '127.0.0.1', '2015-09-04 10:57:24', '2015-09-04 10:57:42', 1, 'timeout', '2015-09-04 10:57:28'),
(147, 1, 'dd43744f165e8e768dbfe7988537b603', '127.0.0.1', '2015-09-04 10:58:26', '2015-09-04 10:58:40', 1, 'timeout', '2015-09-04 10:58:29'),
(148, 1, 'f95612483fa0a1a16e8e92bba80dded2', '127.0.0.1', '2015-09-04 10:58:40', '2015-09-04 10:58:48', 1, 'timeout', '2015-09-04 10:58:40'),
(149, 1, 'ba605acf2a62e488e59df087cf36b565', '127.0.0.1', '2015-09-04 10:58:48', '2015-09-04 10:59:00', 1, 'timeout', '2015-09-04 10:58:48'),
(150, 1, '285d28912b504e8e36d4f58ca9047186', '127.0.0.1', '2015-09-04 10:59:04', '2015-09-04 10:59:23', 1, 'timeout', '2015-09-04 10:59:10'),
(151, 1, 'd38daeef73155c6ae387c7e3334146b9', '127.0.0.1', '2015-09-04 10:59:23', '2015-09-04 10:59:32', 1, 'timeout', '2015-09-04 10:59:23'),
(152, 1, '19a4bd187519993f267116984adecbc2', '127.0.0.1', '2015-09-04 10:59:52', '2015-09-04 11:00:10', 1, 'timeout', '2015-09-04 10:59:59'),
(153, 1, 'fba34b473904791e9e5466fda087ffab', '127.0.0.1', '2015-09-04 11:00:10', '2015-09-04 11:00:15', 1, 'manually', '2015-09-04 11:00:11'),
(154, 1, '1f84f7839d96dca5cd367249e8b08cb8', '127.0.0.1', '2015-09-04 11:33:29', '2015-09-04 11:35:42', 1, 'timeout', '2015-09-04 11:33:30'),
(155, 1, '14db8c588cadd73e427dba3ae03a0afc', '127.0.0.1', '2015-09-04 11:35:47', '2015-09-04 11:36:22', 1, 'timeout', '2015-09-04 11:35:53'),
(156, 1, 'c8a7c6e346e3f8a9de55590e9a3b1ddd', '127.0.0.1', '2015-09-04 11:36:26', '2015-09-04 11:36:42', 1, 'timeout', '2015-09-04 11:36:29'),
(157, 1, '5285ba0fd9f55d3e5816204257616593', '127.0.0.1', '2015-09-04 11:37:21', '2015-09-04 11:41:19', 1, 'timeout', '2015-09-04 11:37:21'),
(158, 1, '75db52c3fa3d922f4f97f47df0142def', '127.0.0.1', '2015-09-04 11:41:31', '2015-09-04 11:41:38', 1, 'manually', '2015-09-04 11:41:34'),
(159, 1, 'b4f66ba7807cda687a3921af6f851bee', '127.0.0.1', '2015-09-04 11:41:48', '2015-09-04 11:41:54', 1, 'manually', '2015-09-04 11:41:48'),
(160, 1, '6879b63e2b7107808e44ddd2c398919a', '127.0.0.1', '2015-09-04 11:42:09', '2015-09-04 11:42:47', 1, 'timeout', '2015-09-04 11:42:09'),
(161, 1, 'd07906cc84df9b566abae13f8641d61c', '127.0.0.1', '2015-09-04 11:42:54', '2015-09-04 11:48:26', 1, 'manually', '2015-09-04 11:48:23'),
(162, 1, 'bbcdf5dbc99b8475b92587e98927a3c2', '127.0.0.1', '2015-09-04 11:48:43', '2015-09-04 11:49:47', 1, 'manually', '2015-09-04 11:49:44'),
(163, 1, '0c2eafce75fe937c0fde17aba95e90e0', '127.0.0.1', '2015-09-04 11:49:54', '2015-09-04 11:50:09', 1, 'manually', '2015-09-04 11:49:55'),
(164, 1, '481f8f751f11e0f4e7ca189dc11617e4', '127.0.0.1', '2015-09-04 11:50:20', '2015-09-04 13:14:20', 1, 'manually', '2015-09-04 13:14:08'),
(165, 1, 'b905e08b2409c48b3dbabb9c50f8c144', '127.0.0.1', '2015-09-04 13:14:29', '2015-09-04 13:16:26', 1, 'manually', '2015-09-04 13:16:16'),
(166, 1, 'f8ee4148d92f9a2c228b16329e997b69', '127.0.0.1', '2015-09-04 13:16:39', '2015-09-04 13:18:40', 1, 'manually', '2015-09-04 13:16:39'),
(167, 1, 'b34d4635d136450835db856e277c094a', '127.0.0.1', '2015-09-04 13:20:55', '2015-09-04 13:20:58', 1, 'manually', '2015-09-04 13:20:55'),
(168, 1, '40fbf1807b1a0d53509dd32c562ffae9', '127.0.0.1', '2015-09-04 14:03:06', '2015-09-04 14:03:10', 1, 'manually', '2015-09-04 14:03:06'),
(169, 1, '05884f9627be7a41bdf62c36f68e5cb3', '127.0.0.1', '2015-09-04 16:50:41', '2015-09-04 16:51:47', 1, 'automatic', '2015-09-04 16:50:41'),
(170, 1, '72fc18c75f05130bc61c410a49169cd0', '127.0.0.1', '2015-09-04 16:51:47', '2015-09-04 16:51:53', 1, 'manually', '2015-09-04 16:51:47'),
(171, 1, '5e631f62d01432ce70fde19274ecd9d8', '127.0.0.1', '2015-09-07 08:59:56', '2015-09-07 09:01:09', 1, 'timeout', '2015-09-07 08:59:57'),
(172, 1, '4d442da997fdb399822ae506b10e0048', '127.0.0.1', '2015-09-07 09:01:12', '2015-09-07 09:01:31', 1, 'timeout', '2015-09-07 09:01:13'),
(173, 1, 'b81ced5efa26c12b0735acf925f8e2c3', '127.0.0.1', '2015-09-07 09:01:31', '2015-09-07 09:01:54', 1, 'timeout', '2015-09-07 09:01:32'),
(174, 1, 'c9da8658f799d2dd6aa97450e33723ca', '127.0.0.1', '2015-09-07 09:01:57', '2015-09-07 09:02:14', 1, 'timeout', '2015-09-07 09:02:07'),
(175, 1, '5a3e61a1bcb114b3be06585b4fcfe9df', '127.0.0.1', '2015-09-07 09:02:17', '2015-09-07 09:05:15', 1, 'timeout', '2015-09-07 09:02:17'),
(176, 1, '7d640fbff3835723c12cd7e14976fd67', '127.0.0.1', '2015-09-07 09:05:41', '2015-09-07 09:05:54', 1, 'timeout', '2015-09-07 09:05:41'),
(177, 1, '943d672c34904108e97d3c9d1d3ad0ba', '127.0.0.1', '2015-09-07 09:05:58', '2015-09-07 09:06:09', 1, 'timeout', '2015-09-07 09:05:58'),
(178, 1, 'ec838f28c1e459ef22cf4a81e5bc5f2a', '127.0.0.1', '2015-09-07 09:06:09', '2015-09-07 09:06:28', 1, 'timeout', '2015-09-07 09:06:09'),
(179, 1, '7ec51de043647f66a7f34cd76495a5a8', '127.0.0.1', '2015-09-07 09:06:31', '2015-09-07 09:06:41', 1, 'timeout', '2015-09-07 09:06:31'),
(180, 1, '7c5d110ece76f10ba2148c72ca641eae', '127.0.0.1', '2015-09-07 09:06:41', '2015-09-07 09:09:41', 1, 'timeout', '2015-09-07 09:06:41'),
(181, 1, 'eef27649656e8124cdc2d73ca985fd0b', '127.0.0.1', '2015-09-07 09:09:41', '2015-09-07 09:11:39', 1, 'timeout', '2015-09-07 09:09:41'),
(182, 1, '14681d98ea71088b9aa043562b0daf0c', '127.0.0.1', '2015-09-07 09:11:42', '2015-09-07 09:11:50', 1, 'timeout', '2015-09-07 09:11:42'),
(183, 1, '36d34b22dd7e322d7dc8ed8d96a1b5bd', '127.0.0.1', '2015-09-07 09:12:04', '2015-09-07 09:12:20', 1, 'timeout', '2015-09-07 09:12:05'),
(184, 1, '6e6bbd69f542d755ea9c0a66c5db918d', '127.0.0.1', '2015-09-07 09:12:20', '2015-09-07 09:13:35', 1, 'timeout', '2015-09-07 09:12:23'),
(185, 1, '4ab535ac743c5b4eca1e8dc2fa658212', '127.0.0.1', '2015-09-07 09:14:17', '2015-09-08 10:56:29', 1, 'automatic', '2015-09-07 09:15:10'),
(186, 1, '6fb5c3583718250f4daf14caaa9c76ac', '127.0.0.1', '2015-09-08 10:56:29', '2015-09-08 16:32:48', 1, 'automatic', '2015-09-08 11:10:23'),
(187, 1, '83f4d82432e59077036b423db956c180', '127.0.0.1', '2015-09-08 16:32:48', '2015-09-11 09:10:18', 1, 'automatic', '2015-09-08 17:33:52'),
(188, 1, 'bc1869aef860da8ecb07fcae21995894', '127.0.0.1', '2015-09-11 09:10:18', '2015-09-15 08:40:53', 1, 'automatic', '2015-09-11 10:15:44'),
(189, 1, '13516b86d24a530ed4853bb67bd252c0', '127.0.0.1', '2015-09-15 08:40:53', '2015-09-15 08:41:24', 1, 'manually', '2015-09-15 08:41:20'),
(190, 1, '5382d97bfbb9288764ed5bf038813619', '127.0.0.1', '2015-09-15 16:53:29', '2015-09-15 16:54:20', 1, 'manually', '2015-09-15 16:53:29'),
(191, 1, '02bf974173e310f9b263042c71ee82a1', '127.0.0.1', '2015-09-17 15:59:06', '2015-09-18 08:42:48', 1, 'timeout', '2015-09-17 17:01:24'),
(192, 1, '340f1b623d355a2b19504c8a2c4aa649', '127.0.0.1', '2015-09-23 16:01:28', '2015-09-24 08:21:42', 1, 'timeout', '2015-09-23 17:27:29'),
(193, 1, 'e26fbbe377fc5a4e04a7cc963be4b85e', '127.0.0.1', '2015-09-24 08:21:42', '2015-09-24 16:08:23', 1, 'manually', '2015-09-24 16:08:20'),
(194, 1, '15189b8b3a205ae21bba019251254a80', '127.0.0.1', '2015-09-28 16:17:12', '2015-09-28 16:17:15', 1, 'manually', '2015-09-28 16:17:13'),
(195, 1, 'db10923adc920e5117bae6d98b60743c', '127.0.0.1', '2015-09-29 08:39:58', '2015-09-29 13:29:18', 1, 'automatic', '2015-09-29 11:19:10'),
(196, 1, '1b18c63a6dbdef00d8dadc2ec111522c', '127.0.0.1', '2015-09-29 13:29:18', '2015-09-30 09:05:56', 1, 'timeout', '2015-09-29 16:46:40'),
(197, 1, 'e7159987ec59a69d56aeaf89c5bcf85b', '127.0.0.1', '2015-09-30 09:05:56', '2015-09-30 16:44:22', 1, 'timeout', '2015-09-30 09:45:40'),
(198, 1, 'b4c0afd193d4e190354f57de5e5f724b', '127.0.0.1', '2015-09-30 16:51:55', '2015-10-01 11:05:39', 1, 'timeout', '2015-09-30 17:22:44'),
(199, 1, '8f53fae977c214e853fc1f6d37fd1819', '127.0.0.1', '2015-10-01 11:05:40', '2015-10-02 16:25:24', 1, 'timeout', '2015-10-01 14:27:59'),
(200, 1, 'f0241012fdd1a1b4ab438763a16cfd5e', '127.0.0.1', '2015-10-02 16:33:19', '2015-10-05 08:56:35', 1, 'timeout', '2015-10-02 18:07:32'),
(201, 1, '786066587193b49dd8290ae5285412bb', '127.0.0.1', '2015-10-05 08:56:35', '2015-10-05 10:43:28', 1, 'manually', '2015-10-05 10:43:24'),
(202, 1, '4e6b78f26db6c1e4b5a9946d303e5a00', '127.0.0.1', '2015-10-05 10:58:16', '2015-10-05 13:37:54', 1, 'timeout', '2015-10-05 11:09:36'),
(203, 1, 'a114011e221acadd4068a8850598b86e', '127.0.0.1', '2015-10-05 13:56:00', '2015-10-05 17:00:21', 1, 'timeout', '2015-10-05 14:55:38');

-- 
-- Вывод данных для таблицы tasks
--

-- Таблица capital.tasks не содержит данных

-- 
-- Вывод данных для таблицы transactions
--
INSERT INTO transactions VALUES
(8, '2015-06-24', 500.00, 15, 4, '', -1),
(9, '2015-06-24', 500.00, 15, 4, '4564253', -1),
(10, '2015-06-25', 500.00, 15, 4, '', -1),
(11, '2015-07-08', 100.00, 14, 4, '', 1),
(12, '2015-07-08', 120.00, 14, 4, '', 1),
(13, '2015-07-29', 100.00, 15, 4, '', 1);

-- 
-- Вывод данных для таблицы users
--
INSERT INTO users VALUES
(1, 'qwe', 'e10adc3949ba59abbe56e057f20f883e', 0, 0, 'Иванов Иван'),
(2, 'qwe2', 'e10adc3949ba59abbe56e057f20f883e', 0, 0, '');

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