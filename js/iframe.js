function iframeURLChange(iframe, callback) {
    var unloadHandler = function () {
        setTimeout(function () {
            callback(iframe.contentWindow.location.href);
        }, 0);
    };

    function attachUnload() {
        iframe.contentWindow.removeEventListener("pagehide", unloadHandler);
        iframe.contentWindow.addEventListener("pagehide", unloadHandler);
        iframe.contentWindow.removeEventListener("unload", unloadHandler);
        iframe.contentWindow.addEventListener("unload", unloadHandler);
    }

    iframe.addEventListener("load", attachUnload);
    attachUnload();
}

Array.from(document.getElementsByTagName("iframe")).forEach((par) => {
    iframeURLChange(par, function (newURL) {
        $(".loader").fadeIn(200);
    });
})

function unload() {
    $(".loader").fadeIn(200);
}

Array.from(document.getElementsByTagName("iframe")).forEach((par) => {
    par.onbeforeunload = unload
})

function loaded () {
    $(".loader").fadeOut(200);

    setTimeout(() => {
        $(".loader").fadeOut(200);
    }, 300)

    setTimeout(() => {
        $(".loader").fadeOut(200);
    }, 1500);
}

Array.from(document.getElementsByTagName("iframe")).forEach((par) => {
    par.onload = loaded;
    par.onabort = loaded;
})