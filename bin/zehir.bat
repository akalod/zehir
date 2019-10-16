@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../akalod/zehir/bin/zehir
php "%BIN_TARGET%" %*
