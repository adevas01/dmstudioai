<?php
session_start();
session_unset();
session_destroy();

header("Location: ../index.php?route=login&nav=dmstudioai&message=loggedout");
exit;