<template>
    <div>
        <div class="flex" style="flex-direction: column;">
            <h2>Mon compte</h2>
            <div class="box-input">
                <label for="name">Nom</label>
                <input type="text" class="form-control" id="name" :value="user.name">
            </div>
            <div class="box-input">
                <label for="first_name">Prénom</label>
                <input type="text" class="form-control" id="first_name" :value="user.firstname">
            </div>
            <div class="box-input">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" :value="user.email">
            </div>
            <div class="box-input">
                <label for="tel">Téléphone</label>
                <input type="text" class="form-control" id="tel" :value="user.tel">
            </div>
            <button class="btn">Enregistrer</button>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

    export default {
        data() {
            return {
                user : {}, 
            }

        },

        methods: {
            // Your methods
        },
        
        computed: {
            // Your computed properties
        },

        async mounted() {
            try {
                const resp = await axios.get('/myAccount/user');
                this.user = resp.data;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        }

    }
</script>