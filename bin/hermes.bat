@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../hermes-parser/src/main/hermes
php "%BIN_TARGET%" %*
