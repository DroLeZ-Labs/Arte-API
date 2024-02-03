<?php

const API_BASE = "Nframework";
const DB_MODE = "MySql";
// const DB_MODE = "Sqlite";

// Sqlite Vars
const DB_PATH = LOCAL . "/core/database/db.db";

//MYSQL Vars
const HOST = "localhost";
const DBNAME = "testdb";
const USERNAME = "root";
const PASSWORD = "";

// JWT Secret
const SECRET = 'secret_key';
// Default Timezone
const TIMEZONE = 'Africa/Cairo';

if (in_array(TIMEZONE, DateTimeZone::listIdentifiers(DateTimeZone::ALL)))
  date_default_timezone_set(TIMEZONE);
else
  Logger::getLogger()->reportError("Couldn't find timezone " . TIMEZONE . " among valid timezones");

// AWS Credentials
const AWS_REGION = 'eu-north-1';
const AWS_VERSION = 'latest';
const AWS_ACCESS_KEY = 'AKIATODI5I4MEPYBDWWZ';
const AWS_SECRET = '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';
const BUCKET = 'nplanet';
