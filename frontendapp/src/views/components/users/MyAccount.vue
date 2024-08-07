<template>
    <div>
        <div class="flex" style="flex-direction: column;">
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
            <button class="btn" v-if="!modif" @click="modif = !modif">Modifier</button>
            <button class="btn" @click="modifUser()" v-if="modif">Enregistrer</button>
        </div>
    </div>
</template>
<script>
import fetchWithCredentials from '@/network';
import axios from 'axios';
import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                account : {},
                modif : false,
            }

        },

        methods: {
            async modifUser() {
                try {
                    let response = await fetchWithCredentials('/myAccount/user', 'POST', {'user' : this.account});
                    console.log(this.account);
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
            }
        },

        computed: {

        },

        async mounted() {
            try {
                const resp = await fetchWithCredentials('/myAccount/user');
                console.log(resp)
                this.account = resp;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        }

    }
</script>
