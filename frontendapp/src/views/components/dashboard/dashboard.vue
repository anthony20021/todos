<template>
  <div>
    <div>
      <button class="btn add" @click="changeViewList" v-if="!showTask" >{{ showCreateList ? "Retour" : "Ajouter une liste" }}</button>
    </div>
    <div v-if="!showCreateList && !showTask">
      <h2 style="margin-left: 20px;">Vos listes : </h2>
      <div  style="margin-left: 20px;" v-if="listes && listes.length > 0">
        <div class="flex" style="flex-wrap: wrap; justify-content: center;">
            <div v-for="list in listes" class="box-liste" @click="openTask(list)">
                <p>{{ list.liste.name }}</p>
                <p v-if="data.user.id != list.liste.owner" style="font-size: 12px; position: absolute; top: 120px;">Créer par : {{ list.owner.firstname + " " + list.owner.name }}</p>
            </div>
        </div>
      </div>
      <p v-else style="margin-left: 20px;">Vous n'avez pas de listes, <a style="color: blue;" @click="changeViewList">Créer une liste</a>.</p>
    </div>
    <div v-if="showCreateList && !showTask" style="display: flex; justify-content: center; margin-top: 8%;">
      <div class="box-input">
        <label for="name">Nom</label>
        <input type="text" v-model="newList.name" style="margin-bottom: 15px;" @keydown.enter="addList">
        <button class="btn" @click="addList">Ajouter la liste</button>
      </div>
    </div>
    <task v-if="showTask" :list_id="currentListName" :owner="currentOwner" :user_id="data.user.id" :owner_name="currentOwnerListName"  @back="showTask = false" @delete="updateListe"></task>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import Task from './task.vue';
import fetchWithCredentials from '@/axios';

export default {
  components: {
    Task
  },
  data() {
    return {
      currentOwnerListName : "",
      showCreateList: false,
      showTask: false,
      listes: [],
      newList: {
        name: "",
      },
      data: [],
      currentListId: {
        type: Number
      },
      currentOwner:{
        type:Number
      }
    };
  },
  methods: {
    changeViewList() {
      this.showCreateList = !this.showCreateList;
    },
    async addList() {
      try {
        const response = await fetchWithCredentials('/dashboard/addListe','POST', this.newList);
        this.listes = response;
        Swal.fire({title:'Succès', text:'Liste ajoutée avec succès', icon:'success', position:'top-end'});
        this.showCreateList=false;
      } catch (error) {
        console.error(error.message);
        Swal.fire({title:'Erreur', text:'Impossible d\'ajouter la liste', icon:'error', position:'top-end'});
      }
    },

    openTask(liste) {
      this.currentOwner = liste.liste.owner
      this.currentListName = liste.liste.id;
      this.currentOwnerListName = liste.owner.firstname + " " + liste.owner.name;
      this.showTask = true;
    },

      updateListe(result){
        this.listes = result;
        this.showTask = false;
      },
  },

  async mounted() {
    try {
      const response = await fetchWithCredentials('/dashboard/getData');
      this.data = response;
      this.listes = this.data.listes;
    } catch (error) {
      console.error(error.message);
    }
  }
};
</script>

<style scoped>
  .add{
      position: fixed;
      bottom: 20px;
      left: 50%;
      z-index: 4;
      transform: translateX(-50%);
      border-radius: 60px;
      width: 600px;
      height: 50px;
  }
  @media (max-width: 1024px) {
      .add{
          width: 200px;
          height: 50px;
      }
  }
</style>
