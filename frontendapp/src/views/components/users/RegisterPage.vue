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
                const response = await fetchWithCredentials('/register/adduser', 'POST', this.user);

                Swal.fire({
                    title: 'Bravo !',
                    text: 'Vous êtes désormais inscrit',
                    icon: 'success',
                    position: 'top-end'
                });

                // Réinitialisation des champs utilisateur après succès
                this.user = {
                    firstname: '',
                    name: '',
                    email: '',
                    password: '',
                };

                // Redirection après un délai de 2 secondes
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } catch (error) {
                // Vérifier si l'erreur est un code 422 (erreur de validation)
                if (error.response && error.response.status === 422) {
                    // Récupérer les erreurs du serveur
                    const errors = error.response.data.errors;
                    let errorMessages = '';

                    // Boucle à travers les erreurs et les concatène dans un seul message
                    Object.values(errors).forEach(fieldErrors => {
                        fieldErrors.forEach(errorMsg => {
                            errorMessages += `${errorMsg}\n`;
                        });
                    });

                    // Afficher toutes les erreurs dans l'alerte Swal
                    Swal.fire({
                        title: 'Erreur de validation',
                        text: errorMessages,
                        icon: 'error',
                        position: 'top-end'
                    });
                } else {
                    // Afficher une erreur générique si ce n'est pas une erreur 422
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Une erreur inattendue s\'est produite. Veuillez réessayer.',
                        icon: 'error',
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
