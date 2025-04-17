setInterval(() => {
    window.fetch("/app/radio/song.php").then((raw) => {
        raw.text().then((text) => {
            document.getElementById('radio-now').innerText = text;
        })
    })
    window.fetch("/app/radio/next.php").then((raw) => {
        raw.text().then((text) => {
            document.getElementById('radio-next').innerText = text;
        })
    })
}, 2000)