<template>
    <div style="margin-top: 145px; " v-if="!editListe">
        <div class="share-liste">
            <button v-if="allUser.length>1" class="btn" style="background-color: chocolate; height: 40px;" @click="affAssignement = !affAssignement">{{ affAssignement ? "Retour" : "Assigner des tâches" }}</button>
            <div v-if="owner == user_id && allUser && allUser.length > 1" class="share-box">
                <div style="display: flex; justify-content: space-around; align-items: center;">
                    <h3>Liste partagé avec : </h3>
                    <ul>
                        <li style="width: 100px; background-color: transparent; white-space: nowrap;" v-for="user in allUser">
                            <span v-if="user.user_id != user_id" style="cursor: pointer;">
                                - {{ user.user.firstname }} {{ user.user.name }}²
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <p>Double cliquez ou appuyez longtemps sur une tâche pour la modifier</p>
        <button class="btn add" @click="back">Retour</button>
        <div v-if="showCreateTask" style="display: flex; justify-content: center; margin-top: 8%;">
            <div class="box-input">
                <label for="task-name">Nom</label>
                <input type="text" v-model="newTask.name" id="task-name" style="margin-bottom: 15px;" @keydown.enter="addTask">
                <button class="btn" @click="addTask">Ajouter la tâche</button>
            </div>
        </div>
        <div v-else style="margin-top: 40px;">
            <ul style="padding: 0px;">
                <li v-if="task && task.length>0" v-for="tache in task" :style="{ backgroundColor: tache.tache.checked ? 'grey' : ''  }" :class="'liste'+liste.style" @click="tache.tache.checked = !tache.tache.checked, changeChecked(tache.tache.id, tache.tache.checked)" @dblclick="tache.tache.modif = true" @touchstart="touchStart" @touchend="modifTaskMenu(tache.tache.id)" style="cursor: pointer; margin-bottom: 7px;">
                    <input type="checkbox" v-model="tache.tache.checked" @change="changeChecked(tache.tache.id, tache.tache.checked)">
                    <p v-if="!tache.tache.modif" style="margin: 0; height: 39px; display: flex; flex-direction: column; justify-content: center;">{{ tache.tache.name }}</p>
                    <div v-else style="display: flex;" @click.stop>
                        <input type="text" v-model="tache.tache.name">
                        <button class="btn" @click="updateTask(tache)">Modifier</button>
                    </div>
                    <div style="display: flex;" v-if="affAssignement">
                        <select v-if="owner == user_id || tache.tache.user_id == user_id" v-model="tache.tache.assignement" class="select-assignement" @change="updateTask(tache);" @click.stop>
                            <option :value="null">Tout le monde</option>
                            <option v-for="user in allUser" :key="user.user_id" :value="user.user_id">{{ user.user.firstname }} {{ user.user.name }}</option>
                        </select>
                    </div>
                    <button class="btn" style="background-color:white; height: 30px; padding: 3px; margin-right: 10px; "  @click.stop="deleteTask(tache.tache.id)" v-if="(owner == user_id || tache.tache.user_id == user_id) && !tache.tache.modif">❌</button>
                    <span v-else style="width: 90px;"></span>
                </li>
                <p v-else style="margin-left: 20px;">Il n'y a toujours pas de tâches dans la liste, <a style="color: blue;" @click="showCreateTask = !showCreateTask">Créer ma première tâche</a>.</p>
            </ul>
        </div>
        <button class="btn" style="background-color: cadetblue;" @click="editListe = !editListe">Modifier la liste</button>
        <button class="btn" style="background-color: red; margin-left: 10px;" @click="deleteListe()" v-if="owner == user_id" >Supprimer la liste</button>
        <button class="btn" style="background-color: red; margin-left: 10px;" @click="leaveListe()" v-if="owner != user_id" >Quitter la liste</button>
        <button class="btn" style="background-color: green; margin-left: 10px;" @click="showShareListe = !showShareListe"  v-if="owner == user_id" >{{ showShareListe ? "Annuler" : "Partager"}}</button>
        <div class="box-input" v-if="showShareListe">
            <label for="task-name">Email</label>
            <input type="email" v-model="sharingEmail" id="task-name" style="margin-bottom: 15px;" @keydown.enter="shareListe()">
            <button class="btn" @click="shareListe()">Partager la liste</button>
        </div>
        <button class="btn addTask" @click="showCreateTask = !showCreateTask">
            {{ showCreateTask ? "Retour" : "Ajouter une tâche" }}
        </button>
    </div>
    
    <div v-else>
        <div class="margin-xl">
            <h2>Modifier la liste</h2>
            <div class="flex">
                <button class="btn" @click="putListe()">Modifier</button>
            </div>
            <div>
                <div class="box-input">
                    <label for="task-name">Nom</label>
                    <input type="text" v-model="liste.name" id="task-name" style="margin-bottom: 15px;">
                </div>
                <div class="flex" style="flex-wrap: wrap;">
                    <div class="flex container-style" v-for="(item, index) in stylesList" :key="index">
                        <input type="radio" :id="'style' + item.value" :value="item.value" v-model="liste.style">
                        <div :class="'box-liste liste' + item.value" @click="liste.style = item.value" style="margin-left: 5%; margin-right: 5%;">{{ item.name }}</div>
                    </div>
                </div>
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
        allUser : [],
        sharingEmail : "",
        newTask: {
            name: "",
        },
        touchStartTime: null,
        touchTimer: null,
        isLongPress: false,
        task: [],
        showCreateTask: false,
        showShareListe: false,
        editListe: false,
        affAssignement:false,
        stylesList: [
            { value: 1, name: 'Morning Sunglass' },
            { value: 2, name: 'Midnight Sunglass' },
            { value: 3, name: 'Alien Sunglasses' },
            { value: 4, name: 'Midday Sunglasses' },
            { value: 5, name: 'Twilight Sunglasses' },
            { value: 6, name: 'Default' },
            { value: 7, name: 'Galaxy' },
            { value: 8, name: 'Earth' },
            { value: 9, name: 'Sun' },
            { value: 10, name: 'Moon' },
            { value: 11, name: 'Rainbow' },
        ],
    };
    },
    props: {
    list_id: {
        type: Number,
        required: true
    },
    liste: {
        type: Object,
        required: true
    },
    owner: {
        type: Number,
        required: true
    },
    user_id: {
        type: Number,
        required: true
    },
    owner_name: {
        type: String,
        required: true
    },
    },
    methods: {

        back() {
            this.$emit('back');
        },

        //A utiliser dans le cas d'un appuie long. le mettre dans touchstart
        touchStart() {
            // Enregistrer le moment du début du toucher
            this.touchStartTime = Date.now();

            // Définir un timer pour détecter l'appui long (1 seconde)
            this.touchTimer = setTimeout(() => {
                this.isLongPress = true; 
            }, 500); 
        },

        //Afficher la modif task
        modifTaskMenu(task_id) {
            if (this.touchTimer) {
                clearTimeout(this.touchTimer);
                this.touchTimer = null;
            }

            if (this.isLongPress) {
                //passer modif de la task à true
                this.task.forEach(t => {
                    if(t.tache.id == task_id){
                        t.tache.modif =!t.tache.modif;
                    }
                });
                this.isLongPress = false;
            }
        },

        async shareListe(){
            this.sharingEmail = this.sharingEmail.toLowerCase();
            try{
                const response = await axios.post('/dashboard/shareListe', {'list_id' : this.list_id, 'email' : this.sharingEmail})
                this.allUser = response.data;
                Swal.fire({title:'Succès', text:'La liste a bien été partagé', icon:'success', position:'top-end'});
            }
            catch(error){
                Swal.fire({
                    title:'Erreur',
                    text:"Votre liste n'a pas été partagé.",
                    icon:'error',
                    position: 'top-end',
                });
            }
        },

        async putListe() {

            try {
                const result = await axios.post('/dashboard/putListe', {'list_id' : this.list_id, 'name' : this.liste.name, 'style' : this.liste.style})
                if(result.data.statut == 'ok'){
                    Swal.fire({title:'Succès', text:'La liste a bien été modifié', icon:'success', position:'top-end'});
                    this.$emit('putListe', this.liste);
                }
                else{
                    Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
                }
            } catch (error) {
                console.error(error);
                Swal.fire({title:'Erreur', text:'Une erreur inattendue s\'est produite', icon:'error', position:'top-end'});
            }

        },

        deleteListe() {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action est irréversible',
                icon: 'warning',
                position: 'top-end',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText:'Annuler',
            }).then( async (result) => {
                if (result.isConfirmed) {
                    try{
                        const result = await axios.post('/dashboard/deleteListe', {'list_id' : this.list_id})
                        Swal.fire({
                            title:'Supprimé !',
                            text:'Votre liste a été supprimée.',
                            icon:'success',
                            position: 'top-end',
                        });
                        this.$emit('delete', result)
                    }
                    catch(error){
                        if(error.response?.status==555){
                            Swal.fire({
                                title:'Erreur',
                                text:"Vous n'avez pas la permission",
                                icon:'error',
                                position: 'top-end',
                        });
                        }
                        else{
                            Swal.fire({
                                title:'Erreur',
                                text:"Votre liste n'a pas été supprimée.",
                                icon:'error',
                                position: 'top-end',
                            });
                        }
                    }
                }
            });
        },

        leaveListe() {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Le propriétaire devra vous réinviter si vous voulez retrouver la liste',
                icon: 'warning',
                position: 'top-end',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Quitter',
                cancelButtonText:'Annuler',
            }).then( async (result) => {
                if (result.isConfirmed) {
                    try{
                        const result = await axios.post('/dashboard/leaveListe', {'list_id' : this.list_id,})
                        Swal.fire({
                            title:'Vous avez bien quitté la liste',
                            icon:'success',
                            position: 'top-end',
                        });
                        this.$emit('delete', result)
                    }
                    catch(error){
                        if(error.response?.status==555){
                            Swal.fire({
                                title:'Erreur',
                                text:"Vous n'avez pas la permission",
                                icon:'error',
                                position: 'top-end',
                        });
                        }
                        else{
                            Swal.fire({
                                title:'Erreur',
                                text:"Vous n'avez pas quitté la liste.",
                                icon:'error',
                                position: 'top-end',
                            });
                        }
                    }
                }
            });
        },

        deleteTask(id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action est irréversible',
                icon: 'warning',
                position: 'top-end',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText:'Annuler',
            }).then( async (result) => {
                if (result.isConfirmed) {
                    try{
                        const result = await axios.post('/dashboard/deleteTask', {'task_id' : id, 'list_id' : this.list_id})
                        Swal.fire({
                            title:'Supprimé !',
                            text:'Votre tâche a été supprimée.',
                            icon:'success',
                            position: 'top-end',
                        });
                        this.task = result.data;
                    }
                    catch(error){
                        if(error.response?.status==555){
                            Swal.fire({
                                title:'Erreur',
                                text:"Vous n'avez pas la permission",
                                icon:'error',
                                position: 'top-end',
                        });
                        }
                        else{
                            Swal.fire({
                                title:'Erreur',
                                text:"Votre liste n'a pas été supprimée.",
                                icon:'error',
                                position: 'top-end',
                            });
                        }
                    }
                }
            });
        },

        async addTask() {
            if (!this.newTask.name.trim()) {
                Swal.fire({title:'Erreur', text:'Le nom de la tâche ne peut pas être vide.', icon:'error', position:'top-end'});
                return;
            }
            try {
                const response = await axios.post('/dashboard/addTask', {
                    task: this.newTask,
                    list_id: this.list_id
                });
                this.task = response.data;
                Swal.fire({title:'Succès', text:'Tâche ajoutée avec succès', icon:'success', position:'top-end'});
                this.showCreateTask = false;
                this.newTask.name = "";
            } catch (error) {
                console.error(error.message);
                Swal.fire({title:'Erreur', text:'Impossible d\'ajouter la tâche', icon:'error', position:'top-end'});
            }
        },

        async updateTask(task) {
            try{
                const response = await axios.post('/dashboard/modifDataTask', {'task' : task})
                if(response.data.statut == "ok"){
                    Swal.fire({title:'Succès', text:'Tâche modifié', icon:'success', position:'top-end'});
                    this.task.forEach((t, index) => {
                        t.tache.modif = false;
                    });
                }
                else{
                    Swal.fire({title:'Erreur', text:'Impossible de modifier la tâche', icon:'error', position:'top-end'});
                }
            }
            catch(error){
                console.error(error.message)
            }
        },

        async changeChecked(id, bool){
            try{
                await axios.post('/dashboard/modifTask', {'task_id' : id, 'bool' : bool})
            }
            catch(error){
                console.error(error.message)
            }
        },

    },

    async mounted() {
        try {
            const response = await axios.post('/dashboard/tache/getData', {'list_id' : this.list_id});
            this.task = response.data.taches;
            if(response.data.allUser != ""){
                this.allUser = response.data.allUser;
            }
        } catch (error) {
            console.error(error.message);
    }
}
};
</script>

<style scoped>
.addTask{
    position: fixed;
    bottom: 20px;
    left: 50%;
    z-index: 4;
    transform: translateX(-50%);
    border-radius: 60px;
    width: 600px;
    height: 50px;
}
.add {
    position: absolute;
    top: 92px;
    right: 7px;
}
.box-liste {
    width: 350px;
}
li{
    display: flex;
    width: 100%;
    justify-content: space-between;
    padding: 1px;
    align-items: center;
    box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.089);
    border : 1px solid rgba(0, 0, 0, 0.089);
}
.share-liste{
    display: flex;
    justify-content: space-between;
}
.share-box{
    background-color:deeppink;
    color: aliceblue;
    padding: 1px;
    width: 50%;
    border-radius: 15px 0px 0px 15px;
}
.select-assignement{
    width: 200px;
}
@media (max-width: 1024px) {
    .share-liste{
        flex-direction: column;
    }
    .container-style{
        width: 100%;
    }
    .addTask{
        width: 200px;
        height: 50px;
    }
    .share-box{
        width: 100% !important;
        border-radius: 15px !important;
        margin-top: 10px;
    }
    .box-liste {
        width: 90%;
    }
    .select-assignement{
        width: 120px;
    }
}




</style>
