<template>
    <div class="modal" @click="close()">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" @click="close()" style="cursor: pointer;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <h2>Changer sa photo de profil</h2>
            </div>
            <div class="modal-body">
                <div style="display: flex; flex-direction: column;">
                    <label for="file-upload" class="custom-file-label">
                        Sélectionner un fichier
                        <input type="file" id="file-upload" @change="onFileChange" />
                    </label>
                    <img class="margin" v-if="imagePreview" :src="imagePreview" alt="preview" style="max-height: 500px; width: auto;"/>
                    <button class="btn margin" @click="uploadImage">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Swal from'sweetalert2';
    export default {
        data() {
            return {
                imageSrc: null,
                imagePreview: null,
            }
        },
        methods: {
            close() {
                this.$emit('close')
            },
            onFileChange(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith("image/")) {
                    this.imageSrc = file;  
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;  
                    };
                    reader.readAsDataURL(file);  
                } else {
                    Swal.fire({
                        title: "Erreur",
                        text: "Veuillez sélectionner un fichier image valide.",
                        icon: "error",
                        confirmButtonText: "Ok",
                    })
                }
            },
            async uploadImage(){
                if (!this.imageSrc) {
                    Swal.fire({
                        title: "Erreur",
                        text: "Aucune image sélectionnée.",
                        icon: "error",
                        confirmButtonText: "Ok",
                    })
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('image', this.imageSrc); // Envoie le fichier directement
                    const response = await axios.post('/myAccount/upload', formData);
                    let user = response.data.user;
                    this.$emit('updateUser', user); 

                    Swal.fire({
                        title: "Succès",
                        text: "Votre photo de profil a bien été mise à jour.",
                        icon: "success",
                        confirmButtonText: "Ok",
                    })
                    this.close();
                } catch (error) {
                    Swal.fire({
                        title: "Erreur",
                        text: "Une erreur s'est produite lors de la mise à jour de votre photo de profil.",
                        icon: "error",
                        confirmButtonText: "Ok",
                    })
                }
            }
        },
    }
</script>