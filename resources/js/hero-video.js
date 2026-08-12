function initHeroVideos() {
    document.querySelectorAll('[data-hero-video]').forEach(function(video) {
        const iframe = video.querySelector('iframe');
        const videoId = iframe?.src.match(/embed\/([^?]+)/)?.[1];

        if (!videoId) return;

        const player = new YT.Player(video, {
            videoId: videoId,
            playerVars: {
                autoplay: 1,
                mute: 1,
                loop: 1,
                playlist: videoId,
                controls: 0,
                showinfo: 0,
                rel: 0,
                modestbranding: 1,
                playsinline: 1,
            },
            events: {
                onReady: function(e) {
                    e.target.playVideo();
                }
            }
        });
    });
}

function loadYouTubeAPI() {
    if (window.YT && window.YT.Player) {
        initHeroVideos();
    } else {
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        window.onYouTubeIframeAPIReady = function() {
            initHeroVideos();
        };
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadYouTubeAPI);
} else {
    loadYouTubeAPI();
}