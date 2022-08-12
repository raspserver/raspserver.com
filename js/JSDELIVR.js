/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

src="https://cdn.jsdelivr.net/hls.js/latest/hls.js"
if(Hls.isSupported()) {
    var video = document.getElementById('video');
    var  hls = new Hls({ autoStartLoad:false });
    hls.loadSource('https://p1media-americasvoice-1.vizio.wurl.com/manifest/playlist.m3u8');
    hls.attachMedia(video);
    hls.startLoad(1);
    hls.on(Hls.Events.MANIFEST_PARSED,function() {
        hls.startLoad(20);
        video.play();}
    );
}
