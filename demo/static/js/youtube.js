var player;

var $$ = function(tagname) { return document.getElementsByTagName(tagname); }

function onYouTubeIframeAPIReady() {
    var videos = $$('iframe'), // the iframes elements
        players = [], // an array where we stock each videos youtube instances class
        playingID = null; // stock the current playing video

    console.log(videos);

    for (var i = 0; i < videos.length; i++) // for each iframes
    {
        var currentIframeID = videos[i].id; // we get the iframe ID
        players[currentIframeID] = new YT.Player(currentIframeID); // we stock in the array the instance
        // note, the key of each array element will be the iframe ID

        videos[i].onmouseover = function(e) { // assigning a callback for this event
            var currentHoveredElement = e.target;
            if (playingID) // if a video is currently played
            {
                players[playingID].pauseVideo();
            }

            (new YT.Player(currentHoveredElement)).playVideo();

            playingID = currentHoveredElement.id;
        };
    }
}


var tag = document.createElement('script');
tag.src = "http://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
function onYouTubePlayerAPIReady(){
    console.log('yt api ready');
        onYouTubeIframeAPIReady();
}