import { AdMob, BannerAdOptions, BannerAdPosition } from '@capacitor-community/admob';

export async function showAdBanner() {
  const options = {
    adId: 'ca-app-pub-8600642579394173/1363641100', // Remplacez par votre propre ID
    adSize: 'BANNER',
    position: BannerAdPosition.TOP_CENTER,
    margin: 0,
  };

  try {
    await AdMob.showBanner(options);
    console.log('Banner Ad shown');
  } catch (error) {
    console.error('Failed to show Banner Ad', error);
  }
}

export async function hideAdBanner() {
  try {
    await AdMob.hideBanner();
    console.log('Banner Ad hidden');
  } catch (error) {
    console.error('Failed to hide Banner Ad', error);
  }
}
