<template>
    <div class="building-container">
        <div v-for="b in buildings" :key="b.name" :alt="b.name" class="building" :style="b.style"
            @click="goTo(b.route)"></div>
    </div>
</template>

<script>
import { gameState } from '../store/gameState.js'
import Navbar from "./Navbar.vue";

export default {
    name: "GameMap",
    components: {
        Navbar
    },
    data() {
        return {
            buildings: [
                {
                    name: "Real Estate",
                    // No img source needed here!
                    route: "/quiz/bank",
                    // Define a clickable box over the Realty building in the background
                    style: "bottom: 25%; left: 8%; width: 20%; height: 30%;"
                },
                {
                    name: "City Hall",
                    route: "/quiz/cityhall",
                    // Define a clickable box over the City Hall building
                    style: "top: 10%; left: 30%; width: 35%; height: 40%;"
                },
                {
                    name: "Store",
                    route: "building/supermarket",
                    // Define a clickable box over the Store building (upper right)
                    style: "top: 20%; left: 60%; width: 30%; height: 35%;"
                },
                {
                    name: "Bank",
                    route: "/quiz/online",
                    // Define a clickable box over the Bank building (middle right)
                    style: "top: 30%; left: 70%; width: 25%; height: 30%;",
                },
                {
                    name: "Market",
                    route: "/quiz/department",
                    // Define a clickable box over the Market Square building (center bottom)
                    style: "bottom: 15%; left: 45%; width: 25%; height: 30%;",
                },
            ],
        };
    },
    methods: {
        // ... your existing methods ...
        goTo(route) {
            this.$router.push(route);
        },
    },
};
</script>

<style scoped>
/* 🔑 KEY: Ensures this container fills the parent (intro-bg) and is the positioning context */
.building-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.building {
    /* 🔑 KEY: Makes the element positionable and clickable but transparent */
    position: absolute;
    cursor: pointer;
    background-color: transparent;

    /* ⚠️ UNCOMMENT THE LINE BELOW FOR TESTING to see where your clickable areas are */
    /* border: 1px dashed red; */

    transition: all 0.2s ease-out;
}

.building:hover {
    z-index: 50;
    /* Optional: Provides a visual feedback when hovering over the invisible area */
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 10px rgba(0, 0, 0, 0.5);
    border-radius: 5px;
}
</style>