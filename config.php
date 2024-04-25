<?php

// Make sure to update variables in this file carefully in order not to cause unexpected behaviors

const API_BASE = "Arte-API";
$_ENV = require __DIR__ . "/core/proxy/env.php";

// DB Mode
const DB_MODE = MYSQL; // or SQLITE

// DB VARS
/**
 * Should be defined in .env:
 * (MySQL)
 * 1. DB_HOST
 * 2. DB_NAME
 * 3. DB_USER
 * 4. DB_PASS
 * 
 * (Sqlite)
 * 1. DB_PATH
 */

// Default Timezone
const TIMEZONE = 'Africa/Cairo';

if (in_array(TIMEZONE, DateTimeZone::listIdentifiers(DateTimeZone::ALL)))
  date_default_timezone_set(TIMEZONE);
else
  Logger::getLogger()->reportError("Couldn't find timezone " . TIMEZONE . " among valid timezones");