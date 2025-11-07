<!-- <template>
    <div class="map-screen">
        <h2>Mathropolis City</h2>
        <div>
            <button v-for="store in stores" :key="store.name" :disabled="!progress.unlocked.includes(store.name)"
                @click="goToQuiz(store.name)">
                {{ store.label }} <span v-if="!progress.unlocked.includes(store.name)">🔒</span>
            </button>
        </div>
        <button v-if="progress.guest" @click="$router.push('/login')">
            Login to Unlock Final Challenge
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            stores: [
                { name: "bank", label: "Bank" },
                { name: "supermarket", label: "Supermarket" },
                { name: "fastfood", label: "Fast Food" },
                { name: "department", label: "Department Store" },
                { name: "online", label: "Online Store" },
                { name: "final", label: "Final Challenge" },
            ],
            progress: JSON.parse(localStorage.getItem("progress")) || { guest: true, unlocked: ["bank"] }
        };
    },
    methods: {
        goToQuiz(store) {
            if (store === "final" && this.progress.guest) {
                alert("Please login to continue!");
                this.$router.push("/login");
            } else {
                this.$router.push(`/quiz/${store}`);
            }
        }
    }
};
</script>
 -->
<template>
  <!--   <Navbar coin="1000" /> -->
    <div class="game-container d-flex flex-column vh-100">

        <div class="flex-fill position-relative intro-bg">
            <img v-for="b in buildings" :key="b.name" :src="b.img" :alt="b.name" class="building" :style="b.style"
                @click="goTo(b.route)" />
        </div>
        <!-- Individual buildings -->

    </div>
</template>

<script>
import imageMap from "../images/city-map-v2.png";
import { gameState } from '../store/gameState.js'
import Navbar from "./Navbar.vue";
export default {
    name: "GameMap",
    components: {
        Navbar
    },
    data() {
        return {
            imageMap,
            buildings: [
                {
                    name: "Real Estate",
                    img: "/images/realEstate.png",
                    route: "/quiz/bank",
                    style: "top: 35%; left: 5%; width: 20%;",
                },
                {
                    name: "City Hall",
                    img: "/images/cityhall.png",
                    route: "/quiz/cityhall",
                    style: "top: -17%; left: 18%; width: 30%;",
                },
                {
                    name: "Store",
                    img: "/images/store.png",
                    route: "/quiz/fastfood",
                    style: "top: -5%; left: 56%; width: 26%;",
                },
                {
                    name: "Bank",
                    img: "/images/bank.png",
                    route: "/quiz/online",
                    style: "top: 27.5%; left: 75.5%; width: 23%;",
                },
                {
                    name: "Market",
                    img: "/images/market.png",
                    route: "/quiz/department",
                    style: "top: 52%; left: 50%; width: 30%;",
                },
            ],
        };
    },
    methods: {
        enterBuilding(name) {
            if (!gameState.unlocked[name]) {
                alert('Complete the previous level to unlock this building!');
                return;
            }
            // router push to quiz/scene for this building
            // router.push(`/quiz/${name}`);
        },
        goTo(route) {
            this.$router.push(route);
        },

    },
};
</script>

<style scoped>
.game-container {
    background: #dff5f2;
}

.intro-bg {
    background: url('/images/city-map-v2.png') center/cover no-repeat;
}


.building {
    position: absolute;
    cursor: pointer;
    transition: transform 0.25s ease;
}

.building:hover {
    transform: scale(1.3);
    z-index: 10;
   
}
</style>
