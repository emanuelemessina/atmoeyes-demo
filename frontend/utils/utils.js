function domContentLoad(){
    return new Promise( (resolve, reject) => {
        document.addEventListener('DOMContentLoaded', () => { resolve("DOM Content Loaded")})
    });
}