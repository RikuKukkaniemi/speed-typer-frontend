<template>
  <div>
    <div class="row justify-content-center align-items-center">
      <p class="text-center">Your score was:</p>
      <p class="fw-bold fs-4 text-center">{{ points }}</p>
      <div class="mb-3" v-if="isNewHighscore">
        <p class="text-center fs-5">That is a new highscore!</p>
        <form @submit.prevent="addNewHighscore">
          <div class="row justify-content-center align-items-center">
            <div class="col-auto">
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
              <button type="submit" class="btn btn-primary col-auto m-2">
                Submit
              </button>
            </div>
          </div>
        </form>
      </div>
      <div v-else class="row justify-content-center align-items-center">
        <button
          class="col-auto m-4 bg-info"
          v-on:click="$emit('playAgain', wordLanguage)"
        >
          Play Again
        </button>
        <button
          class="col-auto m-4 bg-info"
          v-on:click="$emit('returnMainMenu')"
        >
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
</template>
<script>
import moment from "moment";

export default {
  props: {
    points: Number,
    wordLanguage: String,
  },
  data() {
    return {
      userName: "",
      userNameIsValid: true,
      highscores: [],
      isNewHighscore: false,
    };
  },
  methods: {
    addNewHighscore() {
      this.userName = this.userName.trim();
      let userNameLength = this.userName.length;

      if ((this.userNameIsValid = userNameLength > 0 && 21 > userNameLength)) {
        axios
          .post("/highscores", { name: this.userName, score: this.points })
          .then((response) => {
            this.highscores = response.data;
            this.isNewHighscore = false;
          });
      }
    },
    formatDate(date) {
      return moment(String(date)).format("DD.MM.YYYY");
    },
  },
  mounted: function () {
    axios.get("/highscores").then((response) => {
      this.highscores = response.data;
      if (
        this.highscores.length < 10 ||
        this.highscores.at(-1).score < this.points
      ) {
        this.isNewHighscore = true;
      }
    });
  },
};
</script>