<template>
    <div>
        <div class="flex" style="flex-direction: column;" v-if="!changeMdp">
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
                <button class="btn" @click="changeMdp = !changeMdp">Changer de mot de passe</button>
                <button class="btn" v-if="!modif" @click="modif = !modif">Modifier</button>
                <button class="btn" @click="modifUser()" v-if="modif">Enregistrer</button>
            </div>
        </div>
        <div v-else class="flex" style="flex-direction: column;">
            <h2>Changer son mot de passe</h2>
            <button class="btn" @click="changeMdp = !changeMdp">Retour</button>
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
                <input type="password" class="form-control" id="new_mdp2" v-model="mdp2">
            </div>
            <button class="btn" @click="changeTheMdp()">Valider</button>
        </div>
    </div>
</template>

<script>
import fetchWithCredentials from '@/network';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            account: {},
            modif: false,
            changeMdp: false,
            Amdp: '',
            mdp: '',
            mdp2: '',
        }
    },

    methods: {
        async modifUser() {
            try {
                let response = await fetchWithCredentials('/myAccount/userPost', 'POST', { 'user': this.account });
                if (response.statut !== 'ok') {
                    Swal.fire({ title: 'Erreur', text: 'Une erreur inattendue s\'est produite', icon: 'error', position: 'top-end' });
                } else {
                    this.modif = !this.modif;
                    Swal.fire({ title: 'Super!', text: 'Vos informations ont bien été modifiées', icon: 'success', position: 'top-end' });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({ title: 'Erreur', text: 'Une erreur inattendue s\'est produite', icon: 'error', position: 'top-end' });
            }
        },

        async changeTheMdp() {
            if (this.mdp === this.mdp2) {
                try {
                    let response = await fetchWithCredentials('/myAccount/changeMdp', 'POST', { 'mdp': this.Amdp, 'new_mdp': this.mdp });
                    if (response.statut !== 'ok') {
                        if (response.statut === 'mdp') {
                            Swal.fire({ title: 'Erreur', text: 'Le mot de passe actuel ne correspond pas', icon: 'error', position: 'top-end' });
                        }
                        else if(response.statut === 'count') {
                            Swal.fire({ title: 'Erreur', text: 'Mot de passe trop court', icon: 'error', position: 'top-end' });
                        }
                        else {
                            Swal.fire({ title: 'Erreur', text: 'Une erreur inattendue s\'est produite', icon: 'error', position: 'top-end' });
                        }
                    } else {
                        this.changeMdp = !this.changeMdp;
                        Swal.fire({ title: 'Super!', text: 'Le mot de passe a bien été modifié', icon: 'success', position: 'top-end' });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({ title: 'Erreur', text: 'Une erreur inattendue s\'est produite', icon: 'error', position: 'top-end' });
                }
            } else {
                Swal.fire({ title: 'Erreur', text: 'Les mots de passe ne sont pas identiques', icon: 'error', position: 'top-end' });
            }
        }
    },

    async mounted() {
        try {
            let resp = await fetchWithCredentials('/myAccount/user');
            this.account = resp;
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }
}
</script>
