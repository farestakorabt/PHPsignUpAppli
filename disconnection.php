<?php

session_start();

session_unset();

session_destroy();

setcookie('logAuto','', time() - 3600, '/', null, false, true);

header('location: ./');