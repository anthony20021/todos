<template>
    <div style="display: flex; justify-content: center;" class="margin-xl" v-if="!verif_page && !password_page">
        <div>
            <div class="box-input">
                <label for="email">Email</label>
                <input type="email" id="email" v-model="user.email">
            </div>
            <div class="box-input">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" v-model="user.password" @keydown.enter="logUser">
            </div>
            <div style="display: flex; justify-content: space-between;">
                <button class="btn" @click="logUser">Se connecter</button>
                <div>
                    <a @click="password_page=true">Mot de passe oublié ?</a>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="verif_page && !password_page" style="display: flex; justify-content: center;" class="margin-xl">
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
    <div v-else-if="password_page && !verif_page" style="display: flex; justify-content: center;" class="margin-xl">

        <div v-if="!code_page && !code_pass_ok">
            <h2>Ça arrive à tout le monde !</h2>
            <p>Entrez votre votre adresse email et si elle correspond à un compte vous recevrez un code.</p>
            <div class="box-input">
                <label for="email">Email</label>
                <input type="email" id="email" v-model="user.email" @keydown.enter="sendCodeMdp">
            </div>
            <button class="btn" @click="sendCodeMdp">Envoyer</button>
        </div>

        <div v-else-if="code_page && !code_pass_ok">
            <h2>On y est presque.</h2>
            <p>Entrez votre code ici. Attention vous n'avez qu'un essaie pour changer votre mot de passe. (sinon il faut recommencer).</p>
            <div class="box-input">
                <label for="code">Code</label>
                <input type="text" id="code" v-model="user.verification_code" @keydown.enter="verifyCodeMdp">
            </div>
            <div class="flex" style="flex-direction: column; align-items: baseline;">
                <button class="btn" @click="verifyCodeMdp">Valider</button>
                <a @click="code_page = false">Vous n'avez pas reçu de mail ?</a>
            </div>
        </div>

        <div v-else>
            <h1>Dernière étape</h1>
            <p>Choisissez votre nouveau mot de passe.</p>
            <div class="box-input">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" v-model="user.password" @keydown.enter="changeCodeMdp">
            </div>
            <button class="btn" @click="changeCodeMdp">Valider</button>
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
        password_page:false,
        code_page:false,
        code_pass_ok:false,
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
        },

        async sendCodeMdp(){
            try {
                const result = await axios.post('/password/sendCode', {'email' : this.user.email});
                if(result.data.code == 'ok'){
                    this.code_page = true;
                }
            } catch (error) {
                console.error(error);
            }
        },

        async verifyCodeMdp(){
            try {
                const result = await axios.post('/password/verifyCode', {'email' : this.user.email, 'code' : this.user.verification_code});
                if(result.data.code == 'ok'){
                    this.code_pass_ok = true;
                }
                else{
                    this.code_page = false;
                    Swal.fire({title:'Erreur', text:'Le code de vérification est incorrect', icon:'error', position:'top-end'});
                }
            } catch (error) {
                console.error(error);
            }
        },

        async changeCodeMdp(){
            try {
                const result = await axios.post('/password/change', {'email' : this.user.email, 'mdp' : this.user.password, 'code' : this.user.verification_code});
                if(result.data.code == 'ok'){
                    Swal.fire({title:'Succès', text:'Votre mot de passe a bien été modifié', icon:'success', position:'top-end'});
                    this.code_page = false;
                    this.code_pass_ok = false;
                    this.password_page = false;
                }
                else if(result.data.code == 'bad_mdp'){
                    Swal.fire({title:'Erreur', text:result.data.message, icon:'error', position:'top-end'});
                }
                else{
                    this.code_page = false;
                    this.code_pass_ok = false;
                    Swal.fire({title:'Erreur', text:'Le code de vérification est incorrect', icon:'error', position:'top-end'});
                }
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
