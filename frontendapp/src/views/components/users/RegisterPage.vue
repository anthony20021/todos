<template>
    <div style="display: flex; justify-content: center; " class="margin-xl">
      <div>
          <div class="box-input">
              <label for="name">Nom</label>
              <input type="text" id="name" v-model="user.name">
          </div>
          <div class="box-input">
              <label for="fistname">Prénom</label>
              <input type="text" id="firstname" v-model="user.firstname">
          </div>
        <div class="box-input">
            <label for="email">Email</label>
            <input type="email" id="email" v-model="user.email">
        </div>
        <div class="box-input">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" v-model="user.password" @keydown.enter="addUser">
        </div>
        <button class="btn" @click="addUser"> S'enregistrer</button>
      </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'
import fetchWithCredentials from '@/network';
  export default{
    data(){
      return {
        user:{
            firstname:"",
            name:"",
            email:"",
            password:"",
        }
      }
    },
    methods:{
        async addUser() {
            try {
            const response = await fetchWithCredentials('/register/adduser', 'POST' ,this.user);
            Swal.fire({
                title: 'Bravo !',
                text: 'Vous êtes désormais inscrit',
                icon: 'success',
                position:'top-end'
            })
            this.user = {
                firstname: '',
                name: '',
                email: '',
                password: '',
            };
            setTimeout(() => {
              window.location.href = '/login';
            }, 2000);
            } catch (error) {
            Swal.fire({
                title: 'Erreur',
                text: error,
                icon: 'error',
                position:'top-end'
            })
            }
        },
    }
  }
</script>
<style>
</style>
