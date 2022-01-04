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
      <div class="row justify-content-center align-items-center">
        <span
          class="badge rounded-pill fs-5 col-auto m-4"
          v-bind:class="timer > 9 ? 'bg-info' : 'bg-warning'"
        >
          {{ timer }}
        </span>
      </div>
      <div class="row justify-content-center align-items-center">
        <span class="badge bg-secondary fs-4 col-lg-3 col-auto">{{
          words[currentWordIndex]
        }}</span>
      </div>
      <div
        class="row form-floating m-3 justify-content-center align-items-center"
      >
        <div class="col-auto">
          <input
            type="text"
            class="form-control fs-3 text-center"
            id="userInput"
            autocomplete="off"
            v-model="userInput"
            v-on:keyup.enter="submitWord"
            v-on:keydown.space="submitWord"
          />
        </div>
      </div>
      <div class="row justify-content-center align-items-center">
        <button class="col-lg-2 col-auto m-4 bg-light" v-on:click="startGame">
          Restart
        </button>
        <button class="col-lg-2 col-auto m-4 bg-light" v-on:click="quitGame">
          Main Menu
        </button>
      </div>
      <div class="row justify-content-center align-items-center">
        <div class="col-4">
          <span
            v-for="(userInputWord, index) in userInputWords"
            :key="index"
            class="badge m-1 fs-6"
            v-bind:class="userInputWord.isMatch ? 'bg-success' : 'bg-danger'"
            >{{ userInputWord.word }}</span
          >
        </div>
      </div>
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
import GameEndingComponent from "./GameEndingComponent.vue";

export default {
  components: {
    MainMenuComponent,
    GameEndingComponent,
  },
  data() {
    return {
      gameRunning: false,
      gameEnded: false,
      wordLanguage: "en",
      words: [],
      currentWordIndex: 0,
      userInput: "",
      userInputWords: [],
      timer: 0,
      timerInterval: 0,
      points: 0,
    };
  },
  methods: {
    startGame(wordLanguage) {
      this.words = [];
      this.wordLanguage = wordLanguage;
      axios.get("/words/" + wordLanguage).then((response) => {
        this.words = response.data.words;
        this.currentWordIndex = 0;
        this.userInput = "";
        this.userInputWords = [];
        this.points = 0;
        this.startTimer();
        this.gameEnded = false;
        this.gameRunning = true;
      });
    },
    quitGame() {
      this.gameRunning = false;
      this.gameEnded = false;
      this.timer = -1;
      clearInterval(this.timerInterval);
    },
    submitWord() {
      let userInputtedWord = this.userInput.trim().toLowerCase();
      let currentWord = this.words[this.currentWordIndex];

      let wordsMatch = this.checkInput(currentWord, userInputtedWord);

      this.userInputWords.push({ word: userInputtedWord, isMatch: wordsMatch });
      this.userInput = "";
      this.currentWordIndex = this.currentWordIndex + 1;
    },
    checkInput(originalWord, reviewedWord) {
      let maxPoints = originalWord.length;
      return (
        maxPoints ===
        this.calculatePoints(originalWord, reviewedWord, maxPoints)
      );
    },
    calculatePoints(originalWord, reviewedWord, maxPoints) {
      if (originalWord !== reviewedWord) {
        let originalWordLength = originalWord.length;
        let reviewedWordLength = reviewedWord.length;

        let negativePoints = originalWordLength - reviewedWordLength;

        if (Math.sign(negativePoints) === -1) {
          negativePoints = negativePoints *= -1;
        }

        originalWord.split("").forEach(function (val, i) {
          if (val != reviewedWord.charAt(i)) negativePoints += 1;
        });

        maxPoints = maxPoints - negativePoints;
      }

      this.points = maxPoints + this.points;
      return maxPoints;
    },
    startTimer() {
      clearInterval(this.timerInterval);
      this.timer = 5;
      this.timerInterval = setInterval(() => {
        if (this.timer === 0) {
          clearInterval(this.timerInterval);
          this.endGame();
        } else {
          this.timer--;
        }
      }, 1000);
    },
    endGame() {
      if (this.points < 0) this.points = 0;
      this.gameEnded = true;
    },
  },
};
</script>
