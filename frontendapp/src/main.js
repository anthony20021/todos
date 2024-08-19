import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store';
import { AdMob } from '@capacitor-community/admob';

import '@/styles/app.scss'

const app = createApp(App)
app.use(router)
app.use(store);

AdMob.initialize({
    requestTrackingAuthorization: true,
  })
    .then(() => {
      AdMob.requestConsentInfo({
        // ...
      })
        .then(result => {
          console.log('Réponse de AdMob.requestConsentInfo:', result);
          if (result.trackingAuthorizationStatus === 'AUTHORIZED') {
            // L'utilisateur a autorisé le suivi
            // ...
          } else {
            // L'utilisateur n'a pas autorisé le suivi
            // ...
          }
        })
        .catch(error => {
          console.error('Erreur lors de la demande de consentement:', error);
        });
    })
    .catch(error => {
      console.error("Erreur lors de l'initialisation d'AdMob:", error);
    });
app.mount('#app')
