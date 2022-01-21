@echo off
:check
curl https://dealin.gr/deal-in/backend/api/product/product_status.php
TIMEOUT /T 30 /NOBREAK
goto check
exit