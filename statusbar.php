<?php

global $_CONFIG;
$_CONFIG = json_decode(file_get_contents("/mnt/familine/private/FamilineConfig.json"), true);

if (isset($_COOKIE['FL_SESSION_TOKEN'])) {
    if (file_exists("/mnt/familine/private/tokens/" . str_replace(".", "", str_replace("/", "", $_COOKIE['FL_SESSION_TOKEN'])))) {
        $_PROFILE = json_decode(file_get_contents("/mnt/familine/private/tokens/" . str_replace(".", "", str_replace("/", "", $_COOKIE['FL_SESSION_TOKEN']))), true);

        if (isset($_PROFILE['familine'])) {
            header("Location: https://" . $_CONFIG["Global"]["domain"] . "/login/?r=" . urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
            die();
        }

        $_USER = $_PROFILE['login'];
        $_SUID = $_PROFILE['login'];
        $_FULLNAME = $_PROFILE['name'];
        $_FRENCH = true;

        if (isset($_PROFILE['locale']) && $_PROFILE['locale']['name'] !== "fr") {
            $_FRENCH = false;
        }
    } else {
        header("Location: https://" . $_CONFIG["Global"]["domain"] . "/login/?r=" . urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
        die();
    }
} else {
    header("Location: https://" . $_CONFIG["Global"]["domain"] . "/login/?r=" . urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
    die();
}

if (isset($_PROFILE["projectRoles"]) && is_array($_PROFILE["projectRoles"]) && isset($_PROFILE["projectRoles"][0]) && is_array($_PROFILE["projectRoles"][0]) && isset($_PROFILE["projectRoles"][0]["role"]) && is_array($_PROFILE["projectRoles"][0]["role"]) && isset($_PROFILE["projectRoles"][0]["role"]["key"]) && is_string($_PROFILE["projectRoles"][0]["role"]["key"]) && $_PROFILE["projectRoles"][0]["role"]["key"] === "system-admin") {
    $_ADMIN = true;
} else {
    $_ADMIN = false;
}

function l($fr, $en) {
    global $_FRENCH;

    if ($_FRENCH) {
        return $fr;
    } else {
        return $en;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>frame</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.svg">
    <link rel="stylesheet" href="/styles.css">
</head>

<body>
    <style>
        /* Statusbar */
        #statusbar {
            background: whitesmoke;
            padding: 8px 32px;
            font-size: 14px;
            z-index: 99;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            color: black;
        }

        #statusbar-tag {
            opacity: .5;
        }

        .logo:hover {
            background: rgba(0, 0, 0, .25);
        }

        .logo:active {
            background: rgba(0, 0, 0, .5);
        }

        .logo {
            cursor: pointer;
            color: black;
        }

        .account:hover {
            background: rgba(0, 0, 0, .25);
        }

        .account:active {
            background: rgba(0, 0, 0, .5);
        }

        .account {
            cursor: pointer;
            color: black;
        }

        @media (prefers-color-scheme: dark) {
            .account {
                color: white;
            }

            .logo {
                color: white;
            }
        }

        @media (max-width: 800px) {
            #apps-desktop {
                display: none;
            }

            #statusbar {
                text-align: center;
            }
        }

        @media (max-width: 1100px) {
            #copyright, #user-name {
                display: none;
            }
        }

        .statusbar-drag-region {
            -webkit-app-region: drag;
        }

        #statusbar-drag-region-01 {
            left: 348px;
            position: fixed;
            top: 0;
            /*background: rgba(255, 0, 0, 0.5);*/
            height: 36px;
            right: 70px;
        }

        #statusbar-drag-region-02 {
            left: 0;
            position: fixed;
            top: 0;
            /*background: rgba(255, 0, 0, 0.5);*/
            height: 36px;
            right: unset;
            width: 32px;
        }

        #statusbar-drag-region-03 {
            left: unset;
            position: fixed;
            top: 0;
            /*background: rgba(255, 0, 0, 0.5);*/
            height: 36px;
            right: 0;
            width: 32px;
        }

        #statusbar.desktop #statusbar-drag-region-01 {
            left: 118px;
        }

        @media (min-width: 1101px) {
            #statusbar-drag-region-01 {
                right: 154px;
            }
        }
    </style>
    <div id="statusbar">
        <div class="statusbar-drag-region" id="statusbar-drag-region-01"></div>
        <div class="statusbar-drag-region" id="statusbar-drag-region-02"></div>
        <div class="statusbar-drag-region" id="statusbar-drag-region-03"></div>
        <a title="<?= l("Accueil de Familine", "Familine Home") ?>" href="https://app.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent"><span class="logo" style="display: inline-block;top: 0;position: relative;padding: 8px 7px 7px 7px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/favicon.svg" style="width: 16px;vertical-align: middle;position: relative;top: -2px;"> Familine</span></a>
        <span id="apps-desktop" <?= isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === "https://app." . $_CONFIG["Global"]["domain"] . "/" ? "style=\"display: none;\"" : "" ?>><span style="opacity: .25;">|</span><a style="text-decoration: none;" title="Familine Pages" href="https://docs.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-docs.svg" style="width: 20px;vertical-align: middle;"></span></a><a style="text-decoration: none;" title="Familine <?= l("Aide", "Help") ?>" href="https://support.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-help.svg" style="width: 20px;vertical-align: middle;"></span></a><a style="text-decoration: none;" title="Familine <?= l("Média", "Media") ?>" href="https://media.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-media.svg" style="width: 20px;vertical-align: middle;"></span></a><!--<a style="text-decoration: none;" title="Familine Planning" href="https://planning.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-planning.svg" style="width: 20px;vertical-align: middle;"></span></a>--><a style="text-decoration: none;" title="Familine <?= l("Généalogie", "Genealogy") ?>" href="https://genealogy.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-recall.svg" style="width: 20px;vertical-align: middle;"></span></a><a style="text-decoration: none;" title="Familine <?= l("Partage", "Share") ?>" href="https://share.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-share.svg" style="width: 20px;vertical-align: middle;"></span></a><!--<a style="text-decoration: none;" title="Familine <?= l("Discussions", "Chat") ?>" href="https://chat.<?= $_CONFIG["Global"]["domain"] ?>" target="_parent">
                <span class="logo" style="display: inline-block;top: -2px;position: relative;padding: 6px 2px 7px 2px;margin: -11px 0;"><img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/icns/familine-you.svg" style="width: 20px;vertical-align: middle;"></span></a>-->
        </span>
        <div id="user">
            <a title="<?= l("Mon compte", "My Account") ?>" href="https://account.<?= $_CONFIG["Global"]["domain"] ?>/hub/users/me" target="_parent"><span class="account" style="display: inline-block;top: 0;position: relative;padding: 8px 7px 7px 7px;margin: -11px 0;">
                <span id="user-name" style="position: relative;margin-left:5px;top: 2.5px;right: 5px;"><?= $_FULLNAME ?></span>
                <img src="https://<?= $_CONFIG["Global"]["cdn"] ?>/me" alt="" style="width:24px;border-radius:999px;vertical-align: middle;position:relative;top: 2px;">
            </span></a>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            if (navigator.userAgent.includes("+FL4D")) {
                console.log("Detected desktop app");
                document.getElementsByClassName("account")[0].parentElement.onclick = (e) => {
                    e.preventDefault();
                    open(document.getElementsByClassName("account")[0].parentElement.href);
                    return false;
                };
            }
        })
    </script>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> e93d0263dc90fdea94f6a5fb89faa910097e7c16
