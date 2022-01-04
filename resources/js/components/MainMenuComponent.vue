<template>
  <div>
    <div class="row justify-content-center mb-4">
      <p class="text-center col-auto col-lg-5">
        Type the words you as precisely as you can before timer runs out. The
        points are determined on how precisely you type the words.
      </p>
      <p class="text-center">Good luck!</p>
    </div>

    <div class="row justify-content-center align-items-center">
      <div class="row justify-content-center">
        <p class="col-auto text-center">Select the language of words:</p>
      </div>
      <form
        class="mt-1"
        @submit.prevent="$emit('chooseLanguage', wordLanguage)"
      >
        <div class="row justify-content-center align-items-center">
          <div class="col-auto">
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
          <div class="col-auto">
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
          <input
            class="col-auto m-3 bg-light"
            type="submit"
            value="Start Game"
          />
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      wordLanguage: "en",
    };
  },
};
</script>