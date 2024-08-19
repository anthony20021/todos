<script setup>
import { RouterView } from 'vue-router'
</script>

<template>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Playwrite+NZ:wght@100..400&display=swap" rel="stylesheet">
    </head>
    <body>
        <nav id="topbar" ref="nav" class="nav" :style="{ right: isOpen ? '0px' : '-150px', zIndex: 10 }">
            <ul class="flex">
                <li id="closeMenu" @click="closeMenu" style="color: #e3dbeb;">Fermer</li>
                <li class="margin"><a class="nav-items todos" href="/" style="margin-top: 40px;">Todos</a></li>

                <div v-if="!isAuthenticated" class="deco flex">
                    <li class="margin"><a class="btn" href="/login">Se connecter</a></li>
                    <li class="margin"><a class="btn" href="/register">S'enregistrer</a></li>
                </div>
                <div v-else>
                    <li class="margin"><a class="nav-items" href="/dashboard">Tableau de bord</a></li>
                    <li class="margin"><a class="nav-items" href="/myAccount">Mon compte</a></li>
                    <li class="margin deco">
                        <li @click="logout">
                            <button type="submit" class="btn deco-wrap" @click="logout">Se déconnecter</button>
                        </li>
                    </li>
                </div>
                <li class="margin">
                    <a class="nav-items" href="https://tonsiteweb.com" target="_blank" rel="noopener noreferrer">Mon site web</a>
                </li>
            </ul>
        </nav>
        <p id="openMenu" @click="toggleMenu" ref="toggleButton" style="top: 40px;"><img src="@/assets/img/menu.png" alt="menu" width="100%" height="100%"></p>
        <RouterView class="body-margin" />
    </body>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
data() {
    return {
    isOpen: false,
    };
},
methods: {
    toggleMenu() {
    this.isOpen = !this.isOpen;
    },
    closeMenu() {
    this.isOpen = false;
    },
    async logout() {
    try {
        await this.$store.dispatch('auth/logout');
        this.$router.push('/');
        window.location('/');
    } catch (error) {
        console.error('Erreur lors de la déconnexion :', error);
    }
    },
    handleClickOutside(event) {
    const nav = this.$refs.nav;
    const toggleButton = this.$refs.toggleButton;

    if (
        !nav.contains(event.target) &&
        !toggleButton.contains(event.target) &&
        window.innerWidth < 1024
    ) {
        this.closeMenu();
    }
    },
},
computed: {
    ...mapGetters('auth', ['isAuthenticated', 'user'])
},
async created() {
    await this.$store.dispatch('auth/checkAuth');
},
mounted() {
    document.addEventListener('click', this.handleClickOutside);
},
beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
}
};
</script>
<style>
    .body-margin {
        margin-top: 90px;
    }
    @media (max-width: 1024px) {
        .body-margin {
            margin-top: 0px;
        }
    }
</style>

