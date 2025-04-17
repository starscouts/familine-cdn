<?php

require_once "/mnt/familine/app/session.php";

header("Location: https://account.familine.minteck.org/hub/api/rest/avatar/$_PROFILE[id]?dpr=2&size=64");
die();