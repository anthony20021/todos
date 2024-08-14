
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store';
import { AdMob} from '@capacitor-community/admob';

import '@/styles/app.scss'

AdMob.initialize({
    requestTrackingAuthorization: true,
    testingDevices: ['ca-app-pub-3940256099942544/6300978111'],
    initializeForTesting: false,
  });

const app = createApp(App)
app.use(router)
app.use(store);

app.mount('#app')
