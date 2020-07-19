CREATE TABLE IF NOT EXISTS accounts (id VARCHAR(32) PRIMARY KEY NOT NULL,name VARCHAR(50) not null, accountnumber VARCHAR(32), deleted TINYINT);
CREATE TABLE IF NOT EXISTS entries (id VARCHAR(32) PRIMARY KEY NOT NULL, account VARCHAR(32) not null,entrydate INT not null,description TEXT not null,amount FLOAT not null,dc as CHARACTER(2) deleted TINYINT );
CREATE INDEX IF NOT EXISTS accountid ON entries(account);
CREATE INDEX IF NOT EXISTS dctype on entries(account,dc);
CREATE TABLE IF NOT EXISTS users (id VARCHAR(32) PRIMARY KEY NOT NULL, username VARCHAR(50), password text, deleted TINYINT);
CREATE TABLE IF NOT EXISTS tokens (token VARCHAR(32) NOT NULL, request_time INTEGER not null,valid_until INTEGER, refreshtoken VARCHAR(32));
