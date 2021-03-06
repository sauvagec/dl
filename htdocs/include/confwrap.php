<?php
// version-indepenent configuration variables
require_once("basefuncs.php");

// base directories
$incPath = dirname(__FILE__);
$cfgPath = "/etc/dl.php";

// read the configuration values from a nearby location
if(is_file("$incPath/config.php"))
  $cfgPath = "$incPath/config.php";
if(!is_readable($cfgPath))
  die("cannot read configuration file\n");
require_once($cfgPath);

// variables
if(!isset($defLocale)) $defLocale = "en_US";
if(!isset($cfgVersion)) $cfgVersion = "0.4";
if(!isset($phpExt)) $phpExt = ".php";
if(!isset($maxSize)) $maxSize = ini_get('upload_max_filesize');
if(!isset($authRealm)) $authRealm = false;
if(!isset($dsn)) $dsn = "sqlite:$spoolDir/data.sdb";
if(!isset($dbUser)) $dbUser = NULL;
if(!isset($dbPassword)) $dbPassword = NULL;
if(!isset($gcProbability)) $gcProbability = 1.0;
if(!isset($gcInternal)) $gcInternal = true;
if(!isset($gcLimit)) $gcLimit = 0;
if(!isset($defTimezone)) $defTimezone = @date_default_timezone_get();
if(!isset($dateFmtShort)) $dateFmtShort = "Y-m-d";
if(!isset($dateFmtFull)) $dateFmtFull = "Y-m-d H:m:s T";

// default style
if(!isset($style))
  $style = "style/default";
else
  $style = "style/$style";

// ticket defaults
if(version_compare($cfgVersion, "0.10", "<"))
{
  // settings prior to 0.9
  if(isset($defaultTotalDays))
  {
    $defaultTicketTotalDays = $defaultTotalDays;
    $defaultGrantTotalDays = $defaultTotalDays;
  }
  if(isset($defaultLastDl)) $defaultTicketLastDlDays = $defaultLastDl / 24;
  if(isset($defaultMaxDl)) $defaultTicketMaxDl = $defaultMaxDl;

  // defaults prior to 0.10
  if(!isset($defaultTicketTotalDays)) $defaultTicketTotalDays = 7;
  if(!isset($defaultTicketLastDlDays)) $defaultTicketLastDlDays = 1;
  if(!isset($defaultTicketMaxDl)) $defaultTicketMaxDl = 0;
  if(!isset($defaultGrantTotalDays)) $defaultGrantTotalDays = 7;
}
else
{
  if(!isset($defaultTicketTotalDays)) $defaultTicketTotalDays = 365;
  if(!isset($defaultTicketLastDlDays)) $defaultTicketLastDlDays = 30;
  if(!isset($defaultTicketMaxDl)) $defaultTicketMaxDl = 0;
  if(!isset($defaultGrantTotalDays)) $defaultGrantTotalDays = 365;
}

// derived data
$parsedMasterPath = parse_url($masterPath);
$useSysLog = (!empty($logFile) && strstr($logFile, "/") === FALSE);
$iMaxSize = returnBytes($maxSize);
$dataDir = $spoolDir . "/data";
$adminPath = $masterPath . "admin$phpExt";
$helpRoot = "static/guide";
$dPath = $masterPath . "d$phpExt";
$rPath = $masterPath . "rest$phpExt";

$defaults = array
(
  'ticket' => array
  (
    'total' => $defaultTicketTotalDays * 3600 * 24,
    'lastdl' => $defaultTicketLastDlDays * 3600 * 24,
    'maxdl' => $defaultTicketMaxDl,
  ),
  'grant' => array
  (
    'total' => $defaultGrantTotalDays * 3600 * 24,
  ),
);

// constants
$dlVersion = "0.16";
$schemaVersion = "0.12";
$bannerUrl = 'http://www.thregr.org/~wavexx/software/dl/';
$banner = '<a href="' . htmlentities($bannerUrl) . '">dl ticket service</a>'
  . ' ' . htmlentities($dlVersion);
$cookieLifetime = 1000 * 60 * 60 * 24 * 90;
$maxUUTries = 32;
$tokenLenght = 32;

// password hasher and complexity limits
$maxUserLen = 60;
$maxPassLen = 72;
