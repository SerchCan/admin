@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../rplansky/credit-card/bin/ccvalidator
php "%BIN_TARGET%" %*
