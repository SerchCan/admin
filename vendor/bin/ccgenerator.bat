@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../rplansky/credit-card/bin/ccgenerator
php "%BIN_TARGET%" %*
