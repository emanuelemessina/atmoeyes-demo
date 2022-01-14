function domContentLoad() {
    return new Promise((resolve, reject) => {
        document.addEventListener('DOMContentLoaded', () => { resolve("DOM Content Loaded") })
    });
}

function getConf() {
    return new Promise((resolve, reject) => {
        fetch('.atmoeyes').then(f => f.json()).then(j => resolve(j))
    });
}

var atmoeyes = {};

( async () => {
    
    atmoeyes = await getConf()
    
})();
