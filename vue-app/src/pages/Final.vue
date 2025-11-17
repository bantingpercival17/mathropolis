<template>
  <div class="final-screen">
    <h1>🏆 Grand Finale: City Budgeting Challenge</h1>
    <p v-if="!done">
      You have a 10,000-coin city budget. Allocate at least 30% to savings and
      40% to essential expenses. How much will you save?
    </p>

    <div v-if="!done">
      <input v-model.number="saving" type="number" placeholder="Savings amount" />
      <button @click="submit">Submit</button>
    </div>

    <div v-else>
      <p v-if="success">🎉 You did it! Congratulations on mastering Mathropolis!</p>
      <p v-else>❌ Try again to meet the savings requirement.</p>
      <button @click="$router.push('/map')">Back to Map</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "Final",
  data() {
    return {
      saving: null,
      done: false,
      success: false,
    };
  },
  created() {
    const token = localStorage.getItem("token");
    if (!token) {
      alert("You must log in to play the final challenge.");
      this.$router.push("/login");
    }
  },
  methods: {
    submit() {
      this.done = true;
      this.success = this.saving >= 3000; // 30% of 10,000
    },
  },
};
</script>
