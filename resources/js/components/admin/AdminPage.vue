<template>
    <div class="margin-xl flex" style="flex-direction: column;">
        <h1>Page d'administration</h1>
        <h3>Administration des users</h3>
        <div style="width: 100%;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.firstname }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <div v-for="role in user.roles">
                                {{ role.name }}
                            </div>
                        </td>
                        <td>
                            <button class="btn" @click="editUser(user)" style="background-color: green;">Modifier</button>
                            <button class="btn" @click="deleteUser(user.id)" style="background-color: red;">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3>Administration des permissions</h3>
        <div style="width: 100%;">
            <button class="btn">Ajouter</button>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="permission in permissions" :key="permission.id">
                        <td>{{ permission.permission }}</td>
                        <td>
                            <button class="btn" @click="deletePermission(permission.id)" style="background-color: red;">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3>Administration des rôles</h3>
        <div style="width: 100%;">
            <button class="btn">Ajouter</button>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles" :key="role.id">
                        <td>{{ role.name }}</td>
                        <td>
                            <button class="btn" @click="deleteRole(role.id)" style="background-color: red;">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal" v-if="edit" @click="edit = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" @click="edit = false" style="cursor: pointer;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <h2>Modifier un user</h2>
            </div>
            <div class="modal-body">
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
                    <input type="password" id="password" v-model="user.password">
                </div>
                <!-- A faire : créer un selecteur multiple pour gérer le multi role -->
                <div class="box-input">
                    <label for="role">Rôle</label>
                    <select id="role" v-model="user.roles[0].id">
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                </div>
                <button class="btn" style="background-color: green;" @click="updateUser()">Valider</button>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2';
export default {
    data() {
        return {
            users:[],
            roles:[],
            permissions:[],
            user:{},
            edit:false,
        }
    },
    methods: {
        editUser(user) {
            this.edit = true;
            this.user = user;
        },
        async deleteUser(id) {
                Swal.fire({
                    title: 'Etes-vous sur de vouloir supprimer cet utilisateur ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer'
                })

                .then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await axios.post('/admin/deleteUser', {'id' : id});
                            if(response.data.statut !== 'ok') {
                                Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                            } else {
                                this.users = this.users.filter(user => user.id !== id);
                                Swal.fire({title:'Super!', text:'L\'utilisateur a bien été supprimé', icon:'success', position:'top-end'});
                            }
                        } catch (error) {
                            console.error(error.message);
                        }
                    }
                })
        },
        async updateUser() {
            try {
                const result = await axios.post('/admin/modifUser', {user : this.user});
                if(result.data.statut !== 'ok') {
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                }
                else {
                    this.edit = false;
                    Swal.fire({title:'Super!', text:'L\'utilisateur a bien été modifié', icon:'success', position:'top-end'});
                }
            } catch (error) {
                console.error(error.message);
            }
        }
    },
    async mounted() {
        try {
            const response = await axios.get('/admin/users');
            if(response.data.statut !== 'ok') {
                Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
            } else {
                this.users = response.data.users;
                this.roles = response.data.roles;
                this.permissions = response.data.permissions;
            }
        } catch (error) {
            console.error(error.message);
        }
    }
}
</script>
