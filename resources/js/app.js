import './bootstrap';
import { createApp } from 'vue';

import HomePage from './components/HomePage.vue';
import RegisterPage from './components/users/RegisterPage.vue'
import LoginPage from './components/users/LoginPage.vue';
import Dashboard from './components/dashboard/dashboard.vue';
import MyAccount from './components/users/myAccount.vue';
import AdminPage from './components/admin/AdminPage.vue';

const admin = createApp(AdminPage);
const myAccount = createApp(MyAccount);
const home = createApp(HomePage);
const register = createApp(RegisterPage);
const login = createApp(LoginPage)
const dashboard = createApp(Dashboard);
admin.mount('#admin-page')
myAccount.mount('#my-account-page')
login.mount('#login-page')
home.mount('#home-page');
register.mount('#register-page')
dashboard.mount('#dashboard-page')

