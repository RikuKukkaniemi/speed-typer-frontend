<template>
  <div class="container mt-4">
    <div class="row justify-content-center align-items-center">
      <h1 class="text-center m-5">Speed Typer</h1>
    </div>
    <div v-if="!gameRunning">
      <main-menu-component @chooseLanguage="startGame" />
    </div>
    <div
      class="row justify-content-center align-items-center"
      v-if="gameRunning && !gameEnded"
    >
      <game-running-component
        :wordLanguage="wordLanguage"
        :words="words"
        @restartGame="startGame"
        @quitGame="quitGame"
        @endGame="endGame"
      />
    </div>
    <div v-if="gameEnded">
      <game-ending-component
        :points="points"
        :wordLanguage="wordLanguage"
        @playAgain="startGame"
        @returnMainMenu="quitGame"
      />
    </div>
  </div>
</template>

<script>
import MainMenuComponent from "./MainMenuComponent.vue";
import GameRunningComponent from "./GameRunningComponent.vue";
import GameEndingComponent from "./GameEndingComponent.vue";

export default {
  components: {
    MainMenuComponent,
    GameRunningComponent,
    GameEndingComponent,
  },
  data() {
    return {
      gameRunning: false,
      gameEnded: false,
      wordLanguage: "en",
      words: [],
      points: 0,
    };
  },
  methods: {
    startGame(wordLanguage) {
      this.words = [];
      this.wordLanguage = wordLanguage;
      axios.get("/words/" + wordLanguage).then((response) => {
        this.words = response.data.words;
        this.gameEnded = false;
        this.gameRunning = true;
      });
    },
    quitGame() {
      this.gameRunning = false;
      this.gameEnded = false;
    },
    endGame(points) {
      points < 0 ? (this.points = 0) : (this.points = points);
      this.gameEnded = true;
    },
  },
};
</script>
