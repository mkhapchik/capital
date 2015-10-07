DROP TABLE IF EXISTS menu;

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
CHARACTER SET utf8
COLLATE utf8_general_ci

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
CHARACTER SET utf8
COLLATE utf8_general_ci

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
CHARACTER SET utf8
COLLATE utf8_general_ci
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
CHARACTER SET utf8
COLLATE utf8_general_ci
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
CHARACTER SET utf8
COLLATE utf8_general_ci
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
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Типы страниц';


-- 
-- Вывод данных для таблицы pages
--
INSERT INTO pages VALUES
(1, 'account', 'Счета', 'account/default', NULL, NULL, 1, 0),
(2, 'income', 'Категории дохода', 'categories/income', NULL, NULL, 1, 0),
(3, 'expense', 'Категории расхода', 'categories/expense', NULL, NULL, 1, 0),
(4, 'transactions_income', 'Доходы', 'transactions/income', NULL, NULL, 1, 0),
(5, 'transactions_expense', 'Расходы', 'transactions/expense', NULL, NULL, 1, 0),
(6, 'table_expen', 'Сравнение расходов', 'reports/table_expen', NULL, NULL, 1, 0);

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
-- Вывод данных для таблицы pages_type
--
INSERT INTO pages_type VALUES
(1, 'content', 'Страница контента', 'content'),
(2, 'news', 'Страница новости', 'news');
