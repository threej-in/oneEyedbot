<?php
/**
 * Main file for Telegram bot configuration
 * 
 * @package oneEyedBot
 * @author threej[Jitendra Pal]
 */

/**
 * bot_token should be replaced with your own bots unique token which
 * you have received from [botfather](http://t.me/botfather).
 */
define('BOT_TOKEN', '');

/**
 * Telegram chat_id of bot admin. To receive logs/notification from the bot
 * when something odd happens or when a new user starts the bot.
 * 
 * Get your telegram chat_id form [@forwardinfobot](http://t.me/forwardinfobot")
 */
define('ADMINID', '');

/** DATABASE CONFIGURATION */

/**
 * MYSQL database server name. If you are unaware about your db server name then
 * you can get it from your hosting provider.
 */
define('DBSERVER', '');

/** MYSQL database user name. */
define('DBUSERNAME', '');

/** MYSQL database password. */
define('DBPASSWORD', '');

/** MYSQL database name. */
define('DBNAME', '');

/** switch to true for better debugging */
define('DEBUG_MODE', true);
