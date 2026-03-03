<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAJESTIC EDUCATION</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <video id="video" controls width="800"></video>
    <script>
        var video = document.getElementById('video');
        var videoSrc = "{{ $playlistUrl }}";

        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(videoSrc);
            hls.attachMedia(video);
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = videoSrc;
        }
    </script>
</body>
</html>
