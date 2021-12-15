<template>
  <div class="container mt-4">
    <div class="row justify-content-center align-items-center">
      <h1 class="text-center m-5">Speed Typer</h1>
    </div>
    <div v-if="!gameRunning">
      <div class="row justify-content-center mb-4">
        <p class="text-center col-4">
            Type the words you as precisely as you can before timer runs out. The more words you get correct the more points you get.
        </p>
        <p class="text-center">Good luck!</p>
      </div>

      <div class="row justify-content-center align-items-center">
        <div class="row justify-content-center">
          <p class="col-4 text-center">Select the language of words:</p>
        </div>
        <form class="mt-1" @submit.prevent="startGame">
          <div class="row justify-content-center align-items-center">
            <div class="col-1">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="flexRadioDefault"
                  id="radioEn"
                  value="en"
                  v-model="wordLanguage"
                  checked
                />
                <label class="form-check-label" for="radioEn"> English </label>
              </div>
            </div>
            <div class="col-1">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="flexRadioDefault"
                  id="radioFi"
                  value="fi"
                  v-model="wordLanguage"
                />
                <label class="form-check-label" for="radioFi"> Finnish </label>
              </div>
            </div>
          </div>

          <div class="row justify-content-center align-items-center">
            <input class="col-2 m-3 bg-light" type="submit" value="Start Game" />
          </div>
        </form>
      </div>
    </div>
    <div
      class="row justify-content-center align-items-center"
      v-if="gameRunning && !gameEnded"
    >
      <div class="row justify-content-center align-items-center">
        <span
          class="badge rounded-pill fs-5 col-1 m-4"
          v-bind:class="timer > 9 ? 'bg-info' : 'bg-warning'"
        >
          {{ timer }}
        </span>
      </div>
      <div class="row justify-content-center align-items-center">
        <span class="badge bg-secondary fs-4 col-3">{{
          words[currentWordIndex]
        }}</span>
      </div>
      <div
        class="row form-floating m-3 justify-content-center align-items-center"
      >
        <div class="col-4">
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
        <button class="col-2 m-4 bg-light" v-on:click="startGame">
          Restart
        </button>
        <button class="col-2 m-4 bg-light" v-on:click="quitGame">
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
      <div class="row justify-content-center align-items-center">
        <p class="text-center">Your score was:</p>
        <p class="fw-bold fs-4 text-center">{{ points }}</p>
        <div class="mb-3" v-if="newHighscore">
          <p class="text-center fs-5">That is a new highscore!</p>
          <form @submit.prevent="addNewHighscore">
            <div class="row justify-content-center align-items-center">
              <div class="col-3">
                <label for="userNameInput" class="form-label"
                  >Enter username:</label
                >
                <input
                  type="text"
                  class="form-control invalid"
                  id="userNameInput"
                  v-model="userName"
                />
                <span v-if="!userNameIsValid" class="badge bg-danger">
                  Username must be between 1 and 20 characters.
                </span>
              </div>
              <div class="row justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary col-1 m-2">
                  Submit
                </button>
              </div>
            </div>
          </form>
        </div>
        <div v-else class="row justify-content-center align-items-center">
          <button class="col-2 m-4 bg-info" v-on:click="startGame">
            Play Again
          </button>
          <button class="col-2 m-4 bg-info" v-on:click="quitGame">
            Main Menu
          </button>
        </div>
        <div class="mt-2">
          <h3>Highscores:</h3>
          <table class="table table-success table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Score</th>
                <th scope="col">Date</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(highscore, index) in highscores" :key="highscore.id">
                <th scope="row">{{ index + 1 }}</th>
                <td class="fs-6">{{ highscore.name }}</td>
                <td class="fs-6">{{ highscore.score }}</td>
                <td class="fs-6">{{ formatDate(highscore.date) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
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
      highscores: [],
      newHighscore: false,
      userName: "",
      userNameIsValid: true,
    };
  },
  methods: {
    startGame() {
      this.words = [];
      axios.get("/words/" + this.wordLanguage).then((response) => {
        this.words = response.data.words;
        this.currentWordIndex = 0;
        this.userInput = "";
        this.userInputWords = [];
        this.newHighscore = false;
        this.points = 0;
        this.startTimer();
        this.userName = "";
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
      this.timer = 60;
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
      axios.get("/highscores").then((response) => {
        if (this.points < 0) this.points = 0;
        this.highscores = response.data;
        if (
          this.highscores.length < 10 ||
          this.highscores.at(-1).score < this.points
        ) {
          this.newHighscore = true;
        }

        this.gameEnded = true;
      });
    },
    addNewHighscore() {
      this.userName = this.userName.trim();
      let userNameLength = this.userName.length;

      if ((this.userNameIsValid = userNameLength > 0 && 21 > userNameLength)) {
        axios
          .post("/highscores", { name: this.userName, score: this.points })
          .then((response) => {
            this.highscores = response.data;
            this.newHighscore = false;
          });
      }
    },
    formatDate(date) {
      return moment(String(date)).format("DD.MM.YYYY");
    },
  },
};
</script>
