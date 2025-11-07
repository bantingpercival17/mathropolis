<template>
    <div class="game-container d-flex flex-column vh-100">
        <!-- Top Nav -->
        <Navbar coins="0" />

        <!-- Background map area -->
        <div class="flex-fill position-relative intro-bg">
            <div class="overlay-card d-flex justify-content-center align-items-center">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from './Navbar.vue';

export default {
    components: {
        Navbar
    },
    methods: {
        toggleMenu() {
            // open side menu or offcanvas if you add one
        },
        playAsGuest() {
            localStorage.setItem(
                "progress",
                JSON.stringify({
                    guest: true,
                    coins: this.coins,
                    unlocked: ["home"]
                })
            );
            this.$router.push("/map");
        }
    }
};
</script>

<style scoped>
.game-container {
    background: #dff5f2;
}

.intro-bg {
    background: url('/images/city-map-v1.png') center/cover no-repeat;
}

.overlay-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.text-shadow {
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

.overlay-card {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    /* keep it from stretching too wide */
}

.text-shadow {
    text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
}
</style>
