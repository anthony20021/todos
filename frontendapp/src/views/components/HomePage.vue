<template>
  <div>
    <div class="image-container">
      <div class="text-container">
        <div class="text-content">
          <h1>Gérer vos tâches, liste de course, projet avec Todos !</h1>
          <p>Todos l'application simpliste qui vous permet de créer des listes de tâche et les partager avec vos amis.</p>
          <a href="/register" class="register-link">Commencer à créer dès maintenant</a>
          <p>Déjà inscrit ? <a href="/login" class="login-link">Se connecter</a></p>
        </div>
      </div>
    </div>
    <p class="info-text">APP 1.0</p>
  </div>

</template>

<script>
export default {
  data() {
    return {
      todosLogo:'@img/todos.png'
    };
  }
};
</script>
<style>
body {
  background-color: #e0d7e9;
}

.image-container {
  position: relative;
  height: 600px;
  width: 100%;
  margin: 20px auto;
}

.image {
  height: 100%;
  width: 100%;
  object-fit: cover;
}

.text-container {
  position: absolute;
  top: 0;
  left: 0;
  background: linear-gradient(21deg, rgba(203,91,255,0.2455182756696429) 0%, rgba(255,0,0,0.2007003484987745) 14%, rgba(0,0,0,0) 22%, rgba(174,3,255,0.10826337370885852) 38%, rgba(203,91,255,0.23431379387692575) 63%);
  height: 100%;
  width: 100%;
  color: #341753;
  display: flex;
  justify-content: center;
}

.text-content {
  margin: 20px;
  text-align: center;
  display: flex;
  justify-content: center;
  align-self: center;
  flex-direction: column;
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
}

@media (max-width: 480px){
  .text-content{
    font-size: 13px;
  }
}
</style>
