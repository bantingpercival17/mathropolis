<template>
    <div class="quiz-screen">
        <h2>{{ storeLabel }} Challenge</h2>

        <div v-if="!submitted">
            <p>{{ question.text }}</p>
            <input v-model.number="answer" type="number" placeholder="Enter your answer" />
            <button @click="submitAnswer">Submit</button>
        </div>

        <div v-else>
            <p v-if="correct">✅ Correct! You earned coins.</p>
            <p v-else>❌ Incorrect. The right answer was {{ question.correct }}</p>
            <button @click="finishLevel">Continue</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Quiz",
    data() {
        return {
            question: { text: "What is 20% of 250?", correct: 50 },
            answer: null,
            submitted: false,
            correct: false,
        };
    },
    computed: {
        store() {
            return this.$route.params.store;
        },
        storeLabel() {
            return this.store.charAt(0).toUpperCase() + this.store.slice(1);
        },
    },
    methods: {
        submitAnswer() {
            this.correct = Number(this.answer) === this.question.correct;
            this.submitted = true;
        },
        finishLevel() {
            // Update local progress
            let progress = JSON.parse(localStorage.getItem("progress")) || { guest: true, unlocked: ["bank"] };

            // Simple unlocking logic
            if (this.correct && !progress.unlocked.includes(this.store)) {
                progress.unlocked.push(this.store);
                if (this.store === "bank") progress.unlocked.push("supermarket");
            }

            localStorage.setItem("progress", JSON.stringify(progress));
            this.$router.push("/map");
        },
    },
};
</script>
