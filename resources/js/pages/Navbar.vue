<template>
    <nav class="navbar navbar-expand bg-light shadow-sm px-3 justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-link p-0 me-3" @click="toggleMenu">
                <i class="bi bi-list fs-3"></i>
            </button>
            <button class="btn btn-link p-0">
                <i class="bi bi-person-circle fs-3"></i>
            </button>
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-coin me-1 fs-4 text-warning"></i>
            <span class="fw-bold">{{ coins }}</span>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Navbar',
    props: {
        coins: {
            type: Number,
            default: 0
        }
    },
    methods: {
        toggleMenu() {
            // Logic to open/close a side menu
            console.log('Toggling menu...');
            // You can emit an event here to notify the parent component
            this.$emit('toggle-menu');
        },
        goToProfile() {
            // Logic to navigate to the user profile page
            console.log('Navigating to profile...');
            this.$router.push('/profile');
        }
    }
};
</script>

<style scoped>
.modern-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background-color: #f7f7f7;
    /* Light background */
    border-bottom: 2px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}

.nav-left,
.nav-right {
    display: flex;
    align-items: center;
}

.nav-icon {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    margin-right: 1rem;
    transition: transform 0.2s ease-in-out;
}

.nav-icon:hover {
    transform: scale(1.1);
}

.menu-icon .bar {
    display: block;
    width: 25px;
    height: 3px;
    background-color: #333;
    margin: 5px 0;
}

.profile-icon i {
    font-size: 2rem;
    color: #555;
}

.coin-display {
    display: flex;
    align-items: center;
    background-color: #fff;
    border-radius: 25px;
    padding: 0.5rem 1rem;
    border: 2px solid #ffd700;
    /* Gold border */
}

.coin-icon {
    color: #ffd700;
    /* Gold color */
    font-size: 1.5rem;
    margin-right: 0.5rem;
}

.coin-amount {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
}
</style>