window.addEventListener('load', () => {
    document.getElementById('loading').style.display = "none";
})

function setImageSource(image, source) {
    if (image.src !== source) {
        image.src = source;
    }
}

doCheckForActiveItem = true;

Array.from(document.getElementsByClassName("tab")).forEach((par) => {
    item = par.children[0];
    if (typeof item.getAttribute("data-image-hover") === "string") {
        item.setAttribute("data-image-normal", item.src)
        par.addEventListener('mouseenter', (e) => {
            doCheckForActiveItem = false;

            if (e.target.classList.contains("tab-active")) {
                item = e.target.children[0];
                item.style.opacity = ".75";
            } else {
                item = e.target.children[0];
                setImageSource(item, item.getAttribute("data-image-hover"));
            }
        })
        par.addEventListener('mouseleave', (e) => {
            doCheckForActiveItem = true;

            if (e.target.classList.contains("tab-active")) {
                item = e.target.children[0];
                item.style.opacity = "1";
            } else {
                item = e.target.children[0];
                setImageSource(item, item.getAttribute("data-image-normal"));
            }
        })
    }
})

setInterval(() => {
    Array.from(document.getElementsByClassName("tab")).forEach((par) => {
        if (!doCheckForActiveItem) { return; }
        item = par.children[0];
        if (par.classList.contains("tab-active")) {
            setImageSource(item, item.getAttribute("data-image-active"));
        } else {
            setImageSource(item, item.getAttribute("data-image-normal"));
        }
    })
}, 100)

function openTab(tab) {
    Array.from(document.getElementsByClassName("tab")).forEach((par) => {
        if (par.classList.contains("tab-active")) {
            par.classList.remove("tab-active");
        }
    })
    document.getElementById('tab-' + tab).classList.add("tab-active");
    doCheckForActiveItem = true;
}

function openPane(pane) {
    Array.from(document.getElementsByTagName("iframe")).forEach((par) => {
        par.src = "about:blank";
    })
    Array.from(document.getElementsByClassName("pane")).forEach((par) => {
        $("#" + par.id).fadeOut(200);
    })
    $("#pane-" + pane).fadeIn(200);
    $(".loader").fadeIn(200);
    switch (pane) {
        case "radio":
            document.getElementById('frame-' + pane).src = "/app/radio"
            break;
        case "money":
            document.getElementById('frame-' + pane).src = "/app/money"
            break;
        case "contacts":
            document.getElementById('frame-' + pane).src = "/app/contacts"
            break;
        case "home":
            document.getElementById('frame-' + pane).src = "/app/home"
            break;
        case "space":
            if (navigator.userAgent.includes("+Familine/")) {
                $(".loader").fadeOut(200);
                document.getElementById('frame-' + pane).loadURL("https://chat.familine.minteck.org");
                document.getElementById('frame-' + pane).setZoomFactor(0.9);
                break;
            } else {
                document.getElementById('frame-' + pane).src = "/app/space"
                break;
            }
        case "cinema":
            document.getElementById('frame-' + pane).src = "https://cinema.familine.minteck.org"
            break;
        case "share":
            document.getElementById('frame-' + pane).src = "https://share.familine.minteck.org"
            break;
        case "help":
            document.getElementById('frame-' + pane).src = "/app/help"
            break;
    }
}

window.addEventListener('load', () => {
    try { openPane('home'); } catch (e) {}
})