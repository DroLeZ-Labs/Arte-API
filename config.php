<?php

const API_BASE = "/Arte-API";

// DB Mode
const MYSQL = 1;
const SQLITE = 2;
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