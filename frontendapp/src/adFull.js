import { AdMob, InterstitialAdOptions } from '@capacitor-community/admob';

export async function showInterstitialAd() {
  const options = {
    adId: 'ca-app-pub-8600642579394173/1749588253',
  };

  try {
    // Prepare the interstitial ad before showing it
    await AdMob.prepareInterstitial(options);

    // Show the interstitial ad
    await AdMob.showInterstitial();
    console.log('Interstitial Ad shown');
  } catch (error) {
    console.error('Failed to show Interstitial Ad', error);
  }
}

export async function hideInterstitialAd() {
  try {
    await AdMob.hideInterstitial();
    console.log('Interstitial Ad hidden');
  } catch (error) {
    console.error('Failed to hide Interstitial Ad', error);
  }
}
