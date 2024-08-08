<template>
    <div style="display: flex; justify-content: center;" class="margin-xl">
      <div>
        <div class="box-input">
            <label for="email">Email</label>
            <input type="email" id="email" v-model="user.email">
        </div>
        <div class="box-input">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" v-model="user.password" @keydown.enter="logUser">
        </div>
        <button class="btn" @click="logUser">Se connecter</button>
      </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'
  export default{
    data(){
      return {
        user:{
            email:"",
            password:""
        }
      }
    },
    methods:{
      async logUser() {
        try {
            const response = await axios.post('/login', this.user);

            if (response.status === 200) {
                window.location.href = '/dashboard';
            }
        } catch (error) {
            if (error.response && error.response.status === 401) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Mauvais email ou mot de passe',
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur inattendue s\'est produite',
                    position: 'top-end'
                });
            }
        }
    }
    }
  }
</script>
<style>

</style>
