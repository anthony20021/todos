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
            <button class="btn" @click="editAddPermission = true">Ajouter</button>
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
            <button class="btn" @click="editAddRole = true">Ajouter</button>
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
                            <button class="btn" @click="editTheRole(role)" style="background-color: green">Gérer les permissions</button>
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
                <div class="box-input" v-if="user.roles.length > 0">
                    <label for="role">Rôle</label>
                    <select id="role" v-model="user.roles[0].id">
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                </div>
                <div v-else class="box-input">
                    <label for="role">Rôle</label>
                    <select id="role" v-model="newUserRole" @change="addRoleToUser()">
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                </div>
                <button class="btn" style="background-color: green;" @click="updateUser()">Valider</button>
            </div>
        </div>
    </div>
    <div class="modal" v-if="editRole" @click="editRole = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" @click="editRole = false" style="cursor: pointer;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <h2>Modifier un role</h2>
            </div>
            <div class="modal-body">
                <div class="box-input">
                    <label for="name">Nom</label>
                    <input type="text" id="name" v-model="role.name">
                </div>
                <table class="table">
                    <thead>
                        <th>Nom</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        <tr v-for="permission in role.permissions" :key="permission.id">
                            <td>{{ permission.permission }}</td>
                            <td>
                                <button class="btn" @click="deletePermissionRole(role.id, permission.id)" style="background-color: red;">Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <select id="role" v-model="selectedPermission" style="margin-bottom: 5px;">
                    <option v-for="permission in permissions" :key="permission.id" :value="permission.id">{{ permission.permission }}</option>
                </select>
                <button class="btn" style="background-color: green;" @click="updateRole(role.id, selectedPermission)">Ajouter la permission</button>
            </div>
        </div>
    </div>
    <div class="modal" v-if="editAddRole" @click="editAddRole = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" @click="editAddRole = false" style="cursor: pointer;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <h2>Ajouter un role</h2>
            </div>
            <div class="modal-body">
                <div class="box-input">
                    <label for="name">Nom</label>
                    <input type="text" id="name" v-model="newRole">
                </div>
                <button class="btn" style="background-color: green;" @click="createRole()">Ajouter</button>
            </div>
        </div>
    </div>
    <div class="modal" v-if="editAddPermission" @click="editAddPermission = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" @click="editAddPermission = false" style="cursor: pointer;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <h2>Ajouter une permission</h2>
            </div>
            <div class="modal-body">
                <div class="box-input">
                    <label for="name">Nom</label>
                    <input type="text" id="name" v-model="newPermission">
                </div>
                <button class="btn" style="background-color: green;" @click="createPermission()">Ajouter</button>
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
            role:{},
            selectedPermission:"",
            edit:false,
            editRole:false,
            editAddRole:false,
            editAddPermission:false,
            newRole:"",
            newPermission:"",
            newUserRole:0
        }
    },
    methods: {
        editUser(user) {
            this.edit = true;
            this.user = user;
        },
        editTheRole(role) {
            this.editRole = true;
            this.role = role;
        },
        addRoleToUser() {
            this.user.roles.push(this.newUserRole);
            this.newUserRole = 0;
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
        },
        async deletePermissionRole(id, permissionId) {
            try {
                const response = await axios.post('/admin/deletePermissionRole', {'id' : id, 'permissionId' : permissionId});
                if(response.data.statut !== 'ok') {
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                }
                else {
                    this.role = response.data.role;
                    Swal.fire({title:'Super!', text:'La permission a bien été supprimé', icon:'success', position:'top-end'});
                }
            } catch (error) {
                console.error(error.message);
            }
        },
        async updateRole(id, permissionId) {
            try {
                const response = await axios.post('/admin/updateRole', {'id' : id, 'permissionId' : permissionId});
                if(response.data.statut !== 'ok') {
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                }
                else {
                    this.role = response.data.role;
                    Swal.fire({title:'Super!', text:'La permission a bien été ajoutée', icon:'success', position:'top-end'});
                }
            } catch (error) {
                console.error(error.message);
            }
        },
        async createRole() {
            try {
                const response = await axios.post('/admin/createRole', {'name' : this.newRole});
                if(response.data.statut !== 'ok') {
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                } else {
                    this.roles = response.data.roles;
                    this.newRole = "";
                    this.editAddRole = false;
                    Swal.fire({title:'Super!', text:'Le role a bien été ajouté', icon:'success', position:'top-end'});
                }
            } catch (error) {
                console.error(error.message);
            }
        },

        async createPermission() {
            try {
                const response = await axios.post('/admin/createPermission', {'permission' : this.newPermission});
                if(response.data.statut !== 'ok') {
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                } else {
                    this.permissions = response.data.permissions;
                    this.newPermission = "";
                    this.editAddPermission = false;
                    Swal.fire({title:'Super!', text:'La permission a bien été ajoutée', icon:'success', position:'top-end'});
                }
            } catch (error) {
                console.error(error.message);
            }
        },
        deletePermission(id) {
            Swal.fire({
                title: 'Etes-vous sur de vouloir supprimer cette permission ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer'
            }).then(async (result) => {
                if(result.isConfirmed) {
                    const response = await axios.post('/admin/deletePermission', {'id' : id});
                    if(response.data.statut !== 'ok') {
                        Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                    } else {
                        this.permissions = response.data.permissions;
                        Swal.fire({title:'Super!', text:'La permission a bien été supprimé', icon:'success', position:'top-end'});
                    }
                }
            })
        },
        deleteRole(id) {
            Swal.fire({
                title: 'Etes-vous sur de vouloir supprimer ce role ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer'
            }).then(async (result) => {
                if(result.isConfirmed) {
                    const response = await axios.post('/admin/deleteRole', {'id' : id});
                    if(response.data.statut !== 'ok') {
                        Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                    } else {
                        this.roles = response.data.roles;
                        Swal.fire({title:'Super!', text:'Le role a bien été supprimé', icon:'success', position:'top-end'});
                    }
                }
            })
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
