<template>
  <div>
    <div>
      <div class="container">
        <div class="flex" style="justify-content: center;">
          <div class="center">
            <h1>Gérer vos tâches, liste de course, projet avec Todos !</h1>
            <p>Todos le site simpliste qui vous permet de créer des listes de tâche et les partager avec vos amis.</p>
            <div>
                <a href="/register" class="register-link">Commencer à créer dès maintenant</a>
            </div>
            <p>Déjà inscrit ? <a href="/login" class="login-link">Se connecter</a></p>
          </div>
          <div class="center">
            <img :src="checklist" alt="checklist image" class="image-checklist">
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  data() {
    return {
      checklist:'img/checklist.jpg',
    };
  }
};
</script>
<style scoped>
body {
  background-color: #e0d7e9;
}

.image-checklist {
  width: 60%;
  height: auto;
}

.container {
  position: absolute;
  top: 0;
  left: 0;
  background: linear-gradient(21deg, rgba(203,91,255,0.2455182756696429) 0%, rgba(255,0,0,0.2007003484987745) 14%, rgba(0,0,0,0) 22%, rgba(174,3,255,0.10826337370885852) 38%, rgba(203,91,255,0.23431379387692575) 63%);
  height: 100%;
  width: 100%;
  color: #341753;
  display: flex;
  justify-content: center;
  margin-top: 60px;
}

.text-content {
  margin: 20px;
  text-align: center;
  display: flex;
  justify-content: center;
  align-self: center;
  font-size: 22px;
}

.register-link {
  color: #060C92;
  font-size: 25px;
  font-weight: 600;
}

.login-link {
  color: #060C92;
}

.info-text {
  margin-left: 10%;
}

@media (max-width: 1024px) {


  .image-container {
      height: 800px;
      width: 100%;
  }

  .info-text {
      margin-left: 5%;
  }
  .text-content{
      font-size: 17px;
  }
  .container{
    margin-top: 110px;
  }
}

@media (max-width: 480px){
.text-content{
    font-size: 13px;
}
}
</style>
