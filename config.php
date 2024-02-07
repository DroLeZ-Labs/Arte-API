<?php

const API_BASE = "Arte-API";

// DB Mode
// const DB_MODE = SQLITE;
const DB_MODE = MYSQL;

// DB VARS
// (MySQL)
const HOST = "localhost";
const DBNAME = "testdb";
const USERNAME = "root";
const PASSWORD = "";
// (Sqlite)
const DB_PATH = LOCAL . "/core/database/db.db";

// Default Timezone
const TIMEZONE = 'Africa/Cairo';

// JWT Secret
const SECRET = 'secret_key';

if (in_array(TIMEZONE, DateTimeZone::listIdentifiers(DateTimeZone::ALL)))
  date_default_timezone_set(TIMEZONE);
else
  Logger::getLogger()->reportError("Couldn't find timezone " . TIMEZONE . " among valid timezones");