<template>
    <div>
        <div class="flex" style="flex-direction: column;" v-if="!changeBoolMdp">
            <h2>Mon compte</h2>
            <div class="box-input">
                <label for="name">Nom</label>
                <input type="text" class="form-control" id="name" v-model="account.name" :disabled="!modif">
            </div>
            <div class="box-input">
                <label for="first_name">Prénom</label>
                <input type="text" class="form-control" id="first_name" v-model="account.firstname" :disabled="!modif">
            </div>
            <div class="box-input">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" v-model="account.email" :disabled="!modif">
            </div>
            <div class="box-input">
                <label for="tel">Téléphone</label>
                <input type="text" class="form-control" id="tel" v-model="account.telephone" :disabled="!modif">
            </div>
            <div class="flex" style="justify-content: space-around; width: 30%; height: 85px;">
                <button class="btn" @click="changeBoolMdp = !changeBoolMdp">Changer de mot de passe</button>
                <button class="btn" v-if="!modif" @click="modif = !modif">Modifier</button>
                <button class="btn" @click="modifUser()" v-if="modif">Enregistrer</button>
                <button class="btn" style="background-color: red;" @click="deleteUser()">Supprimer son compte</button>
            </div>
        </div>
        <div v-else class="flex" style="flex-direction: column;">
            <h2>Changer son mot de passe</h2>
            <button class="btn" @click="changeBoolMdp = !changeBoolMdp">Retour</button>
            <div class="box-input">
                <label for="mdp">Ancien mot de passe</label>
                <input type="password" class="form-control" id="mdp" v-model="Amdp">
            </div>
            <div class="box-input">
                <label for="new_mdp">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="new_mdp" v-model="mdp">
            </div>
            <div class="box-input">
                <label for="new_mdp2">Confirmer le nouveau mot de passe</label>
                <input type="password" class="form-control" id="new_mdp2" v-model="mdp2" @keydown.enter="changeMdp()">
            </div>
            <button class="btn" @click="changeMdp()">Valider</button>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                account : {},
                modif : false,
                changeBoolMdp : false,
                Amdp : '',
                mdp : '',
                mdp2 : '',
            }

        },

        methods: {
            async modifUser() {
                try {
                    let response = await axios.post('/myAccount/userPost', {'user' : this.account});
                    response = response.data
                    if(response.statut != 'ok') {
                        Swal.fire({title:'Erreur', text:'Une erreur innatendue s\'est produite', icon:'error', position:'top-end'});
                    }
                    else{
                        this.modif = !this.modif;
                        Swal.fire({title:'Super!', text:'Vos informations ont bien été modifiées', icon:'success', position:'top-end'});
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({title:'Erreur', text:'Une erreur innatendue s\'est produite', icon:'error', position:'top-end'});
                }
            },

            deleteUser(){
                Swal.fire({
                    title: 'êtes-vous sûr?',
                    text: "Voulez-vous vraiment supprimer votre compte?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer mon compte!',
                    cancelButtonText: 'Non, annuler!',
                    reverseButtons: true,
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            let response = await axios.post('/myAccount/delete');
                            response = response.data;
                            if (response.statut != 'ok') {
                                Swal.fire({ title: 'Erreur', text: 'Une erreur innatendue s\'est produite.', icon: 'error', position: 'top-end' });
                            } else {
                                Swal.fire({ title: 'Super!', text: 'Votre compte a bien été supprimé.', icon:'success', position: 'top-end' }).then(() => {
                                    window.location.href = '/';
                                });
                            }
                        } catch (error) {
                            console.error(error);
                            Swal.fire({ title: 'Erreur', text: 'Une erreur innatendue s\'est produite.', icon: 'error', position: 'top-end' });
                        }
                    }
                });
            },

            async changeMdp() {
                if (this.mdp === this.mdp2) {
                    try {
                        let response = await axios.post('/myAccount/changeMdp', { 'mdp': this.Amdp, 'new_mdp': this.mdp });
                        response = response.data;
                        if (response.statut != 'ok') {
                            if (response.statut == 'mdp') {
                                Swal.fire({ title: 'Erreur', text: 'Le mot de passe actuel ne correspond pas.', icon: 'error', position: 'top-end' });
                            }
                            else if(response.statut === 'count') {
                                Swal.fire({ title: 'Erreur', text: 'Mot de passe trop court.', icon: 'error', position: 'top-end' });
                            }
                            else {
                                Swal.fire({ title: 'Erreur', text: 'Une erreur inattendue s\'est produite.', icon: 'error', position: 'top-end' });
                            }
                        } else {
                            this.changeBoolMdp = !this.changeBoolMdp;
                            Swal.fire({ title: 'Super!', text: 'Le mot de passe a bien été modifié.', icon: 'success', position: 'top-end' });
                        }
                    } catch (error) {
                        let message = '';

                        if (error.response.data.errors.new_mdp) {
                            message += error.response.data.errors.new_mdp.join(' '); 
                        }

                        if (error.response.data.errors.mdp) {
                            if (message) { 
                                message += ' ';
                            }
                            message += error.response.data.errors.mdp.join(' '); 
                        }

                        Swal.fire({ title: 'Erreur', text: message, icon: 'error', position: 'top-end' });
                    }
                } else {
                    Swal.fire({ title: 'Erreur', text: 'Les mots de passe ne sont pas identiques', icon: 'error', position: 'top-end' });
                }
            }
        },

        computed: {

        },

        async mounted() {
            try {
                let resp = await axios.get('/myAccount/user');
                resp = resp.data
                this.account = resp;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        }

    }
</script>
<style scoped>
.btn{
    margin: 2px;
}
</style>
