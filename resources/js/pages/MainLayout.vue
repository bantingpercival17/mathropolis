<template>
    <div class="game-container d-flex flex-column vh-100">
        <div class="flex-fill position-relative intro-bg">
            <Navbar :coins="0" />
            <router-view />
           

        </div>
    </div>
</template>

<script>
import Navbar from './Navbar.vue';

export default {
    components: {
        Navbar
    },
    data() {
        return {
            bgMusic: null
        }
    },

    mounted() {
        // Load the music file
        this.bgMusic = new Audio('/audio/map.mp3')
        this.bgMusic.loop = true
        /* this.bgMusic.volume = 01 */

        // Wait for a user click/touch (autoplay policy)
        // Try to autoplay
        this.bgMusic.play().catch(err => {
            console.warn('Autoplay blocked:', err)
        })
    },

    // ... rest of your script
};
</script>

<style>
.game-container {
    background: #dff5f2;
}

/* 🎨 THE RESPONSIVE BACKGROUND MAP STYLES */
.intro-bg {
    /* 1. Sets the image source */
    background-image: url('/images/city-map-v1.png');
    /* 2. Centers the image */
    background-position: center;
    /* 3. SCALES the image to cover the entire container (the key to responsiveness) */
    background-size: cover;
    /* 4. Prevents the image from tiling */
    background-repeat: no-repeat;
    /* This element is the positioning context for the GameMap component */
    position: relative;
}

</style>