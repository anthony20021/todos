<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <body>
    <nav id="topbar" ref="nav" class="nav" :style="{ right: isOpen ? '0px' : '-150px', zIndex: 10 }">
      <ul class="flex">
        <li id="closeMenu" @click="closeMenu" style="color: #e3dbeb;">Fermer</li>
        <li class="margin"><a class="nav-items todos" href="/">Todos</a></li>
        
        <div v-if="!isAuthenticated" class="deco flex">
          <li class="margin"><a class="btn" href="/login">Se connecter</a></li>
          <li class="margin"><a class="btn" href="/register">S'enregistrer</a></li>
        </div>
        <template v-else>
          <li class="margin"><a class="nav-items" href="/dashboard">Tableau de bord</a></li>
          <li class="margin"><a class="nav-items" href="/myAccount">Mon compte</a></li>
          <li class="margin deco">
            <form action="{{ route('logout') }}" method="POST">
              <button type="submit" class="btn deco-wrap">Se déconnecter</button>
            </form>
          </li>
        </template>
      </ul>
    </nav>
    <p id="openMenu" @click="toggleMenu" ref="toggleButton"><img src="@/assets/img/menu.png" alt="menu" width="100%" height="100%"></p>
    <RouterView />
  </body>
</template>
<script>
export default {
  data() {
    return {
      isOpen: false,
      isAuthenticated: false, // Simuler l'authentification (à remplacer par votre logique d'authentification)
    };
  },
  methods: {
    toggleMenu() {
      this.isOpen = !this.isOpen;
    },
    closeMenu() {
      this.isOpen = false;
    },
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  methods: {
    toggleMenu() {
      this.isOpen = !this.isOpen;
    },
    closeMenu() {
      this.isOpen = false;
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
};
</script>
<style>

</style>

