export default function (component) {
  const posterImage = component.querySelector('.figure-image');
  const videoPlayer = component.querySelector('.video-player');
  const iframe = component.querySelector('iframe');
  const figure = component.querySelector('.figure');
  const playButton = component.querySelector('.figure-playButton');

  if (!iframe || !playButton) return;

  playButton.addEventListener('click', () => hideFigureAndPlay(posterImage, playButton, iframe, videoPlayer, figure));
}

function hideFigureAndPlay(posterImage, playButton, iframe, videoPlayer, figure) {
  if (posterImage) posterImage.style.display = 'none';
  if (playButton) playButton.style.display = 'none';

  loadVideo(iframe, videoPlayer, figure);
}

function loadVideo(iframe, videoPlayer, figure) {
  const videoSrc = iframe.getAttribute('src') || iframe.dataset.src;
  if (!videoSrc) return;

  const url = new URL(videoSrc);
  url.searchParams.set('rel', '0');
  url.searchParams.set('autoplay', '1');
  url.searchParams.set('controls', '1');
  url.searchParams.set('enablejsapi', '1');
  url.searchParams.set('playsinline', '1');

  iframe.setAttribute('src', url.toString());
  iframe.setAttribute(
    'allow',
    'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'
  );
  iframe.setAttribute('allowfullscreen', true);

  iframe.addEventListener('load', () => videoIsLoaded(videoPlayer, figure));
  videoPlayer.classList.add('video-player--isLoading');
}

function videoIsLoaded(videoPlayer, figure) {
  videoPlayer.classList.remove('video-player--isLoading');
  videoPlayer.classList.add('video-player--isLoaded');
  figure.classList.add('figure--isHidden');
}
