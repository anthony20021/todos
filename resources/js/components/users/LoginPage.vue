<template>
    <div style="display: flex; justify-content: center;" class="margin-xl" v-if="!verif_page">
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
    <div v-else style="display: flex; justify-content: center;" class="margin-xl">
        <div>
            <h1>Vérifier votre compte</h1>
            <p>Un email a été envoyé à l'adresse {{user.email}}. Veuillez renseigner le code de vérification.</p>
            <div>
                <div class="box-input">
                    <label for="verification-code">Code de vérification</label>
                    <input type="text" id="verification-code" v-model="user.verification_code" @keydown.enter="logUser">
                </div>
                <button class="btn" @click="verif">Valider</button>
            </div>
            <p>Si vous n'avez pas reçu de code de vérification, <a style="color: blue;" @click="resendCode">renvoyer</a>.</p>
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
            password:"",
            verification_code:""
        },
        verif_page: false,
      }
    },
    methods:{
        async logUser() {
            try {
                const response = await axios.post('/login', this.user);

                if (response.status === 200) {
                    window.location.href = '/dashboard';
                }
                else if(response.data.code === 'unauthorized') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Identifiant ou mot de passe incorrect',
                        position: 'top-end'
                    });
                }
                else if(response.data.code === 'unverified') {
                    this.verif_page = true;
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur inattendue s\'est produite',
                        position: 'top-end'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur inattendue s\'est produite',
                    position: 'top-end'
                });
            }
        },

        async verif(){
            try {
                const result = await axios.post('/verif', this.user);
                if(result.data.code == 'verified'){
                    this.logUser();
                }
                else{
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                }
            } catch (error) {
                console.error(error);
                Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
            }
        },

        async resendCode() {
            try {
                const result = await axios.post('/resendCode', this.user);
                if(result.data.code == 'verified'){
                    Swal.fire({title:'Succès', text:'Le code a bien été envoyé', icon:'success', position:'top-end'});
                }
                else{
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                }
            } catch (error) {
                console.error(error);
                Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
            }
        }
    }
}
</script>
<style>

</style>
